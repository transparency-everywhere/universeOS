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
        

var folders = new function(){
              	
    this.getData = function(folder_id){
        
        var result="";
	$.ajax({
            url:"api/folders/select/",
            async: false,  
            type: "POST",
            data: {folder_id : folder_id},
            success:function(data) {
               result = JSON.parse(data);
            }
	});
	return result;
    };
    this.update = function(folderId, parent_folder, title, privacy, callback){
        
        var result="";
	$.ajax({
            url:"api/folders/update/",
            async: false,  
            type: "POST",
            data: $.param({folder_id : folderId, title: title})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    
    this.showUpdateFolderForm = function(folder){
        var formModal = new gui.modal();
        var folderData = folders.getData(folder);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['value'] = folderData['name'];
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Privacy';
        field1['inputName'] = 'privacy';
        field1['type'] = 'html';
        field1['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Folder';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The folder has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+folder));
            };
            console.log($('#createElementFormContainer #privacyField :input').serialize());
            folders.update(folder, folderData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', folderData['privacy'], true);
        formModal.init('Update Folder', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.folderIdToFolderTitle = function(folderId){
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=folderIdToFolderTitle",
				      async: false,  
					  type: "POST",
					  data: { folderId : folderId },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   return result;
    };
    this.createFolder = function(parent_folder, name, privacy, callback){
        var result="";
	$.ajax({
            url:"api/folders/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : parent_folder, name: name})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.showCreateFolderForm = function(parent_folder){
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
        
        var field1 = [];
        field1['caption'] = 'Privacy';
        field1['inputName'] = 'privacy';
        field1['type'] = 'html';
        field1['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Folder';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The folder has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            folders.createFolder(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Create Folder', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
};