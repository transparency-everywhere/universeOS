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
class folder {

    public $id;

    function __construct($id = NULL) {
        if ($id != NULL) {
            $this->id = $id;
        }
    }

    function create($superiorFolder, $title, $user, $privacy, $createFeed = true) {
        $title = sanitizeText($title);
        $title = filename_safe($title);
            
            $dbClass = new db();
            $folderData = $dbClass->select('folders', array('id', $superiorFolder));
            $folderClass = new folder($superiorFolder);
            $relativePath = $folderClass->getPath() . $title;
            $absolutePath = universeBasePath . '/' . $relativePath;
            $oldmask = umask(0);
            if (!file_exists($absolutePath) && mkdir($absolutePath,0755)) {
                umask($oldmask);
                $time = time();

                $values['folder'] = $superiorFolder;
                $values['name'] = $title;
                $values['path'] = $relativePath;
                $values['creator'] = $user;
                $values['timestamp'] = $time;
                $values['privacy'] = $privacy;
                $db = new db();
                $folderId = $db->insert('folders', $values);
                
                if($createFeed){
                    $feed = "has created a folder";
                    $feedClass = new feed();
                    $feedClass->create($user, $feed, "", "showThumb", $privacy, "folder", $folderId);
                }
                //return true;

                return $folderId;
            } else {
                jsAlert("The folder hasnt been created.");
                return false;
            }
    }

    function select() {
        $db = new db();
        return $db->select('folders', array('id', $this->id));
    }

    function update($parent_folder, $title, $privacy) {
        $title = sanitizeText($title);

        $db = new db();
        $checkFolderData = $db->select('folders', array('id', $this->id));

        $folderClass = new folder($checkFolderData['folder']);
        $parentFolderPath = universeBasePath . '/' . $folderClass->getPath();

        //check if folder exists
        if (!file_exists($parentFolderPath . urldecode($title))) {
            //rename folder
            if (rename($parentFolderPath . $checkFolderData['name'], $parentFolderPath . urldecode(save($title)))) {
                //update db
                $values['name'] = $title;
            }
        } else {
            $values['privacy'] = $privacy;
            $db->update('folders', $values, array('id', $this->id));
        }

        $values['privacy'] = $privacy;

        $db->update('folders', $values, array('id', $this->id));
        return true;
    }

    function loadFolderArray($type) {
        $folder = $this->id;
        $db = new db();
        switch ($type) {

            //loads all subordinated folders of $folder

            case 'children':
                $folders = $db->shiftResult($db->select('folders', array('folder', $folder), array('id')), 'id');
                foreach ($folders AS $folderData) {
                    $return[] = $folderData['id'];
                }
                break;

            case 'path':

                //maximum while 
                $maxqueries = 150;
                $i = 0;

                while (($folder != "0" || $folder != "0") && $i < $maxqueries) {
                    if (!empty($folder)) {
                        $folderData = $db->select('folders', array('id', $folder), array('name', 'folder'));
                        //var_dump($folderData);

                        $folder = $folderData['folder'];
                        if ($folder != 0) {
                            $returnFolder[] = $folder;
                            $returnName[] = $folderData['name'];
                        }
                    }
                    $i++;
                }

                $return['ids'] = $returnFolder;
                $return['names'] = $returnName;
                break;
        }

        return $return;
    }

    function getFolderData() {
        $folderId = $this->id;
        $dbClass = new db();
        $data = $dbClass->select('folders', array('id', save($folderId)));
        return $data;
    }

    function getTitle() {
        $folderId = $this->id;
        $folderData = $this->getFolderData();
        return $folderData['name'];
    }

    function getPath($showRealPath = true) {
        $folderId = $this->id;
        if ($showRealPath)
            $path = "upload/";
        else
            $path = '';
        $folderArray = $this->loadFolderArray("path");
        if(is_array($folderArray['names'])){
                $folderArray = array_reverse($folderArray['names'], true);
            foreach ($folderArray as $folder) {
                $folder = urldecode($folder);
                $path .= "$folder/";
            }
        }
        return $path;
    }

