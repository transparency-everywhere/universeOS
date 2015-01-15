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
                            $session .= "newPersonalEvent $personalEventData[id]";
                        $newEventSql2 = mysql_query("SELECT username FROM user WHERE userid='$personalEventData[user]'");
                        $newEventData2 = mysql_fetch_array($newEventSql2);
                        $countEvents++;

                            //comments
                        if($personalEventData['event'] == "comment"){
                            if($personalEventData['info'] == "feed"){
                                $description = "Has commented your post.";
                                $link = "reader.tabs.addTab(\'Comment\', \'\',gui.loadPage(\'modules/reader/showComment.php?type=$personalEventData[info]&itemid=$personalEventData[eventId]\')); return false";
                            }else if($personalEventData['info'] == "profile"){
                                $description = "Has commented in your profile.";
                                $link = "showProfile(\'".$_SESSION['userid']."\');";
                            }
                        }
                            //events
                        if($personalEventData['event'] == "event"){
                            $events = new events($personalEventData['eventId']);
                                    $eventData = $events->getData($personalEventData['eventId']);


                            $description = 'Invited you to the event "<a href="#" onclick="events.show(\\\''.$personalEventData['eventId'].'\\\');">'.$eventData['title'].'</a>"';

                                    if(!empty($eventData['place']))
                                            $description .= ' at '.$eventData['place'];

                                    $link = "events.joinForm(\'".$personalEventData['eventId']."\');";

                        }

                        ?>
                        <script>
                            if($("#personalEvent_<?=$personalEventData['event'];?>_<?=$personalEventData['info'];?>_<?=$personalEventData['eventId'];?>").length == 0){
                                $('#systemAlerts').append('<li id="personalEvent_<?=$personalEventData['event'];?>_<?=$personalEventData['info'];?>_<?=$personalEventData['eventId'];?>"><?=showUserPicture("$personalEventData[user]", '15', '', true);?><div class="messageMain"><a href="#" onclick="showProfile(\'<?=$personalEventData[user];?>\');"><?=$newEventData2[username];?></a> <?=$description;?></div><div class="messageButton"><a class="btn btn-info btn-mini" target="submitter" style="margin-right:25px;" onclick="deleteFromPersonals(\'<?=$personalEventData[id];?>\');<?=$link;?>$(\'#personalEvent_<?=$personalEventData[event];?>_<?=$personalEventData[info];?>_<?=$personalEventData[eventId];?>\').remove();">Show</a><a href="#" class="btn btn-mini" target="submitter" onclick="deleteFromPersonals(\'<?=$personalEventData[id];?>\');$(\'#personalEvent_<?=$personalEventData[event];?>_<?=$personalEventData[info];?>_<?=$personalEventData[eventId];?>\').remove();">Ignore</a></div></li>');
                            }
                        </script>
                    <?
                    $i++;
                    }
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