//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com
        

var elements = new function(){
    
    this.show = function(element){
        var elementData = this.getData(element);
        var elementAuthorData = this.getAuthorData(elementData['author']);
        var link = "./modules/reader/showfile.php?type=" + elementData['type'];
        var html = filesystem.generateLeftNav();
        if(elementData['type'] === "image"){
            var title;
            var bar;
            if(elementData['title'] === "profile pictures"){
                title = "Userpictures";
                bar = false;
                if($elementData['author'] == getUser()){
                      changeUserPicture = true;
                }
            }else{
                title = elementData['title'];
                bar = true;
            }
            var fileNumbers = this.getFileNumbers(elementData['id']);
            html =+ '<div class="frameRight">';
            html =+ '        <center>';
            html =+ '                <h2>' + title + '</h2>';
            html =+ '                <h3>by <i><a href="#" onclick="showProfile(\'' + elementAuthorData['userid'] + '\')">' + elementAuthorData['username'] + '</a></i></h3>';
            html =+ '        </center>';
            if(fileNumbers > 0){
                html =+ '        <div id="showImages" class="dockMenuElement" style="margin-top: 10px; margin-right: 0px; margin-left: 0px; overflow: scroll; height: 120px; padding-left: 5px;">';
                html =+ '            <table wdth="100%">';
                html =+ '                <tr>';
                html =+ '';
                html =+ '';
                html =+ '';
                html =+ '';
            }
  
  
//  $documentSQL = mysql_query("SELECT id, title, owner, privacy FROM files WHERE folder='".$elementData['id']."'");
//  $fileNumbers = mysql_num_rows($documentSQL);
//  
//  
//    ?>
//<div class="frameRight">
//        <center>
//                <h2><?=$title;?></h2>
//                <h3>by <i><a href="#" onclick="showProfile('<?=$elementAuthorData['userid'];?>')"><?=$elementAuthorData['username'];?></a></i></h3>
//        </center>
//		<? if($fileNumbers > 0){ ?>
//        <div id="showImages" class="dockMenuElement" style="margin-top: 10px; margin-right: 0px; margin-left: 0px; overflow: scroll; height: 120px; padding-left: 5px;">
//
//            <table wdth="100%">
//                <tr>
//        <?php
//        
//        while($documentData = mysql_fetch_array($documentSQL)){
//        $documentFolderSQL = mysql_query("SELECT id, path, privacy FROM folders WHERE id='".$elementData['folder']."'");
//        $documentFolderData = mysql_fetch_array($documentFolderSQL);
//        $folderPath = urldecode($documentFolderData['path']);
//        if($elementData['title'] == "profile pictures"){
//        $folderPath = "/userPictures";
//        $folderPath = "upload$folderPath/thumb/300";
//        }else{
//        $folderPath = "upload$folderPath/thumbs/";
//        }
//        if(authorize($documentData['privacy'], "show", $documentData['owner'])){
//            $fileClass = new file($documentData['id']);
//            ?>
//            <td onclick="openFile('image', '<?=$documentData['id'];?>', '<?=$elementData['title'];?>');" oncontextmenu="showMenu('image<?=$documentData['id'];?>'); return false;"><img src="<?=$fileClass->getFullFilePath();?>" height="100px"></td>   
//                
//        <?php 
//        $contextMenu = new contextMenu("image", $documentData['id'], $elementData['title'] , $documentData['owner']);
//        $contextMenu->showRightClick();
//        }} ?>
//        </tr>
//        </table>
//        </div>
//        <? } ?>
//        <div>
//
//        </div>
//	    <center style="margin-top: 20px; margin-bottom: 20px;">
//	    	<?
//	    	if(proofLogin() && $bar){
//	    	?>
//	        <a href="#" onclick="filesystem.openUploadTab('<?=$_GET[element];?>');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;upload File</a>&nbsp;
//	        <a href="#" onclick="links.showCreateLinkForm('<?=$_GET['element'];?>');" class="btn btn-info"><i class="icon-globe icon-white"></i>&nbsp;add Link</a>
//			<? }
//			
//			if($changeUserPicture){ ?>
//				<a href="#" onclick="settings.show();" class="btn btn-info">&nbsp;change Userpicture&nbsp;</a>
//			<? }
//			?>
//	    </center>
//        <hr>
//            <div>
//                <?
//                $classComments = new comments();
//                $classComments->showComments(element, $elementData['id']);
//                ?>
//            </div>
//<?    
//}

        }else{
            html =+ '    <div id="showElement" class="frameRight">';
            html =+ '        <h2 style="margin-left: 5%; margin-bottom:0px; margin-top:5%;">';
            html =+ '            ' + htmlspecialchars(elementData['title']) + '&nbsp;<i class="icon-info-sign" onclick="$(\'.elementInfo' + elementData['id'] + '\').slideDown();"></i>';
            html =+ '        </h2>';
            html =+ '        <div class="elementInfo' + elementData['id'] + ' hidden">';
            html =+ '    <table width="100%" class="fileBox" cellspacing="0">';
            html =+ '        <tr bgcolor="#FFFFFF" height="35px">';
            html =+ '            <td width="110px">Element-Type:</td>';
            html =+ '            <td>' + elementData['type'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#e5f2ff" height="35px">';
            html =+ '            <td width="110px">Author:</td>';
            html =+ '            <td>' + elementData['creator'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#FFFFFF" height="35px">';
            html =+ '            <td>Title:</td>';
            html =+ '            <td>' + elementData['name'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#e5f2ff" height="35px">';
            html =+ '            <td>Year:</td>';
            html =+ '            <td>' + elementData['year'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#FFFFFF" height="35px">';
            html =+ '            <td>Original Title:</td>';
            html =+ '            <td>' + elementData['originalTitle'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#e5f2ff" height="35px">';
            html =+ '            <td>Language:</td>';
            html =+ '            <td>' + elementData['language'] + '</td>';
            html =+ '        </tr>';
            html =+ '        <tr bgcolor="#FFFFFF" height="35px">';
            html =+ '            <td>License:</td>';
            html =+ '            <td>' + elementData['license'] + '</td>';
            html =+ '        </tr>';
            html =+ '    </table>';
            html =+ '    <div style="display:none; float:left; width:40%; margin-top: 3%; background: #c9c9c9; height: 250px;">';
            html =+ '    </div>';
            html =+ '    </div>';
            html =+ '    <div style=" clear: left;margin-left: 5%;">';
            html =+ '        <a target="_blank" href="http://www.amazon.de/gp/search?ie=UTF8&camp=1638&creative=6742&index=aps&keywords=' + htmlentities(elementData[title]) + '%20' + elementData['creator'] + '&linkCode=ur2&tag=universeos-21">find on amazon</a><img src="http://www.assoc-amazon.de/e/ir?t=universeos-21&l=ur2&o=3" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
            html =+ '    </div>';
            html =+ '    <h3 style="margin-left:5%; margin-bottom:-10px;"><i class="icon-file"></i>&nbsp;Files</h3>';
            html =+ '        <table cellspacing="0" class="filetable" style="width: 90%; margin-left: 5%; border: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9;">';
            html =+ '            <tr class="grayBar" height="35">';
            html =+ '                <td></td>';
            html =+ '                <td>Title</td>';
            html =+ '                <td></td>';
            html =+ '                <td></td>';
            html =+ '                <td></td>';
            html =+ '            </tr>';
            html =+ elements.showFileList(elementData['id']); //muss ich noch machen :(
            html =+ '        </table>';
            html =+ '    <center style="margin-top: 20px; margin-bottom: 20px;">';
            if(proofLogin()){
                html =+ '    	<a class="btn btn-info" href="#" onclick="filesystem.showCreateUFFForm(\'' + element + '\'); " target="submitter"><i class="icon-file icon-white"></i> Create Document</a>';
                html =+ '        <a href="#" onclick="filesystem.openUploadTab(\'' + element + '\');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;Upload File</a>';
                html =+ '        &nbsp;<a href="javascript: links.showCreateLinkForm(\'' + element + '\');" class="btn btn-info"><i class="icon white-link"></i>&nbsp;Add Link</a>';
            }
            html =+ '    </center>';
            html =+ '    <hr>';
            html =+ '    <div>';
            html =+ comments.loadSubComments(elementData['id']); //hier nochmal nachfragen war class_comments -> showComments('element', elementId)
            html =+ '    </div>';
            html =+ '</div>';
        }
    };
    
    this.showFileList = function(element_id){
        //class_elements -> showFileList(eId)
    };
    
    this.getData = function(element_id){
        return api.query('api/elements/select/',{element_id : element_id});
	return result;
    };
    
    this.getAuthorData = function(user_id){
        return api.query('api/elements/getAuthorData/',{user_id : user_id});
	return result;
    };
    
    this.getFileNumbers = function(element_id){
        return api.query('api/elements/getFileNumbers/',{element_id : element_id});
	return result;
    };
    
    this.create = function(folder, title,  type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    
    this.showCreateElementForm = function(parent_folder){
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        fieldArray[0] = field0;
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            elements.create(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Create Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.update = function(element_id, folder, title, type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/update/",
            async: false,  
            type: "POST",
            data: $.param({element_id:element_id,folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    }
    
    this.showUpdateElementForm = function(element){
        var formModal = new gui.modal();
        var elementData = elements.getData(element);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['value'] = elementData['title'];
        fieldArray[0] = field0;
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        field1['preselected'] = elementData['type'];
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            };
            elements.update(element, elementData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', elementData['privacy'], true);
        formModal.init('Update Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.delete = function(elementId, callback){
        var result="";
	$.ajax({
            url:"api/elements/delete/",
            async: false,  
            type: "POST",
            data: {element_id : elementId},
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.verifyRemoval = function(elementId){
        var confirmParameters = {};
        var elementData = elements.getData(elementId);
        confirmParameters['title'] = 'Delete Element';
        confirmParameters['text'] = 'Are you sure to delete this element?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            elements.delete(elementId);
            gui.alert('The element has been deleted');
            filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
    
    this.elementIdToElementTitle = function(elementId){
            return elements.getData(elementId)['title'];
    };
              	
};
