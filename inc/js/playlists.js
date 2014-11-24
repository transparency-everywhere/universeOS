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

var playlists = new function(){
    
    this.getData = function(playlist_id){
        
        var result="";
	$.ajax({
            url:"api/playlists/select/",
            async: false,  
            type: "POST",
            data: {playlist_id : playlist_id},
            success:function(data) {
               result = JSON.parse(data);
            }
	});
	return result;
    };
    this.getTitle = function(playlist_id){
        var result="";
	$.ajax({
            url:"api/playlists/idToTitle/",
            async: false,  
            type: "POST",
            data: {playlist_id : playlist_id},
            success:function(data) {
               result = data;
            }
	});
	return result;
    };
    this.create = function(title, privacy, callback){
                var result="";
                $.ajax({
                    url:"api/playlists/create/",
                    async: false,  
                    type: "POST",
                    data: $.param({title: title})+'&'+privacy,
                    success:function(data) {
                       result = data;
                       if(typeof callback === 'function'){
                           callback(); //execute callback if var callback is function
                       }
                    }
                });
                return result;  		
            };
    this.showCreationForm = function(){
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
        modalOptions['buttonTitle'] = 'Create Playlist';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The playlist has been added');
                $('.blueModal').remove();
                //filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            playlists.create($('#createPlaylistFormContainer #title').val(), $('#createPlaylistFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Create Playlist', '<div id="createPlaylistFormContainer"></div>', modalOptions);
        gui.createForm('#createPlaylistFormContainer',fieldArray, options);
			  		
    };
    
    this.update = function(playlist_id, title, privacy, callback){
        
        var result="";
	$.ajax({
            url:"api/playlists/update/",
            async: false,  
            type: "POST",
            data: $.param({playlist_id : playlist_id, title: title})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    
    this.showUpdatePlaylistForm = function(playlist_id){
        var formModal = new gui.modal();
        var playlistData = playlists.getData(playlist_id);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['value'] = playlistData['title'];
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Privacy';
        field1['inputName'] = 'privacy';
        field1['type'] = 'html';
        field1['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Playlist';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The playlist has been updated');
                $('.blueModal').remove();
            };
            console.log($('#createElementFormContainer #privacyField :input').serialize());
            playlists.update(playlist_id, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', playlistData['privacy'], true);
        formModal.init('Update Playlist', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
};