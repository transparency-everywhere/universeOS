<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */

class personalEvents{
    
                function get(){
                    $personalEventSql = mysql_query("SELECT * FROM personalEvents WHERE owner='$_SESSION[userid]' AND seen='0'");
                    while($personalEventData = mysql_fetch_array($personalEventSql)){
                        

                            //comments
                        if($personalEventData['event'] == "comment"){
                            if($personalEventData['info'] == "feed"){
                                $description = "Has commented your post.";
                                $link = "reader.tabs.addTab(\'Comment\', \'\',gui.loadPage(\'modules/reader/showComment.php?type=$personalEventData[info]&itemid=$personalEventData[eventId]\')); return false";
                            }else if($personalEventData['info'] == "profile"){
                                $description = "Has commented in your profile.";
                                $link = "showProfile(\'".getUser()."\');";
                            }
                        }
                            //events
                        if($personalEventData['event'] == "event"){
                            $events = new events($personalEventData['eventId']);
                            $eventData = $events->getData($personalEventData['eventId']);


                            $description = 'Invited you to the event "<a href="#" onclick="events.show(\\\''.$personalEventData['eventId'].'\\\');">'.$eventData['title'].'</a>"';

                            if(!empty($eventData['place'])){
                                    $description .= ' at '.$eventData['place'];
                            }

                            $link = "events.joinForm(\'".$personalEventData['eventId']."\');";

                        }
                        
                        $result[] = array(  
                                            'action'=>'notification', //for js reload action
                                            'subaction'=>'push', //for js reload action
                                            'data'=>array(
                                                'id'=>$personalEventData['id'],
                                                'type'=>$personalEventData['event'],
                                                'info'=>$personalEventData['info'],
                                                'eventId'=>$personalEventData['ecentId'],
                                                'user'=>$personalEventData['user'],
                                                'description'=>$description,
                                                'link'=>$link)
                                            );
                        
                    }
                    return $result;
                }
		
		function create($owner,$user,$event,$info,$eventId){
                    $values['owner'] = $owner;
                    $values['user'] = $user;
                    $values['event'] = $event;
                    $values['info'] = $info;
                    $values['eventId'] = $eventId;
                    $values['timestamp'] = time();
                    
                    $db = new db();
                    return $db->insert('personalEvents', $values);
	        
		}
	}