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
class salt {
    function create($type, $itemId, $receiverType, $receiverId, $salt){
            //stores encrypted salt in db
            $db = new db();
            
            //delete all old salts
            $db->delete('salts', array('type', $type, 'AND', 'itemId', $itemId));
            
                $values['type'] = $type;
                $values['itemId'] = $itemId;
                $values['receiverType'] = $receiverType;
                $values['receiverId'] = $receiverId;
                $values['salt'] = $salt;
                
                
            
                    if($db->insert('salts', $values))
                            return true;
                    else 
                            return false;

      }
      
      
    function update($type, $itemId, $salt){
           
            //stores encrypted salt in db
            $db = new db();
            
            //delete all old salts
            $db->delete('salts', array('type', $type, 'AND', 'itemId', $itemId));
            
                $values['type'] = $type;
                $values['itemId'] = $itemId;
                $values['receiverType'] = 'user';
                $values['receiverId'] = $itemId;
                $values['salt'] = $salt;

                    if($db->insert('salts', $values))
                            return true;
                    else 
                            return false;
    }
    function get($type, $itemId){
            $type = save($type);
            $itemId = save($itemId);
            $db = new db();
            $data = $db->select('salts', array('type', $type, 'AND', 'itemId', $itemId), array('salt'));
            return $data['salt'];
    }
}



