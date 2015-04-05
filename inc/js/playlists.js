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
    this.updateGUI = function(playlist_id){
        dashBoard.updateDashBox('playlist');
    };
    
    this.activePlaylist = {};
    
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
    
    
    this.create = function(element, title, privacy, callback){
                var result="";
                $.ajax({
                    url:"api/playlists/create/",
                    async: false,  
                    type: "POST",
                    data: $.param({element: element, title: title})+'&'+privacy,
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
        field0['required'] = true;
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Element';
        field1['inputName'] = 'element';
        field1['type'] = 'html';
        field1['value'] = "<div id=\'createPlaylistFileBrowserFrame\'></div>";
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = '';
        field2['inputName'] = 'privacy';
        field2['type'] = 'privacy';
        fieldArray[2] = field2;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Playlist';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('', 'The playlist has been added');
                $('.blueModal').remove();
                
                
                updateDashbox('playlist');
                $('#favTab_playList').load('doit.php?action=showUserPlaylists');
            };
            
            playlists.create($('#createPlaylistFileBrowserFrame .choosenTypeId').val(), $('#createPlaylistFormContainer #title').val(), $('#createPlaylistFormContainer #privacyField :input').serialize(),callback);
        };
        
        formModal.init('Create Playlist', '<div id="createPlaylistFormContainer"></div>', modalOptions);
        gui.createForm('#createPlaylistFormContainer',fieldArray, options);
        
        //load minifilebrowser
        loadMiniFileBrowser($('#createPlaylistFileBrowserFrame'),"1", '', '', true, "element");
        
        
			  		
    };
    //returns all playlists which the users has access to
    //@param type - show or edit
    this.getUserPlaylists = function(type, user_id){
        if(typeof user_id === 'undefined')
        var user_id = User.userid;
        return api.query('api/playlists/getUserPlaylists/', { type : type, user_id: user_id});
    };
            
    
    this.getGroupPlaylists = function(type, group_id){
        return api.query('api/playlists/getGroupPlaylists/', { type : type, group_id:group_id});
    };
    
    this.showInfo = function(playlist_id){
        
        
        var formModal = new gui.modal();
        var playlistData = playlists.getData(playlist_id);
        var playlistFileContent = filesystem.readFile(playlistData['file_id']);
        
        var itemList;
        if(typeof playlistFileContent.items === 'undefined' || playlistFileContent.items.length === 0){
            itemList = 'This playlist is empty';
        }else{
            itemList = '<ul class="dynamicList">';
            $.each(playlistFileContent.items, function(key, value){
                console.log(value);
                var info = item.getInfo(value.item_type, value.item_id);
                itemList += '<li class="playlistItem_'+playlist_id+'_'+value.order_id+'" >';
                    itemList += item.generateInfo(info.image, info.title, '<a href="#" onclick="playlists.removeItemFromPlaylist(\''+playlist_id+'\',\''+value.order_id+'\');"><i class="icon white-minus"></i></a>');
                    itemList += '<span class="icon white-play" onclick="playlists.playPlaylistRow(\''+playlist_id+'\', \''+value.order_id+'\');"></span>';
                itemList += '</li>';
            });
            itemList += '</ul>';
        }
      
        var html = '<div id="showPlaylistInfoContainer">'+itemList+'</div>';
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Play Playlist';
        
        modalOptions['action'] = function(){
            playlists.playPlaylist(playlist_id)
        };
        formModal.init('Playlist '+playlistData['title'], html, modalOptions);
        
        
    };
    
    
