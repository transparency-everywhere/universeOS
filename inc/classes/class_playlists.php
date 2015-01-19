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
class playlists {
    
        public $id;

        function __construct($id=NULL){
            if($id != NULL){
                $this->id = $id;
            }

        }
        
        function createPlaylist($title, $privacy, $user=null){
            if(empty($user)){
                $user = getUser();
            }
            
            $values['user'] = $user;
            $values['title'] = $title;
            $values['privacy'] = $privacy;
            
            $db = new db();
            return $db->insert('playlist', $values);
        }
    
	function getPlaylists($userid=NULL){
            $playlists;
            if(empty($userid))
                    $userid = getUser();
            $db = new db();
            $playlists = $db->shiftResult($db->select('playlists', array('user', $userid)),'id');
            
            foreach($playlists AS $data){
                    $playlists[] = $data['id'];
            }
            return $playlists;
	}
        
        function select(){
            $db = new db();
            return $db->select('playlist', array('id', $this->id));
        }
        
	function getPlaylistTitle($playlistId=NULL){
            if(empty($playlistId))
                $playlistId = $this->id;
                $db = new db();
		$data = $db->select('playlist', array('id', $playlistId), array('title'));
		return $data['title'];
	}
	
	function getUserPlaylistArray($userId=null, $type='show'){
		if($userId == null){
			$userId = getUser();
		}
		$db = new db();
                $userGroupsQuery = $db->select('groupAttachments', array('item', 'user', '&&', 'itemId', $userId), array('group'));
                
                
                
		//get all the groups in which the current user is
        foreach($userGroupsQuery AS $userGroupsData){
            $userGroups[] = $userGroupsData['group'];
        }
        
        //add them to the query
        foreach($userGroups AS &$userGroup){
                $query = "$query OR (INSTR(`privacy`, '{$userGroup}') > 0)";
        }
		
		$buddylistClass = new buddylist();
			//get playlists from friends
				$buddies = $buddylistClass->buddyListArray();
                $buddies = join(',',$buddies);
                $query .= "OR (INSTR(`user`, '{$buddies}') > 0)";
        
            //get playlists for user and groups
            $playListsSql = mysql_query("SELECT id, title, privacy, user FROM playlist WHERE user='".getUser()."' $query");
            while($playListsData = mysql_fetch_array($playListsSql)){
                if(authorize($playListsData['privacy'], $type, $playListsData['user'])){
	                $ids[] = $playListsData['id'];
	                $titles[] = $playListsData['title'];
				}
            }
			
			$return['ids'] = $ids;
			$return['titles'] = $titles;
			
			return $return;
        
		
	}
	
	function showPlaylist($id, $query=NULL){
		if(!empty($id))
			$query = "id='$id'";
		
        $playListSql = mysql_query("SELECT * FROM playlist WHERE $query");
        $playListData = mysql_fetch_array($playListSql);
		?>
		<table cellspacing="0" width="100%">                
                            <?php
                            $i = 0;
                            $query = commaToOr("$playListData[folders]", "id");
                            $playListFolderSql = mysql_query("SELECT * FROM folders WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation(folder, $playListFolderData['id'])){;
                            ?>
                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/folder.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData['name']?></td>
                                    </tr>

                            <?php 
                            $i++;

                            }}
                            $query = commaToOr($playListData['elements'], "id");
                            $playListFolderSql = mysql_query("SELECT id, title FROM elements WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){   
                            ?>

                                    <tr class="strippedRow">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;e_<?=$playListFolderData['title']?></td>
                                    </tr>
                        <?php 
                            $i++;

                            }}
                            $query = commaToOr("$playListData[files]", "id");    
                            $playListFolderSql = mysql_query("SELECT * FROM files WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=file&itemId=".$playListFolderData['id']."\" target=\"submitter\"><i class=\"icon icon-minus\"></i></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListfileNo<?=$playListFolderData['id'];?>">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData['title']?></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?php
                            $i++; }}
                            $query = commaToOr($playListData['links'], "id");
                            $playListFolderSql = mysql_query("SELECT * FROM links WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){    
                                if($playListLinkData['type'] == "youTube"){

                                }
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=link&itemId=".$playListFolderData['id']."\" target=\"submitter\"><i class=\"icon icon-minus\"></i></a></td>";
                            }

                            ?>

                                    <tr class="strippedRow playListlinkNo<?=$playListFolderData['id'];?>">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData['id'];?>', '<?=$i;?>')"><?=$playListFolderData['title']?></a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                        <?php
                            $i++; }}
                            $videos = explode(";", $playListData['youTube'], -1);
                            foreach($videos as &$vId){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=youTube&itemId=$vId\" target=\"submitter\"><i class=\"icon icon-minus\"></i></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListyouTubeNo<?=$vId;?> tooltipper" onmouseover="mousePop('youTube', '<?=$vId;?>', '');" onmouseout="$('.mousePop').hide();">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData['id'];?>', '<?=$i;?>')">Youtube Video</a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?php
                            $i++;

                            }?>
                                </table>
		<?php
	}
	
	function update($title, $privacy){
            $values['title'] = $title;
            $values['privacy'] = $privacy;
            
            $db = new db();
            return $db->update('playlist', $values, array('id', $this->id));
        }
	
}
