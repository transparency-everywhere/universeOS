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
    //put your code here
}

    function jPlayerFormat($title, $fileId, $type){
        $path = getFilePath($fileId);
        $path = "$path/$title";
        echo "{";
        echo "title: \"$title\",";
        echo "$type: \"./upload/$path\"";
        echo "}";
    }
	
	function getPlaylists($userid=NULL){
		if(empty($userid))
			$userid = getUser();
		
		$sql = mysql_query("SELECT `id` FROM playlist WHERE `user`='".mysql_real_escape_string($userid)."'");
		while($data = mysql_fetch_array($sql)){
			$playlists[] = $data[id];
		}
		
		return $playlists;
	}
	
	function getPlaylistTitle($playlistId){
		$sql = mysql_query("SELECT `title` FROM playlist WHERE id='".mysql_real_escape_string($playlistId)."'");
		$data = mysql_fetch_array($sql);
		return $data['title'];
		
		
		
		
	}
	
	function getUserPlaylistArray($userId=null, $type='show'){
		if($userId == null){
			$userId = getUser();
		}
		
		//get all the groups in which the current user is
        $userGroupsSql = mysql_query("SELECT `group` FROM `groupAttachments` WHERE item='user' AND itemId='$userId'");
        while($userGroupsData = mysql_fetch_array($userGroupsSql)){
            $userGroups[] = "$userGroupsData[group]";
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
                            <?
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

                            <? 
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
                        <? 
                            $i++;

                            }}
                            $query = commaToOr("$playListData[files]", "id");    
                            $playListFolderSql = mysql_query("SELECT * FROM files WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=file&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListfileNo<?=$playListFolderData['id'];?>">
                                        <td><img src="./modules/filesystem/icons/file.png" width="30px"></td>
                                        <td>&nbsp;<?=$playListFolderData['title']?></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++; }}
                            $query = commaToOr($playListData['links'], "id");
                            $playListFolderSql = mysql_query("SELECT * FROM links WHERE $query");
                            while($playListFolderData = mysql_fetch_array($playListFolderSql)){

                            if(checkAuthorisation($playListFolderData['privacy'])){    
                                if($playListLinkData['type'] == "youTube"){

                                }
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=link&itemId=$playListFolderData[id]\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }

                            ?>

                                    <tr class="strippedRow playListlinkNo<?=$playListFolderData[id];?>">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')"><?=$playListFolderData[title]?></a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                        <?
                            $i++; }}
                            $videos = explode(";", $playListData['youTube'], -1);
                            foreach($videos as &$vId){
                            if($delete){
                                $deleteRow = "<td><a href=\"doit.php?action=deleteFromPlaylist&playlist=$playListId&type=youTube&itemId=$vId\" target=\"submitter\"><img src=\"./gfx/icons/minus.png\" height=\"32\" border=\"0\"></a></td>";
                            }
                            ?>
                                    <tr class="strippedRow playListyouTubeNo<?=$vId;?> tooltipper" onmouseover="mousePop('youTube', '<?=$vId;?>', '');" onmouseout="$('.mousePop').hide();">
                                        <td><img src="./gfx/icons/fileIcons/youTube.png" width="20px" style="margin: 5px;"></td>
                                        <td>&nbsp;<a href="javascript: nextPlaylistItem('<?=$playListData[id];?>', '<?=$i;?>')">Youtube Video</a></td>
                                        <?=$deleteRow;?>
                                    </tr>
                            <?
                            $i++;

                            }?>
                                </table>
		<?
	}
	