    function getItems() {
        $folderId = $this->id;
        $db = new db();
        if(is_numeric($folderId)){
            //select subfolders in folder
            $folders = $db->shiftResult($db->select('folders', array('folder', $folderId)), 'id');
            foreach ($folders as $folderData) {
                $folderData['iconsrc'] = "img/folder_dark.png";
                if(authorize($folderData['privacy'], "show", $folderData['creator']))
                    $result[] = array('type' => 'folder', 'data' => $folderData);
            }

            //select elements in folder
            $elements = $db->shiftResult($db->select('elements', array('folder', $folderId)), 'id');
            foreach ($elements as $elementData) {
                $elementData['iconsrc'] = "gfx/icons/filesystem/element.png";
                if(authorize($elementData['privacy'], "show", $elementData['author']))
                    $result[] = array('type' => 'element', 'data' => $elementData);
            }

            //select shortcuts in folder
            $shortcuts = $db->shiftResult($db->select('internLinks', array('parentId', $folderId, 'AND', 'parentType', 'folder')), 'id');
            foreach ($shortcuts as $shortcutData) {
                if($shortcutData['type'] === 'folder') {
                    $folders = $db->shiftResult($db->select('folders',array('id', $shortcutData['typeId'])), 'id');
                    foreach ($folders as $folderData) {
                        $folderData['iconsrc'] = "img/folder_dark.png";
                        $folderData['shortcut'] = true;
                        if(authorize($folderData['privacy'], "show", $folderData['creator'])){
                            $result[] = array('type' => 'folder', 'data' => $folderData);
                        }
                    }
                }
                if($shortcutData['type'] === 'element') {
                    $elements = $db->shiftResult($db->select('elements', array('folder', $shortcutData['typeId'])), 'id');
                    foreach ($elements as $elementData) {
                        $elementData['iconsrc'] = "gfx/icons/filesystem/element.png";
                        $elementData['shortcut'] = true;
                        if(authorize($elementData['privacy'], "show", $elementData['author'])){
                            $result[] = array('type' => 'element', 'data' => $elementData);
                        }
                    }
                }
            }
        }else{
            //special querys, like show all music, show popular files etc. from the left sidebar
            $specialType = $folderId;
            if($specialType === "pupularity"){
                $folders = $db->shiftResult($db->query("SELECT * FROM folders ORDER BY score DESC LIMIT 0,10"), 'id');
                $elements = $db->shiftResult($db->query("SELECT * FROM elements ORDER BY score DESC LIMIT 0,10"), 'id');
            }else if($specialType === "myfiles"){
                $folders = $db->shiftResult($db->query("SELECT * FROM folders WHERE creator = " . getUser() . " ORDER BY name ASC"), 'id');
                $elements = $db->shiftResult($db->query("SELECT * FROM elements WHERE author  = " . getUser() . " ORDER BY title ASC"), 'id');
            }else if($specialType === "audio"){
                $elements = $db->shiftResult($db->query("SELECT * FROM elements WHERE type LIKE '%audio%' ORDER BY score DESC LIMIT 0,10"), 'id');
            }else if($specialType === "video"){
                $elements = $db->shiftResult($db->query("SELECT * FROM elements WHERE type LIKE '%video%'  ORDER BY score DESC LIMIT 0,10"), 'id');
            }else if($specialType === "document"){
                $elements = $db->shiftResult($db->query("SELECT * FROM elements WHERE type LIKE '%document%'  ORDER BY score DESC LIMIT 0,10"), 'id');
            }
            if(isset($folders)){
                foreach ($folders as $folderData) {
                    $folderData['iconsrc'] = "img/folder_dark.png";
                    if(authorize($folderData['privacy'], "show", $folderData['creator']))
                        $result[] = array('type' => 'folder', 'data' => $folderData);
                }
            }
            foreach ($elements as $elementData) {
                $elementData['iconsrc'] = "gfx/icons/filesystem/element.png";
                if(authorize($elementData['privacy'], "show", $elementData['creator']))
                    $result[] = array('type' => 'element', 'data' => $elementData);
            }
        }
        return $result;
    }

    function delete() {
        $folderId = $this->id;
        $dbClass = new db();
        $folderData = $dbClass->select('folders', array('id', $folderId));


        //select and delete folders which are children of this folder
        $folders = $dbClass->shiftResult($dbClass->select('folders', array('folder', $folderId), array('id')), 'id');
        foreach ($folders AS $childrenFolderData) {
            $folder = new folder($childrenFolderData['id']);
            $folder->delete();
        }
        //select and delete element which are children of this folder
        $childElements = $dbClass->shiftResult($dbClass->select('elements', array('folder', $folderId), array('id')), 'id');
        foreach ($childElements AS $childrenElementData) {
            $element = new element($childrenElementData['id']);
            $element->delete();
        }
        $folderClass = new folder($folderId);
        $folderpath = universeBasePath . '/' . $folderClass->getPath();
        $dbClass->delete('folders', array('id', $folderId));
        system('/bin/rm -rf ' . escapeshellarg($folderpath));

        //delete comments, feeds and shortcuts
        $commentClass = new comments();
        $commentClass->deleteComments("folder", $folderId);

        $classFeed = new feed();
        $classFeed->deleteFeeds("folder", $folderId);

        $shortcutClass = new shortcut();
        $shortcutClass->deleteInternLinks("folder", $folderId);

        jsAlert("The folder has been deleted.");
        return true;
    }

}
