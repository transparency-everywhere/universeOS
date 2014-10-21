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

class contextMenu{
	public $type;
	public $itemId;
	public $title;
	public $info1;
	
	
	
	function __construct($type, $itemId, $title, $info1) {
      	$this->type = $type;
      	$this->itemId = $itemId;
		if(!empty($title)){
      		$this->title = $title;
		}
		if(!empty($info1)){
      		$this->info1 = $info1;
		}
	}
	function getOptions(){
		$itemId = $this->itemId;
		
				//init vars
	            $open[] = '';
	            $fav[] = '';
	            $privacy[] = '';
	            $playlist[] = '';
				$edit[] = '';
				$delete[] = '';
				$protect[] = '';
				$undeletable[] = '';
				
		switch($this->type){
			case 'feed':
	        	$feedCheck = mysql_query("SELECT author, privacy FROM feed WHERE id='$itemId'");
	            $feedData = mysql_fetch_array($feedCheck);
	            if(authorize('p', "edit", $feedData['author'])){
	            	$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=feed&itemId=$itemId')";
					

				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=feed&itemId=$itemId";
					$delete['target'] = 'submitter'; 
	            }
				
				$options[] = $privacy;
				$options[] = $delete;
 				break;
			case 'comment':
	        	$commentCheck = mysql_query("SELECT author, type, typeid, privacy FROM comments WHERE id='$itemId'");
	            $commentData = mysql_fetch_array($commentCheck);
	            
	            //allow profile owner to delete comments that other users made in his profile
	            if($commentData['type'] == "profile" && $commentData['typeid'] == getUser()){
	            	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=comment&itemId=$itemId";
					$delete['target'] = 'submitter'; 
				}
	            if(authorize('p', "edit", $commentData['author'])){
	                $privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=comment&itemId=$itemId')";
					

				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=comment&itemId=$itemId";
					$delete['target'] = 'submitter'; 
	            }
				
				$options[] = $delete;
				$options[] = $privacy;
				break;
			case 'internLink':
				$checkInternLinkData = mysql_fetch_array(mysql_query("SELECT * FROM internLinks WHERE id='$itemId'"));
            
                if($checkInternLinkData['type'] == "folder"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy, creator FROM folders WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "element"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, creator FROM elements WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "file"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type, owner FROM files WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['owner'];
                }else if($checkInternLinkData['type'] == "link"){
                    $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type, author FROM links WHERE id='$checkInternLinkData[typeId]'"));
                    $user = $shortCutItemData['author'];
                }
                
                if(authorize($shortCutItemData['privacy'], "edit", $user)){
				  	$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=internLink&itemId=$itemId";
					$delete['target'] = 'submitter'; 
                }
                $options[] = $delete;
				break;
			case "folder":
				
				
		    	$checkFolderSql = mysql_query("SELECT `privacy`, `creator` FROM `folders` WHERE id='$itemId'");
		        $checkFolderData = mysql_fetch_array($checkFolderSql);
				  
				$open['title'] = 'open';
				$open['href'] = '#';
				$open['onclick'] = "openFolder('$itemId')";
				
				if(proofLogin()){
					
					$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=folder&item=$itemId";
					$fav['target'] = 'submitter';
					
				}
				
				if(authorize($checkFolderData['privacy'], "edit", $checkFolderData['creator'])){
				
					$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=folder&itemId=$itemId')";
					
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=folder&itemId=$itemId')";
					
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=folder&itemId=$itemId";
					$delete['target'] = 'submitter';
					
				}
				
				
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkFolderData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=folder&itemId=$itemId')";

					}else{
						
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=folder&itemId=$itemId')";
						
					}
				}
	
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkFolderData['privacy'])){
						
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=folder&itemId=$itemId');";
					
					}else{
						
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=folder&itemId=$itemId');";
					
					}
					
				}
				
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $fav;
				$options[] = $protect;
				$options[] = $undeletable;
				
				
		      break;
		   	case "element":
			  
		    	$checkElementSql = mysql_query("SELECT privacy, author FROM elements WHERE id='$itemId'");
		      	$checkElementData = mysql_fetch_array($checkElementSql);
			 
			 
			 	$open['title'] = 'Open';
			 	$open['href'] = '#';
			 	$open['onclick'] = "openElement('$itemId', '$title');";
			 
		      	if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=element&itemId=$itemId')";
		         
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=element&itemId=$itemId')";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=element&itemId=$itemId";
					$delete['target'] = 'submitter';
		      	}
			 
			 	if(proofLogin()){
			 		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=element&item=$itemId";
					$fav['target'] = "submitter";
			 	}
			 
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkElementData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=element&itemId=$itemId')";

					}else{
					
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=element&itemId=$itemId')";
					
					}
				}
	
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkElementData['privacy'])){
					
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=element&itemId=$itemId');";
				
					}else{
					
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=element&itemId=$itemId');";
					}
				
				}
			
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $fav;
				$options[] = $protect;
				$options[] = $undeletable;
			
				break;
		   	case "file":
		    	$checkFileSql = mysql_query("SELECT privacy, owner FROM files WHERE id='$itemId'");
		       	$checkFileData = mysql_fetch_array($checkFileSql);
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('$info1', '$itemId', '$title');";
			  
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=file&item=$itemId";
					$fav['target'] = 'submitter';
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "javascript: popper('doit.php?action=addFileToPlaylist&file=$itemId');";
			  	}
			  
		       	if(authorize($checkFileData['privacy'], "edit", $checkFileData['owner'])){
		       		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=file&itemId=$itemId')";
		         
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=file&itemId=$itemId";
					$delete['target'] = 'submitter';
		       	}
			 
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkFileData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=file&itemId=$itemId')";
	
					}else{
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=file&itemId=$itemId')";
						
					}
				}
		
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkFileData['privacy'])){
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=file&itemId=$itemId');";
					
					}else{
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=file&itemId=$itemId');";
					
					}
					
				}
			  	
			
			  	$report['title'] = 'Report';
			    $report['href'] = '#';
			  	$report['onclick'] = "javascript: popper('doit.php?action=reportFile&fileId=$itemId')";
			  
			  	$download['title'] = 'download';
			  	$download['href'] = "./out/download/?fileId=$itemId";
			  	$download['target'] = 'submitter';
			
				$options[] = $open;
				$options[] = $privacy;
				$options[] = $fav;
				$options[] = $playlist;
				$options[] = $download;
				$options[] = $delete;
				$options[] = $protect;
				$options[] = $undeletable;
				$options[] = $report;
			
		   		break;
		   	case "image":
		    	$checkFileSql = mysql_query("SELECT privacy, owner FROM files WHERE id='$itemId'");
		       	$checkFileData = mysql_fetch_array($checkFileSql);
		       
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('image', '$itemId', '$title');";
			  
			  
			  	$download['title'] = 'Download';
			  	$download['href'] = "./out/download/?fileId=$itemId";
			  	$download['target'] = 'submitter';
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=file&item=$itemId";
					$fav['target'] = 'submitter';
				
					$background['title'] = 'Set as Background';
					$background['href'] = "doit.php?action=changeBackgroundImage&type=file&id=$itemId";
					$background['target'] = 'submitter';
			  	}
				
			  	if(authorize($checkFileData['privacy'], "edit", $checkFileData['owner'])){
			  	 	$delete['title'] = 'Delete';
				 	$delete['href'] = "doit.php?action=deleteItem&type=file&itemId=$itemId";
				 	$delete['target'] = 'submitter';
			  	}
				$options[] = $open;
				$options[] = $download;
				$options[] = $fav;
				$options[] = $background;
				$options[] = $delete;
		   		break;
		   	case "link":
		    	$checkLinkSql = mysql_query("SELECT privacy, author FROM links WHERE id='$itemId'");
	           	$checkLinkData = mysql_fetch_array($checkLinkSql);
	           
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('$info1', '$itemId', '$title');";
			  
			  	if(proofLogin()){
			  	
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "doit.php?action=addFav&type=link&item=$itemId";
					$fav['target'] = 'submitter';
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "popper('doit.php?action=addFileToPlaylist&link=$itemId');";
			  	}
			  
		      	if(authorize($checkLinkData['privacy'], "edit", $checkLinkData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=link&itemId=$itemId')";
		         
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=link&itemId=$itemId')";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "doit.php?action=deleteItem&type=link&itemId=$itemId";
					$delete['target'] = 'submitter';
		      	}
			 
			  
			  
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!isProtected($checkLinkData['privacy'])){
						$protect['title'] = 'Protect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=protectFileSystemItems&type=link&itemId=$itemId')";
	
					}else{
						
						$protect['title'] = 'Unprotect';
						$protect['href'] = '#';
						$protect['onclick'] = "javascript: popper('doit.php?action=removeProtectionFromFileSystemItems&type=link&itemId=$itemId')";
						
					}
				}
		
				//check if person has rights to make files undeletable
				if(hasRight("undeletableFilesystemItems")){
					if(!isUndeletable($checkLinkData['privacy'])){
						
						$undeletable['title'] = 'Make Undeletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemUndeletable&type=link&itemId=$itemId');";
					
					}else{
						
						$undeletable['title'] = 'Make Deletable';
						$undeletable['href'] = '#';
						$undeletable['onclick'] = "javascript: popper('doit.php?action=makeFileSystemItemDeletable&type=link&itemId=$itemId');";
					
					}
					
				}
			  
			  	
				$options[] = $open;
				$options[] = $fav;
				$options[] = $playlist;
				$options[] = $privacy;
				$options[] = $edit;
				$options[] = $delete;
				$options[] = $protect;
				$options[] = $undeletable;
			  
		   		break;
			
		}
		
			  
			  return $options;
	}
	public function showRightClick(){
		
      	 $type = $this->type;
      	 $itemId = $this->itemId;
		
		$options = $this->getOptions();
		
		if(count($options) > 0){
			$list = '';
			foreach($options AS $option){
				if(!empty($option['title'])){
					
						$onclick = '';
				if(!empty($option['href'])){
					$href = 'href="'.$option['href'].'"';
					
				}
				if(!empty($option['onclick'])){
					if($href == 'href="#"'){
						$onclick = 'onclick="'.$option['onclick'].'"';
					}
				}
				if(!empty($option['target'])){
					if($href != 'href="#"')
						$target = 'target="'.$option['target'].'"';
					
				}
				$list .= "<li><a $href $onclick $target>".$option['title'].'</a></li>';  
					
				}
			}
		}
	  
		  $output = "<span id=\"rightClick$type$itemId\" class=\"rightclick\" style=\"display: none;\">";
		  	$output .= "<ul>";
	        	$output .= $list;
		  	$output .= "</ul>";
		  $output .= "</span>";
		  
		  return $output;
	}
	public function showItemSettings(){
		$options = $this->getOptions();
		if(count($options) > 0){
			$list = '';
			foreach($options AS $option){
				if(!empty($option['title'])){
					
						$onclick = '';
				if(!empty($option['href'])){
					$href = 'href="'.$option['href'].'"';
					
				}
				if(!empty($option['onclick'])){
					if($href == 'href="#"'){
						$onclick = 'onclick="'.$option['onclick'].'"';
					}
				}
				if(!empty($option['target'])){
					if($href != 'href="#"')
						$target = 'target="'.$option['target'].'"';
					
				}
				$list .= "<li><a $href $onclick $target>".$option['title'].'a</a></li>';  
					
				}
			}
		}
		
				if(!empty($list)){
					
			        $return = "
			        <a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"btn btn-mini\"><i class=\"icon-cog\"></i></a>
			        <div class=\"itemSettingsWindow\">
			            <ul>
			                $list
			            </ul>
			        </div>";
				}
				if(!empty($return)){
					return $return;
				}
	}
}