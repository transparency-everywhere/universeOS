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
            mysql_fetch_array(mysql_query("DELETE FROM `salts` WHERE `type`='$type' AND itemId='$itemId'"));

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
            mysql_fetch_array(mysql_query("DELETE FROM `salts` WHERE `type`='$type' AND itemId='$itemId'"));

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

            $data = mysql_fetch_array(mysql_query("SELECT * FROM `salts` WHERE `type`='$type' AND itemId='$itemId' LIMIT 1"));
            return $data['salt'];
    }
}



