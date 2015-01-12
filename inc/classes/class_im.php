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


