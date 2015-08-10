
//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You
//        may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com
        

var player = new function(){
    this.tabs;
    this.activeItem = {};
    this.updateActiveItemObject = function(tab, type, is_playlist, playlist_data){
        if(typeof is_playlist === 'undefined'){
            is_playlist = this.activeItem.is_playlist;
        }
        if(typeof playlist_data === 'undefined'){
            playlist_data = this.activeItem.playlist_data;
        }
        this.activeItem = {'tab':tab, 'type':type, 'is_playlist':is_playlist, 'playlist_data':playlist_data};
        
    };
    
    this.init = function(){
        this.applicationVar = new application('player');
        this.applicationVar.create('Player', 'html', '<div id="playerFrame"></div>',{width: 7, height:  7, top: 2, left: 2, hidden: true});
        
        
        var initHTML = '<center><span class="icon blue-play" style="width: 70px;height: 70px; margin-top:15px;"></span>';
            initHTML += '<h1 style="margin-top:0;">Create playlists and add videos</h1></center>';
	this.tabs = new tabs('#playerFrame');
        this.tabs.init();
	this.tabs.addTab('Home', 'html',initHTML);
    };
    this.show = function(){
        
        applications.show('show');
    };
    
    this.prev = function(){
        
        if(this.activeItem.is_playlist === true){
            playlists.playPlaylistRow(this.activeItem.playlist_data.playlist_id, parseInt(player.activeItem.playlist_data.order_id)-1);
        }
    };
    this.next = function(){
        
        if(this.activeItem.is_playlist === true){
            playlists.playPlaylistRow(this.activeItem.playlist_data.playlist_id, parseInt(player.activeItem.playlist_data.order_id)+1);
        }
        
    };
    this.updateActiveItemPause = function(callback){
        var $dockPlay = $('.dockPlayer .play');
        $dockPlay.removeClass('white-play');
        $dockPlay.addClass('white-pause');
        $dockPlay.unbind('click');
        $dockPlay.bind('click',callback);
    };
    this.updateActiveItemPlay = function(callback){
        var $dockPlay = $('.dockPlayer .play');
        $dockPlay.addClass('white-play');
        $dockPlay.removeClass('white-pause');
        $dockPlay.unbind('click');
        $dockPlay.bind('click',callback);
    };
    this.loadItem = function($target, type, selector, onStop){
        console.log(type);
        handlers[type].open($target, selector, onStop);
        this.updateActiveItemObject($target.attr('id'), type);
        $('.dockPlayer').show();
    };
    //adds tab and loads item
    this.openItem = function(type, link){
        userHistory.push(type, link);
        var playerFrameId = gui.generateId();
        this.show();
        var tab_id = this.tabs.addTab('Player', 'html', '<div class="playerFrame" id="'+playerFrameId+'"></div>', function(){/*onclose*/});
        var $target = $('.playerFrame#'+playerFrameId);
        
        this.loadItem($target, type, link, 
        //onStop function
        function(){
            $('.dockPlayer').hide();
        });
        //
        this.tabs.updateTabTitle(tab_id,handlers[type].getTitle(link));
    };
    this.loadYoutubeVideo = function($target, selector, onStop){
        handlers.youtube.open($target, selector, onStop);
        //update active Object
        this.updateActiveItemObject($target.attr('id'), 'youtube');
    };
};