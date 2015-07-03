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
class im {

    //checks if $lastMessageReceived = newest message id
    public function checkForMessages($lastMessageReceived){
        $user = getUser();
        
        $db = new db();
        $lastMessageReceived = (int)$lastMessageReceived;
        $where = "`id`>$lastMessageReceived AND (`receiver`='$user' OR `sender`='$user')";
        
        $result = $db->shiftResult($db->select('messages', $where,  array('id', 'receiver', 'sender')), 'id');

        
        return $result;
    }
 
}

class sessions{
    
    function createSession($title, $identifier){
        
        $db = new db();
        $db->insert('sessions', array('title'=>$title, 'identifier'=>$identifier, 'user'=>getUser()));
        
    }
    function getSessionInformation($session_identifier, $information_title){
        
        $db = new db();
        $data = $db->select('sessions', array('user', getUser(), '&&', 'identifier', $session_identifier));
        
        $parsed_information = json_decode($data['sessionInfo']);
        
        return $parsed_information[$information_title];
    }
    function updateSessionInformation($session_identifier, $options){
        //select data from db and parse json
        $db = new db();
        $data = $db->select('sessions', array('user', getUser(), '&&', 'identifier', $session_identifier));
        $parsed_information = json_decode($data['sessionInfo']);
        
        foreach($options AS $index=>$value){
            $parsed_information[$index] = $value;
        }
        $db->update('sessions', json_encode($options), array('user', getUser(), '&&', 'identifier', $session_identifier));
    }
    
}


