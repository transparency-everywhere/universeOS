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
class privacy {
    public $privacy;
    
    //put your code here
    function __construct($privacy=NULL){
            $this->privacy = $privacy;
    }
    
    
	  /**
	  * finds out whether or not a privacies value is "protected"
	  *
	  * @param string $value      contains privacy query.
	  *
	  * @return bool 
	  */
	function isProtected(){
                $value = $this->privacy;
                $privacies = explode(";", $value);
		if(end($privacies) == "PROTECTED"){
			return true;
		}else{
			return false;
		}
	}
	
	  /**
	  * finds out whether or not a privacies value is "undeletable"
	  *
	  * @return bool 
	  */
	function isUndeletable(){
                $value = $this->privacy;
                $privacies = explode(";", $value);
		if(end($privacies) == "UNDELETABLE"){
			return true;
		}else{
			return false;
		}
	}
	
	
	  /**
	  * shows the privacysettings box 
	  *
	  * @return
	  */
    function showPrivacySettings($editable=true){
    	
                $value = $this->privacy;
                
		$oldValue = $value;
		if(end(explode(";", $oldValue)) == "PROTECTED"){
			
			$protected = true;
			$value = str_replace(";PROTECTED", "", $value);
        	
                }
		if(end(explode(";", $oldValue)) == "UNDELETABLE"){
        	
			
			$undeletable = true;
			$value = str_replace(";UNDELETABLE", "", $value);
        	
        }
		
    	if(empty($value)){
    		$value = "p";
    	}
		
        if($value == "p"){
            //check checkbox on public
            $checked['privacyPublic'] = 'checked="checked"';
        }else if($value == "h"){
            //check checkbox on hidden
            $checked['privacyHidden'] = 'checked="checked"';
        }else{
			$showCustom = "notHidden";
            //handle other cases
            //   f;4;3;2//f;2;
            $custom = explode("//", $value);
            
            $customShow = $custom[1];
            $customShow = explode(";", $customShow);
            
            if(in_array("f", $customShow)){
                //check checkbox on show friends
                $checked['privacyCustomShowF'] = 'checked="checked"';
            }
            
            
            
            $customEdit = $custom[0];
            $customEdit = explode(";", $customEdit);
            
            
            if(in_array("f", $customEdit)){
                //check checkbox on allow friends to edit
                $checked['privacyCustomEditF'] = 'checked="checked"';
            }
            if(in_array("h", $customEdit)){
                //only authour is allow to edit
                $checked['privacyCustomEditH'] = 'checked="checked"';
            }
            
            
        }
		
		if(true){
			if($protected OR !$editable){
				$disabled = 'disabled="disabled"'; //added to checkboxes
			}
        			if($protected){
        				echo"<li style=\"font-size:16pt;\">Protected</li>";
        			}else if($undeletable){
        				//echo"<li style=\"font-size:16pt;\">Undeletable</li>";
        			}
			if(true){
        ?>
        	<div class="privacySettings">
        		<header>Privacy Settings</header>
        		<ul>
        			<li>
        				<h2><input type="checkbox" name="privacyPublic" value="true" class="privacyPublicTrigger uncheckCustom uncheckHidden" <?=$checked[privacyPublic];?> <?=$disabled;?>>Public</h2>
        				Every user is allowed to see and edit.
        			</li>
        			<li>
        				<h2><input type="checkbox" class="privacyHiddenTrigger uncheckPublic uncheckCustom" name="privacyHidden" value="true" <?=$checked[privacyHidden];?> <?=$disabled;?>>Only me</h2>
        				You are the only one who is allowed to see and edit.
        			</li>
        			<li>
        				<h2><input type="checkbox" class="privacyBuddyTrigger privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked['privacyCustomShowF'];?> <?=$checked['privacyCustomEditF'];?> <?=$disabled;?>>Friends</h2>
        				Friends cann see or edit.
        			</li>
        			<li class="<?=$showCustom;?> sub privacyShowBuddy">
        				<div><input type="checkbox" name="privacyCustomSee[]" value="f" data-privacytype="see" class="privacyCustomTrigger uncheckPublic uncheckHidden privacyBuddyTrigger privacyBuddyTrigger_see" <?=$checked['privacyCustomShowF'];?> <?=$disabled;?>>See</div>
        				<div><input type="checkbox" name="privacyCustomEdit[]" value="f" data-privacytype="edit" class="uncheckPublic privacyCustomTrigger uncheckHidden privacyBuddyTrigger privacyBuddyTrigger_edit" <?=$checked['privacyCustomEditF'];?> <?=$disabled;?>>Edit</div>
        			</li>
        			<li>
        				<h2><input type="checkbox" class="uncheckPublic privacyGroupTrigger privacyCustomTrigger uncheckHidden" <?=$disabled;?>>Groups</h2>
        				Particular Groups can see and edit.
        			</li>
        			<li class="<?=$showCustom;?> sub privacyShowGroups <?=$showBuddy;?>">
        				<ul class="groupList">
        					                           <?
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".getUser()."' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData['title'];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            if(in_array($groupData['id'], $customEdit)){
                                                                $checked['editGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['editGroup'] = '';
                                                            }
                                                            ?>
                                                                <li>
                                                                	<div><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="reader.tabs.addTab('<?=$title10;?>', '',gui.loadPage('group.php?id=<?=$groupData['id'];?>'));return false"><?=$title15;?></a></div>
                                                                	<div>
                                                                		<input type="checkbox" name="privacyCustomSee[]" value="<?=$groupData['id'];?>" data-groupid="<?=$groupData['id'];?>" data-privacytype="see" class="privacyGroupTrigger privacyCustomTrigger uncheckPublic privacySee uncheckHidden privacyGroupTrigger_<?=$groupData['id'];?>_see" <?=$checked['editGroup'];?> <?=$disabled;?>>
																		show
                                                                	</div>      
                                                                	<div>
                                                                		<input type="checkbox" name="privacyCustomEdit[]" value="<?=$groupData['id'];?>" data-groupid="<?=$groupData['id'];?>" data-privacytype="edit"  class="privacyGroupTrigger privacyCustomTrigger uncheckPublic checkPrev uncheckHidden privacyGroupTrigger_<?=$groupData['id'];?>_edit" <?=$checked['editGroup'];?> <?=$disabled;?>>
																		edit
                                                                	</div>
                                                                </li>
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
        				</ul>
        			</li>
        			<li style="height:1px;"></li>
        			<?php
        			}
        			if(1 == 2){
        			if($protected){
        				echo"<li style=\"font-size:16pt;\">Protected</li>";
        			}
        			?>
        			<li><input type="checkbox" name="privacyPublic" value="true" class="privacyPublicTrigger uncheckCustom uncheckHidden" <?=$checked[privacyPublic];?> <?=$disabled;?>> Public</li>
					<li>Custom:</li>
					<li style="padding-left:5px;">See</li>
					<li style="padding-left: 10px;"><input type="checkbox" name="privacyCustomSee[]" value="f" class="privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked[privacyCustomShowF];?> <?=$disabled;?>> All your Friends</li>

                                                        <?                            
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".$_SESSION['userid']."' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData[title];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            
                                                            if(in_array($groupData['id'], $customShow)){
                                                                $checked['showGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['showGroup'] = '';
                                                            }
                                                            ?>
                                                            <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomSee[]" value="<?=$groupData['id'];?>" class="privacyCustomTrigger uncheckPublic uncheckHidden" <?=$checked[showGroup];?> <?=$disabled;?>><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="reader.tabs.addTab('<?=$title10;?>', '',gui.loadPage('group.php?id=<?=$groupData['id'];?>'));return false"><?=$title15;?></a></li>
       
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
                     <li style="padding-left:5px;">Edit</li>
                     <li style="padding-left:5px;"><input type="checkbox" name="privacyCustomEdit[]" value="f" class="privacyCustomTrigger uncheckPublic uncheckOnlyMe uncheckHidden"<?=$checked[privacyCustomEditF];?> <?=$disabled;?>>All your Friends</li>

                                                        <?                            
                                                        $attSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".getUser()."' AND validated='1'");
                                                        while($attData = mysql_fetch_array($attSql)){
                                                            $i++;
                                                            $groupSql = mysql_query("SELECT id, title FROM groups WHERE id='$attData[group]'");
                                                            $groupData = mysql_fetch_array($groupSql);
                                                            $title = $groupData['title'];
                                                            $title10 = substr("$title", 0, 10);
                                                            $title15 = substr("$title", 0, 25);
                                                            if($i%2 == 0){
                                                                $color="000000";
                                                            }else{
                                                                $color="383838";
                                                            }
                                                            if(in_array($groupData['id'], $customEdit)){
                                                                $checked['editGroup'] = 'checked="checked"';
                                                            }else{
                                                                $checked['editGroup'] = '';
                                                            }
                                                            ?>
                                                                <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomEdit[]" value="<?=$groupData['id'];?>" class="privacyCustomTrigger uncheckOnlyMe uncheckPublic uncheckHidden" <?=$checked[editGroup];?> <?=$disabled;?>><img src="./gfx/icons/group.png" height="15">&nbsp;<a href="#" onclick="reader.tabs.addTab('<?=$title10;?>', '',gui.loadPage('group.php?id=<?=$groupData['id'];?>')); return false"><?=$title15;?></a></li>
                                                        <?}
                                                        if($i < 1){
                                                            echo'<li style="padding-left:10px;">Your are in no group</li>';
                                                        }?>
                                                        <li style="padding-left:10px;"><input type="checkbox" name="privacyCustomEdit[]" value="h" class="privacyCustomTrigger privacyOnlyMeTrigger uncheckPublic uncheckHidden" <?=$checked[privacyCustomEditH];?> <?=$disabled;?>>Only Me</li>
	
                                        <li><input type="checkbox" class="privacyHiddenTrigger uncheckPublic uncheckCustom" name="privacyHidden" value="true" <?=$checked['privacyHidden'];?> <?=$disabled;?>>Hidden:</li>
					<?php }?>
        		</ul>
        	</div>
                                <script>
									privacy.init();
                                </script>
       <?php
       }
    }
    
    
}


