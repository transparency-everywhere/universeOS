<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_universe
 *
 * @author niczem
 */
class universe {
    
    public function getConfig(){
        return get_class_vars('uniConfig');
    }
    
    public function createConfig($options){
		
		$Datei = universeBasePath."/inc/classes/uni_config.php";
                
                $varOutput = '';
                foreach($options AS $optionTitle=>$option){
                    $varOutput .= '    public static $'.$optionTitle.' = \''.$option."';\n";
                }
                
$Text = '<?php
class uniConfig {
'.$varOutput.'
} ?>';


                if(file_exists($Datei))
                    unlink($Datei);
                
		$File = fopen($Datei, "w");
		fwrite($File, $Text);
		fclose($File);
    }
    
    public function init(){

        require 'vendor/autoload.php';

        $routes = uni_routes::getRoutes();
        $app = new \Slim\App;

        foreach($routes AS $route){
            $app->post('/api/'.$route['path'], function ($request, $response, $args) use ($route) {
                $route['callback']($request->getParsedBody());
            });
        }

        $app->post('/arpi/'.$route['path'], function ($request, $response, $args) use ($route) {
            $route['callback']($request->getParsedBody());
        });

        $app->get('/', function ($request, $response, $args) {

            if(proofLogin()){
                    $db = new db();
                    $checkData = $db->select('user', array('userid', getUser()), array('userid', 'hash'));
                    //sense?
            }else{
                $_SESSION['loggedOut'] = true;
            }
                $gui = new gui();
                $gui->generate();
        });
        $app->run();

        
    }
    public static function reload($requests){
                    require('inc/classes/class_sessions.php');
                    $user = getUser();
                    
                    $buddylist = new buddylist();
                    $groups = new groups();
                    
                    $userCounter = 0;
                    $eventCounter = 0;
                    $messageCounter = 0;
                    
                    //if the client fingerprint isn't found for user()->push result to show "add session popup"
                    $fingerprint = $requests[0]['fingerprint'];
                    $sessions = new sessions();
                    if(!$sessions->checkFingerprint($fingerprint)){
                        $result[] = array('action'=>'sessions','subaction'=>'not_found');
                    }
                //    if()
                    
                    foreach($requests AS $request){
                        switch($request['action']){
                            case'buddylist':
                                if($request['subaction'] == 'reload'){
                                    if($request['data']['buddy_checksum'] != $buddylist->getChecksum()){
                                        $result[] = array('action'=>'buddylist','subaction'=>'reload');
                                    }
                                }
                                break;
                                
                            case'IM':
                                if($request['subaction'] == 'sync'){
                                    $im = new im();
                                    
                                    //in case of different clients with different sessions, here needs to be the client_id or something
                                    
                                    $messagesToSync = $im->checkForMessages($request['data']['last_message_received']);
                                    foreach($messagesToSync AS $messageData){
                                        if(($messageData['receiver'] == getUser())&&($messageData['read']==1)){
                                            $messageCounter++;
                                        }
                                    }
                                    if($messagesToSync)
                                        $result[] = array('action'=>'IM','subaction'=>'sync', 'data'=>$messagesToSync);
                                }
                                break;
                            case 'UFF':
                                if($request['subaction'] == 'sync'){
                                    $uff = new uff($request['data']['file_id']);
                                    if($request['data']['checksum'] != $uff->getChecksum()){
                                        $result[] = array('action'=>'UFF','subaction'=>'sync', 'data'=>array('file_id'=>$request['data']['file_id'],'content'=>$uff->show()));
                                    }
                                }
                                break;
                            case 'feed':
                                if($request['subaction'] == 'sync'){
                                    $feed = new feed();
                                    foreach($request['data'] AS $requestData){
                                        $highest_id = $feed->getHighestId($requestData['type'], $requestData['typeId']);
                                        
                                        if(empty($requestData['typeId'])){
                                            $requestData['typeId'] = 0;
                                        }
                                        if($highest_id>$requestData['last_feed_received']){
                                            $result[] = array('action'=>'feed','subaction'=>'sync', 'data'=>array('type'=>$requestData['type'],'typeId'=>$requestData['typeId'],'highest_id'=>$highest_id));
                                        }
                                    }
                                }
                                break;
                        }
                    }
                    
                    foreach($groups->getOpenRequests() AS $openGroupRequestData){
                        $userCounter++;
                        $openGroupRequestData['group_title'] = $groups->getGroupName($openGroupRequestData['group_id']);
                        $result[] = array('action'=>'groups', 'subaction'=>'openRequest', 'data'=>$openGroupRequestData);
                    }
                    
                    
                    //check for open requests
                    $openBuddyRequests = $buddylist->getOpenRequests($user);
                    
                    //add open requests to result
                    if(count($openBuddyRequests > 1)){
                        foreach($openBuddyRequests AS $requestBuddyId=>$requestBUsername){
                            $userCounter++;
                            $result[] = array('action'=>'buddylist','subaction'=>'openRequest','data'=>array('userid'=>$requestBuddyId));
                        }
                    }
                    
                    
                    //other notifications
                    $personalEvents = new personalEvents();
                    $otherNotifications = $personalEvents->get();
                    
                    foreach($otherNotifications AS $otherNotification){
                        $result[] = $otherNotification;
                    }
                    
                    return $result;
                    
                }
    //reload page if session is expired is used in reload.php
    function proofSession(){
            if((!$_SESSION['loggedOut'])&&(!proofLogin())){
                    echo"<script>window.location.href='index.php'</script>";
                    $_SESSION['loggedOut'] = true;
            }
    }

    function universeText($str){

        $str = str_replace(":'(", '<a class="smiley smiley1"></a>', $str);//crying smilye /&#039; = '
        $str = str_replace(':|', '<a class="smiley smiley2"></a>', $str);
        $str = str_replace(';)', '<a class="smiley smiley3"></a>', $str);
        $str = str_replace(':P', '<a class="smiley smiley4"></a>', $str);
        $str = str_replace(':-D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':)', '<a class="smiley smiley6"></a>', $str);
        $str = str_replace(':(', '<a class="smiley smiley7"></a>', $str);
        $str = str_replace(':-*', '<a class="smiley smiley8"></a>', $str);
                    $str = preg_replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4",$str);
        # Links
        $str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);



        return $str;
    }
}
	
	