//    old
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
//    old
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
        field0['required'] = true;
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['value'] = playlistData['title'];
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = '';
        field1['inputName'] = 'privacy';
        field1['type'] = 'privacy';
        field1['value'] = playlistData['privacy'];
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Playlist';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('', 'The playlist has been updated');
                $('.blueModal').remove();
            };
            console.log($('#createElementFormContainer #privacyField :input').serialize());
            playlists.update(playlist_id, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', playlistData['privacy'], true);
        formModal.init('Update Playlist', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.pushItemForm = function(item_type, item_id){
        
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        
        var info = item.getInfo(item_type, item_id);
        var field1 = [];
        field1['caption'] = 'Item';
        field1['inputName'] = 'item';
        field1['type'] = 'html';
        field1['value'] = item.generateInfo(info.image, info.title, '');
        fieldArray[0] = field1;
        
        var userPlaylists = playlists.getUserPlaylists('edit');
        
        var field2 = [];
        field2['caption'] = 'Playlist';
        field2['inputName'] = 'playlist';
        field2['type'] = 'dropdown';
        field2['values'] = userPlaylists['ids'];
        field2['captions'] =  userPlaylists['titles'];
        fieldArray[1] = field2;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Add Item to Playlist';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('', 'The playlist has been updated');
                $('.blueModal').remove();
            };
            playlists.pushItem($('#pushPlaylistFormContainer #playlist').val(), item_type, item_id, callback);
        };
        formModal.init('Add Item To Playlist', '<div id="pushPlaylistFormContainer"></div>', modalOptions);
        gui.createForm('#pushPlaylistFormContainer',fieldArray, options);
    };
    
    this.pushItemToPlaylistForm = function(item_type, item_id){
        
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Add';
        options['noButtons'] = true;
        
        
        var field0 = [];
        field0['caption'] = 'Item';
        field0['inputName'] = '';
        field0['value'] = item.generateItemPreview(item_type, item_id);
        field0['type'] = 'html';
        fieldArray[0] = field0;
        
        
        var user_playlists = playlists.getUserPlaylists('edit');
        var field1 = [];
        field1['caption'] = 'Playlist';
        field1['inputName'] = 'playlist';
        field1['values'] = user_playlists.ids;
        field1['captions'] = user_playlists.titles;
        field1['type'] = 'dropdown';
        fieldArray[1] = field1;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Add to Playlist';
        
        modalOptions['action'] = function(){
            playlists.pushItem($('#pushItemToPlaylistFormContainer #playlist').val(), item_type, item_id, function(){
                gui.alert('The item has been added to the playlist');
            });
        };
        
        formModal.init('Add item to Playlist', '<div id="pushItemToPlaylistFormContainer"></div>', modalOptions);
        gui.createForm('#pushItemToPlaylistFormContainer',fieldArray, options);
        
        //load privacy
        privacy.load('#privacyField', '', true);
        
    };
    
    this.removeItemFromPlaylist = function(playlist_id, order_id){
        
        return api.query('api/playlists/removeItem/', { playlist : playlist_id, order_id: order_id}, function(){
            gui.alert('The item has been removed from the playlist');
            $('.playlistItem_'+playlist_id+'_'+order_id).remove();
        });
        
    };
    
    
    this.pushItem = function(playlist, item_type, item_id, callback){
        
        return api.query('api/playlists/pushItem/', { playlist : playlist, item_type: item_type, item_id: item_id}, callback);
        
    };
    
    
    this.playItem = function(options){
        var type = options['item_type'];
        var item_id = options['item_id'];
        var order_id = options['order_id'];
        var playlist_id = options['playlist_id'];
        
        var onStop = function(){playlists.playPlaylistRow(playlist_id, order_id+1);};
        
        if(this.activePlaylist.playlist_id !== playlist_id){
            this.openPlaylistTab(playlist_id);
        }
        
        switch(type){
            case 'youtube':
                player.loadYoutubeVideo(playlists.getPlaylistTabObject(playlist_id), item_id,onStop);
                break;
        }
    };
    
    this.getPlaylistTabObject = function(playlist_id){
        return $('#player .tab_2');
    };
    
    this.updateActiveItemObject = function(playlist_id, tab, order_id){
        
        if(typeof playlist_id === undefined){
            playlist_id = this.activePlaylist.playlist_id;
        }
        if(typeof tab === undefined){
            tab = this.activePlaylist.tab;
        }
        if(typeof tab === undefined){
            order_id = this.activePlaylist.order_id;
        }
        this.activePlaylist = {'playlist_id':playlist_id, 'tab':tab, 'order_id':order_id};
        player.updateActiveItemObject(tab, '', true, {'playlist_id':playlist_id,'order_id':order_id});
                   
    };
    
    this.playPlaylistRow = function(playlist_id, order_id){
        
       
        var playlistData = playlists.getData(playlist_id);
        var playlistFileContent = filesystem.readFile(playlistData['file_id']);
        var tempThis = this;
        if(playlistFileContent.items.length === 0){
            //playlist is empty
        }else{
            $.each(playlistFileContent.items, function(key, value){
               if(value['order_id'] == order_id){
                   //push playlist id to value array for playItem function
                   value['playlist_id'] = playlist_id;
                   
                   playlists.playItem(value);
                   
                   //update activePlaylist
                   tempThis.updateActiveItemObject(tempThis.activePlaylist.playlist_id, tempThis.activePlaylist.tab, order_id);
                   
               }
            });
        }
    };
    
    this.openPlaylistTab = function(playlist_id){
        
        player.show();
        var tab_id = player.tabs.addTab('Playlist', 'html', 'content', function(){/*onclose*/});
        this.updateActiveItemObject(playlist_id, tab_id);
    };
    
    this.playPlaylist = function(playlist_id){
        this.openPlaylistTab(playlist_id);
        this.playPlaylistRow(playlist_id, 0);
    };
    
        
};