	  /**
	  * finds out if current user is authorized to see or edit
	  * item with $privacy and $author
	  *
	  * @param string $privacy      contains privacy query.
	  *
	  * @param string $type      	see//edit
	  *
	  * @param int $author      	author of the item
	  *
	  * @return bool 
	  */
function authorize($privacy, $type, $author=NULL){
        if(empty($privacy))
            $privacy = 'p';
        $privacies = explode(';', $privacy);
        $endExplode = end($privacies);
	if($endExplode == "UNDELETABLE"){
		$undeletable = true;
		$privacy = str_replace(";UNDELETABLE", "", $privacy);
	}
        if($endExplode == "PROTECTED"){
        	
             $show = true;
             if(hasRight("editProtectedFilesystemItem")){
             	$edit = true;
				 $delete = true;
			 }else{
			 	$edit = false;
				$delete = false;
			 }
        	
        }
        else{
             $show = false;
             $edit = false;
			 $delete = false;
        
	        if($privacy == 'p'){
	            
	            
	             $show = true;
	             if(proofLogin()){
	             	$edit = true;
				 	$delete = true;
				 }
	            
	        }
                else if($privacy == 'h'){
	            
	            if($author == $_SESSION['userid'] && proofLogin()){
	            
	                $show = true;
	                $edit = true;
					$delete = true;
	                
	            }else{
	                
	                $show = false;
	                $edit = false;
					$delete = true;
	                
	            }
	            
	        }
                else{
	            
	
	
	                $custom = explode("//", $privacy);
	                $customShow = $custom[1];
	                $customShow = explode(";", $customShow);
	                $customEdit = $custom[0];
					
					if($customEdit == "h" && $author == getUser()){
	                	$edit = true;
					}
	                $customEdit = explode(";", $customEdit);
	                
	                //check if friends are allowed to see or edit this
	                if((in_array("f", $customShow) || in_array("f", $customEdit)) && proofLogin()){
	                    if($author == getUser()){
	                            //if friends are allowed to see => show = true
	                            if(in_array("f", $customShow)){
	                                $show = true;
	                            }
	                            //if friends are allowed to edit => show = true
	                            if(in_array("f", $customEdit)){
	                                $edit = true;
	                                
	                            }
	                    	
	                    }
	                    //get friends from SQL unefizient
	                    $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$author' && request='0'");
	                    while($buddylistData = mysql_fetch_array($buddylistSql)) {
	                        
	                        //check if user is buddy of $author
	                        if($buddylistData['buddy'] == getUser() && proofLogin()){
	                            //if friends are allowed to see => show = true
	                            if(in_array("f", $customShow)){
	                                $show = true;
	                            }
	                            //if friends are allowed to edit => show = true
	                            if(in_array("f", $customEdit)){
	                                $edit = true;
	                                
	                            }
	                        }
	                    }
	                }
	                
	                
	                
	                
	                if(proofLogin()){
	                //check if user is in group which is allowed to see this item
		                $groupAtSql = mysql_query("SELECT * FROM groupAttachments WHERE item='user' AND itemId='".getUser()."' and validated='1'");
		                while($groupAtData = mysql_fetch_array($groupAtSql)){
		
		                    if(in_array($groupAtData['group'], $customShow) && proofLogin()){
		                        $show = true;  
		                    }
		                    if(in_array($groupAtData['group'], $customEdit) && proofLogin()){
		                        $edit = true;  
		                    }
		                }
					}
	                
	                
	                
	            
	            
	            
	        }
        }
        
        if($type == 'show'){
            return $show;
        }else if($type == 'edit'){
            return $edit;
        }else if($type == 'delete'){
        	if($undeletable){
        		if(hasRight("editUndeletableFilesystemItems")){
        			return true;
        		}else{
        			return false;
        		}
        	}else{
            	return $delete;
        	}
        }
        
    }
	
    
function exploitPrivacy($public, $hidden, $customEdit, $customShow){
    	if(!empty($public) OR !empty($hidden) OR !empty($customEdit) OR !empty($customShow)){
	        if($public == "true"){
	            $privacy = "p";
	        }
	        else if($hidden == "true"){
	            $privacy = "h";
	        }else{
	            
	            $customEdit = implode(";", $customEdit);
	            $customShow = implode(";", $customShow);
	            if(empty($customShow)){
	            	$customShow = "h";
	            }
	            if(empty($customEdit)){
	            	$customEdit = "h";
	            }
	            if(empty($customEdit) OR empty($customShow)){
	            	$privacy = "p";
	           	}else{
	            $privacy = "$customEdit//$customShow";
				}
	        }
	        
	        return $privacy;
		}
    }
    
    