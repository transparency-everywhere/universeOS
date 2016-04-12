<?php

//This file is published by transparency-everywhere with the best deeds.
//Check transparency-everywhere.com for further information.
//Licensed under the CC License, Version 4.0 (the "License");
//you may not use this file except in compliance with the License.
//You may obtain a copy of the License at
//
//https://creativecommons.org/licenses/by/4.0/legalcode
//
//Unless required by applicable law or agreed to in writing, software
//distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//See the License for the specific language governing permissions and
//limitations under the License.
//
//@author nicZem for Tranpanrency-everywhere.com

//sessions are used to store information on different clients
//which are need for synchronisation
class sessions{
    //validates if fingerprint for user -> user() exists
    function checkFingerprint($session_identifier){
        $db = new db();
        $data = $db->select('sessions', array('user', getUser(), '&&', 'fingerprint', $session_identifier));
        if(is_array($data)){
            return true;
        }else{
            return false;
        }
    }
    function createSession($identifier, $type, $title){
        echo 'asdasd';
        $db = new db();
        $data = $db->query('SELECT `sessionInfo` FROM `sessions` WHERE '
                         //. "TRIM(IFNULL(sessionInfo,'')) <> '' &&"
                         . '`user`=\''.getUser().'\'  ORDER BY `id` DESC LIMT 1');
        if($data['sessionInfo'] == 't')
            $values['sessionInfo'] = '{}';
        else
            $values['sessionInfo'] = $data['sessionInfo'];
        
        
        
        
        $values['type'] = $type;
        $values['timestamp'] = time();
        $values['fingerprint'] = $identifier;
        $values['user'] = getUser();
        if($title){
            $values['title'] = $title;
        }
        
        return $db->insert('sessions', $values);
        
    }
    //returns values for session (eg. last message received etc.)
    function getSessionInformation($session_identifier){
        $db = new db();
        $data = $db->select('sessions', array('user', getUser(), '&&', 'fingerprint', $session_identifier), NULL, NULL, 1);
    
        if(is_array($data))
            
            return $data['sessionInfo'];
        return null;
    }
    function updateFingerprint($old_fingerprint, $new_fingerprint){
        $db = new db();
        $db->update('sessions', array('new_fingerprint'=>$new_fingerprint), array('user', getUser(), '&&', 'fingerprint', $old_fingerprint));
    }
    //update/add specific value in the session informations
    //e.g. after a group request has been shown
    function updateSessionInformation($session_identifier, $key, $value){
        //parse old information
        $parsed_information = json_decode($this->getSessionInformation($session_identifier), true);
        //update add value to old information
        $parsed_information[$key] = $value;
        
        $values['sessionInfo'] = json_encode($parsed_information);
        $db = new db();
        $db->update('sessions', $values, array('user', getUser(), '&&', 'fingerprint', $session_identifier));
    }
    
}
