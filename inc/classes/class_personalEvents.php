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