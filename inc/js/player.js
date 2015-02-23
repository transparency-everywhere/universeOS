
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
    this.applicationVar;
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
        console.log(this.activeItem);
    };
    
    this.init = function(){
        this.applicationVar = new application('player');
        this.applicationVar.create('Player', 'html', '<div id="playerFrame"></div>',{width: 5, height:  4, top: 0, left: 4, hidden: true});
        
        
	this.tabs = new tabs('#playerFrame');
        this.tabs.init();
	this.tabs.addTab('Home', 'html','asd');
    };
    this.show = function(){
        player.applicationVar.show();
    };
    
    this.play = function(options){
        
//        var player_id = gui.generateId();
//        //to avoid xss an iframe
//        this.tabs.addTab('Play', 'html','<iframe id="'+player_id+'"></iframe>');
//        
//        $('#'+player_id).contents().find('body').html('<b>asd</b>');

        var player_id = 'ytplayer';

        document.getElementById(player_id).contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        
        
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
        $dockPlay.addClass('white-wikipedia');
        $dockPlay.click(callback);
        
    };
    this.updateActiveItemPlay = function(callback){
        var $dockPlay = $('.dockPlayer .play');
        $dockPlay.addClass('white-play');
        $dockPlay.removeClass('white-wikipedia');
        $dockPlay.click(callback);
    };
    
    this.loadYoutubeVideo = function($target, selector, onStop){
        var videoId;
        if(is_url(selector)){
            videoId = youtubeURLToVideoId(selector);
        }else{
            videoId = selector;
        }
        var tempThis = this;
        
            var output="";
            output += "        <div id=\"ytplayer\"><\/div>";
            
            $target.html(output);
            
            // Load the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            tag.src = "http://www.youtube.com/player_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                //yt player api is added in plugins.js
                var ytplayer;
                ytplayer = new YT.Player('ytplayer', {
                  height: '100%',
                  width: '100%',
                  videoId: videoId,
                  autoplay: 1,
                  events: {
                    'onReady': onPlayerReady
                  }
                });
                
                function onPlayerReady(){
                    ytplayer.playVideo();
                }
                
                if(typeof onStop === 'function'){
                  ytplayer.addEventListener('onStateChange', function(state){
                      if(state.data === 0){
                          onStop();
                      }
                      if(state.data === 1){
                          var callback = function(){
                              ytplayer.pauseVideo();
                          };
                          tempThis.updateActiveItemPause(callback);
                      }
                      if(state.data === 2){
                          var callback = function(){
                              ytplayer.playVideo();
                          };
                          tempThis.updateActiveItemPlay(callback);
                      }
                  });
                }
                
        //update active Object
        this.updateActiveItemObject($target.attr('id'), 'youtube');
    };
};