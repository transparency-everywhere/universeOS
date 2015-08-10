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
		$title = $this->title;
		$info1 = $this->info1;
				//init vars
	        $open[] = '';
	        $fav[] = '';
	        $privacy[] = '';
	        $playlist[] = '';
                $edit[] = '';
                $delete[] = '';
                $protect[] = '';
                $undeletable[] = '';
                //$options = [];
                $db = new db();
		switch($this->type){
			case 'feed':
                            $feedData = $db->select('feed', array('id', $itemId), array('author', 'privacy'));
                            if(authorize('p', "edit", $feedData['author'])){
                                $privacy['title'] = 'Privacy';
				$privacy['href'] = '#';
				$privacy['onclick'] = "javascript: popper('doit.php?action=changePrivacy&type=feed&itemId=$itemId')";
					

				$delete['title'] = 'Delete';
				$privacy['onclick'] = "privacy.showUpdatePrivacyForm('feed', $itemId);";
				$delete['target'] = 'submitter'; 
                            }
				
				$options[] = $privacy;
				$options[] = $delete;
 				break;
			case 'comment':
                            $commentData = $db->select('comments', array('id', $itemId), array('author', 'type', 'typeid', 'privacy'));
	            
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
                                $dbClass = new db();
                                $checkInternLinkData = $dbClass->select('internLinks', array('id', $itemId));
            
                if($checkInternLinkData['type'] == "folder"){
                    
                    $shortCutItemData = $dbClass->select('folders', array('id', $checkInternLinkData['typeId']), array('name', 'privacy', 'creator'));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "element"){
                    $shortCutItemData = $dbClass->select('elements', array('id', $checkInternLinkData['typeId']), array('title', 'privacy', 'creator'));
                    $user = $shortCutItemData['creator'];
                }else if($checkInternLinkData['type'] == "file"){
                    $shortCutItemData = $dbClass->select('files', array('id', $checkInternLinkData['typeId']), array('title', 'privacy', 'type', 'owner'));
                    $user = $shortCutItemData['owner'];
                }else if($checkInternLinkData['type'] == "link"){
                    $shortCutItemData = $dbClass->select('files', array('id', $checkInternLinkData['typeId']), array('title', 'link', 'privacy', 'type', 'author'));
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
                                
                                $checkFolderData = $db->select('folders', array('id', $itemId),array('privacy', 'creator'));
				
				$open['title'] = 'Open';
				$open['href'] = '#';
				$open['onclick'] = "openFolder('$itemId')";
				
				if(proofLogin()){
					
					$fav['title'] = 'Add to Fav';
					$fav['href'] = "#";
					$fav['onclick'] = "fav.add('folder', '$itemId');";
					
				}
				
				if(authorize($checkFolderData['privacy'], "edit", $checkFolderData['creator'])){
				
					$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "privacy.showUpdatePrivacyForm('folder', $itemId);";
					
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "folders.showUpdateFolderForm($itemId);";
					
					$delete['title'] = 'Delete';
					$delete['href'] = "javascript: folders.verifyRemoval('$itemId');";
					$delete['target'] = 'submitter';
					
				}
				
				
                                $privacyClass = new privacy($checkFolderData['privacy']);
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!$privacyClass->isProtected()){
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
					if(!$privacyClass->isUndeletable()){
						
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
                            
                            $checkElementData = $db->select('elements', array('id', $itemId), array('privacy', 'author'));
			 
			 
			 	$open['title'] = 'Open';
			 	$open['href'] = '#';
			 	$open['onclick'] = "openElement('$itemId', '$title');";
			 
		      	if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "privacy.showUpdatePrivacyForm('element', $itemId);";
		         
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "elements.showUpdateElementForm($itemId)";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "javascript: elements.verifyRemoval('$itemId');";
		      	}
			 
			 	if(proofLogin()){
			 		$fav['title'] = 'Add to Fav';
					$fav['href'] = "#";
					$fav['onclick'] = "fav.add('element', '$itemId');";
			 	}
			 
                                $privacyClass = new privacy($checkElementData['privacy']);
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!$privacyClass->isProtected()){
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
					if(!$privacyClass->isUndeletable()){
					
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
                            
                                $checkFileData =  $db->select('files', array('id', $itemId), array('privacy', 'owner'));
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "reader.openFile($itemId), '$title');";
			  
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "#";
					$fav['onclick'] = "fav.add('file', '$itemId');";
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "javascript: popper('doit.php?action=addFileToPlaylist&file=$itemId');";
			  	}
			  
		       	if(authorize($checkFileData['privacy'], "edit", $checkFileData['owner'])){
		       		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "privacy.showUpdatePrivacyForm('file', $itemId);";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "#";
					$delete['target'] = '';
					$delete['onclick'] = 'filesystem.verifyFileRemoval('.$itemId.')';
		       	}
			 
                                $privacyClass = new privacy($checkFileData['privacy']);
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!$privacyClass->isProtected()){
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
					if(!$privacyClass->isUndeletable()){
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
			  	$report['onclick'] = "javascript:filesystem.showReportFileForm($itemId);";
			  
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
		       	$checkFileData = $db->select('files', array('id', $itemId), array('privacy', 'owner'));
		       
			  
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('image', '$itemId', '$title');";
			  
			  
			  	$download['title'] = 'Download';
			  	$download['href'] = "./out/download/?fileId=$itemId";
			  	$download['target'] = 'submitter';
			  	if(proofLogin()){
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "#";
					$fav['onclick'] = "fav.add('file', '$itemId');";
				
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
	           	$checkLinkData = $db->select('links', array('id', $itemId), array('privacy', 'author'));
	           
			  	$open['title'] = 'Open';
			  	$open['href'] = '#';
			  	$open['onclick'] = "openFile('$info1', '$itemId', '$title');";
			  
			  	if(proofLogin()){
			  	
			  		$fav['title'] = 'Add to Fav';
					$fav['href'] = "#";
					$fav['onclick'] = "fav.add('link', '$itemId');";
				
					$playlist['title'] = 'Add to Playlist';
					$playlist['href'] = '#';
					$playlist['onclick'] = "popper('doit.php?action=addFileToPlaylist&link=$itemId');";
			  	}
			  
		      	if(authorize($checkLinkData['privacy'], "edit", $checkLinkData['author'])){
		      		$privacy['title'] = 'Privacy';
					$privacy['href'] = '#';
					$privacy['onclick'] = "privacy.showUpdatePrivacyForm('link', $itemId);";
                                        
					$edit['title'] = 'Edit';
					$edit['href'] = '#';
					$edit['onclick'] = "popper('doit.php?action=editItem&type=link&itemId=$itemId')";
				
					$delete['title'] = 'Delete';
					$delete['href'] = "javascript:links.verifyRemoval('$itemId');";
					$delete['target'] = 'submitter';
		      	}
			 
			  
			  
                                $privacyClass = new privacy($checkLinkData['privacy']);
				//check if person has rights to protect filesystem items of changes
				if(hasRight("protectFileSystemItems")){
					if(!$privacyClass->isProtected()){
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
					if(!$privacyClass->isUndeletable()){
						
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
                        case "user":
                                $buddyListClass = new buddylist();
                                if(!$buddyListClass->buddy($itemId)){
                                            $delete['title'] = 'Add Buddy';
                                            $delete['href'] = "javascript:buddylist.addBuddy('$itemId');";
                                            $options[] = $delete;
                                }
                            break;
                        case "custom": 
                            
                            $options[] = $info1;
                                
                            
                            break;
			
		}
		
			  
			  return $options;
	}
	public function d_showRightClick(){
		
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
                                if(!isset($target))
                                    $target = '';
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
                                if(!isset($target))
                                    $target = '';
				$list .= "<li><a $href $onclick $target>".$option['title'].'</a></li>';  
					
				}
			}
		}
		
				if(true){
					
			        $return = "
			        <a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"btn btn-mini itemSettingsButton\"><i class=\"icon icon-gear\"></i></a>
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
