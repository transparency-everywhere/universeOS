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

    public function routes(){

/*
./search/loadDockList/index.php
./search/query/index.php
./search/extendResults/index.php
./links/update/index.php
./links/delete/index.php
./links/select/index.php
./links/create/index.php
./buddies/add/index.php
./buddies/get/index.php
./buddies/acceptRequest/index.php
./buddies/remove/index.php
./folders/update/index.php
./folders/delete/index.php
./folders/select/index.php
./folders/getPath/index.php
./folders/create/index.php
./folders/getItems/index.php
./calendar/tasks/delete/index.php
./calendar/tasks/get/index.php
./calendar/tasks/create/index.php
./calendar/events/getEventData/index.php
./calendar/events/join/index.php
./calendar/events/create/index.php
./calendar/events/getEvents/index.php
./shortcuts/delete/index.php
./shortcuts/getData/index.php
./shortcuts/create/index.php
./elements/getFileNumbers/index.php
./elements/update/index.php
./elements/delete/index.php
./elements/getFileList/index.php
./elements/select/index.php
./elements/getAuthorData/index.php
./elements/create/index.php
./getPage/index.php
./playlists/idToTitle/index.php
./playlists/update/index.php
./playlists/getGroupPlaylists/index.php
./playlists/getUserPlaylists/index.php
./playlists/select/index.php
./playlists/getPublicPlaylists/index.php
./playlists/removeItem/index.php
./playlists/pushItem/index.php
./playlists/create/index.php
./appCenter/getApps/index.php
./appCenter/prepareAppCreation/index.php
./appCenter/createApp/index.php
./appCenter/getAppDetailsForUser/index.php
./appCenter/getAppsForUser/index.php
./appCenter/addAppToUserDashboard/index.php
./appCenter/removeAppFromUserDashboard/index.php
./item/getOptions/index.php
./item/privacy/load/index.php
./item/privacy/index.php
./item/makeDeletable/index.php
./item/getItemThumb/index.php
./item/loadMiniFileBrowser/index.php
./item/score/scoreMinus/index.php
./item/score/scorePlus/index.php
./item/comments/load/index.php
./item/comments/delete/index.php
./item/comments/create/index.php
./item/comments/count/index.php
./item/removeProtection/index.php
./item/makeUndeletable/index.php
./item/getScore/index.php
./item/protect/index.php
./groups/removeUser/index.php
./groups/leave/index.php
./groups/removeFromAdmins/index.php
./groups/update/index.php
./groups/getPicture/index.php
./groups/delete/index.php
./groups/uploadGroupPicture/index.php
./groups/join/index.php
./groups/getUsers/index.php
./groups/getData/index.php
./groups/get/index.php
./groups/makeUserAdmin/index.php
./groups/create/index.php
./groups/getPublicGroups/index.php
./reload/index.php
./files/uff/write/index.php
./files/uff/create/index.php
./files/readJson/index.php
./files/read/index.php
./files/reader/index.php
./files/delete/index.php
./files/uploadTemp/index.php
./files/updateFileContent/index.php
./files/select/index.php
./files/submitUploader/index.php
./files/getMyFiles/index.php
./files/report/index.php
./user/updateProfileInfo/index.php
./user/updatePassword/index.php
./user/uploadUserPicture/index.php
./user/getProfileInfo/index.php
./user/getPrivacy/index.php
./user/getGroups/index.php
./user/getPicture/index.php
./user/getAllData/index.php
./user/deleteAccount/index.php
./user/getLastActivity/index.php
./user/updatePrivacy/index.php
./user/logout/index.php
./user/create/index.php
./user/proofLogin/index.php
./IM/addMessage/index.php
./IM/selectMessages/index.php
./handlers/index.php
./fav/add/index.php
./fav/select/index.php
./fav/remove/index.php
./sessions/checkFingerprint/index.php
./sessions/updateFingerprint/index.php
./sessions/getSessionInfo/index.php
./sessions/create/index.php
./sessions/updateSessionInfo/index.php
./feed/load/index.php
./feed/delete/index.php
./feed/loadFrom/index.php
./feed/select/index.php
./feed/create/index.php
*/



    }
    
    //put your code here
    public function init(){
    	if(proofLogin()){
                $db = new db();
                $checkData = $db->select('user', array('userid', getUser()), array('userid', 'hash'));
                //sense?
    	}else{
    		$_SESSION['loggedOut'] = true;
    	}
            $gui = new gui();
            $gui->generate();
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
	
	