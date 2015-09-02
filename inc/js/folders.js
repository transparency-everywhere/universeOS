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
              	
    this.open = function(folder_id){
        userHistory.push('folder', folder_id);
        applications.show('filesystem');
        filesystem.openFolder(folder_id);
        return false;
    };
    this.getData = function(folder_id){
        
        if(typeof folder_id === 'object'){
            var requests = [];
            $.each(folder_id,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({folder_id : value});
            });
                return api.query('api/folders/select/', { request: requests});
        }else
            return api.query('api/folders/select/',{request: [{folder_id : folder_id}]})[0];
    };
              	
    this.getPath = function(folder_id){
	return api.query('api/folders/getPath/',{folder_id : folder_id});
    };
              	
    this.getItems = function(folder_id){
        
        var result = [];
    	$.ajax({
                url:"api/folders/getItems/",
                async: false,  
                type: "POST",
                data: {folder_id : folder_id},
                success:function(data) {
                   result = JSON.parse(data);
                }
	});
        if(result === null){
            result = [];
        }
	return result;
    };
    
    this.update = function(folderId, parent_folder, title, privacy, callback){
        
        var result="";
	$.ajax({
            url:"api/folders/update/",
            async: false,  
            type: "POST",
            data: $.param({folder_id : folderId, title: title.replace(/[^a-zA-Z0-9 _-]/g,'')})+'&'+privacy,
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
        field0['required'] = true;
        field0['value'] = folderData['name'];
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = '';
        field1['inputName'] = 'privacy';
        field1['type'] = 'privacy';
        field1['value'] = folderData['privacy'];
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Folder';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The folder has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(folderData['folder']));
            };
            folders.update(folder, folderData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        formModal.init('Update Folder', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.folderIdToFolderTitle = function(folderId){
        if(typeof folderId === 'object' && folderId.length === 0)
            return null;
        var folderData = this.getData(folderId);
        if(typeof folderId === 'object'){
            var results = [];
            $.each(folderData, function(index, value){
                results.push(value['name']);
            });
            return results;
        }
        return folderData['name'];
    };
    this.createFolder = function(parent_folder, name, privacy, callback){
        var result="";
	$.ajax({
            url:"api/folders/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : parent_folder, name: name.replace(/[^a-zA-Z0-9 _-]/g,'')})+'&'+privacy,
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
        field0['required'] = true;
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = '';
        field1['inputName'] = 'privacy';
        field1['type'] = 'privacy';
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Folder';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The folder has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(parent_folder));
            };
            folders.createFolder(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        options['action'] = modalOptions['action'];
        formModal.init('Create Folder', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    
    this.delete = function(folderId, callback){
	return api.query('api/folders/delete/',{folder_id : folderId},callback);
        
    };
    this.verifyRemoval = function(folderId){
        var confirmParameters = {};
        var folderData = folders.getData(folderId);
        confirmParameters['title'] = 'Delete Folder';
        confirmParameters['text'] = 'Are you sure to delete this folder?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            folders.delete(folderId);
            filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(folderData['folder']));
           
            gui.alert('The folder has been deleted');
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
};