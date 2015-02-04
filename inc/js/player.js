
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
        
        var player_id = gui.generateId();
        //to avoid xss an iframe
        this.tabs.addTab('Play', 'html','<iframe id="'+player_id+'"></iframe>');
        
        $('#'+player_id).contents().find('body').html('<b>asd</b>');
        
    };
    
    this.loadYoutubeVideo = function($target, selector, onStop){
        var videoId;
        if(is_url(selector)){
            videoId = youtubeURLToVideoId(selector);
        }else{
            videoId = selector;
        }
        
        
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
                  });
                }
    };
};