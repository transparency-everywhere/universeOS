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
class link {
	function create($folder, $title, $type, $privacy, $link){
            

                $values['folder'] = $folder;
                $values['type'] = $type;
                $values['title'] = $title;
                $values['link'] = $link;
                $values['privacy'] = $privacy;
                $values['author'] =  getUser();
                $values['timestamp'] = time();
                $db = new db();
                if($db->insert('links', $values)){
                	
                    $feedText = "has created the link $title in the folder";
                    $feedLink1 = mysql_insert_id();
                    $feedLink2 = $folder;
                    $feedClass = new feed();
                    $feedClass->add($user, $feedText, folderAdd, $feedLink1, $feedLink2);
					
					return true;
                }
	}
        
        function update($linkId, $element, $title, $link, $type, $privacy){
            $values['folder'] = $element;
            $values['title'] = $title;
            $values['link'] = $link;
            $values['type'] = $type;
            $values['privacy'] = $privacy;
            
            $db = new db();
            return $db->update('links', $values, array('id', $linkId));
        }
    
    function deleteLink($linkId){
        
                $linkSql = mysql_query("SELECT * FROM links WHERE id='$linkId'");
                $linkData = mysql_fetch_array($linkSql);
                    
                //file can only be deleted if uploader = deleter
                if(authorize($linkData['privacy'], "edit", $linkData['author'])){
                    
                       if(mysql_query("DELETE FROM links WHERE id='$linkId'")){
                           
                           //delete comments
                           $classComments = new comments();
                           $classComments->deleteComments("link", $linkId);
                           
                           $classFeed = new feed();
                           $classFeed->deleteFeeds("link", $linkId);
                           
                           $classShortcuts = new shortcut();
                           $classShortcuts->deleteInternLinks("link", $linkId);
                           
                           
                           
                               return true;
                       }else{
                               return false;
                       }
                }else{
                    return false;
                }
    }
    
    function select($linkId){
        $db = new db();
        return $db->select('links', array('id', $linkId));
    }
    
    
    
    function getType($linkId){
        
        $fileData = mysql_fetch_array(mysql_query("SELECT type FROM links WHERE id='".save($fileId)."'"));
        return $fileData['type'];
    }
}


