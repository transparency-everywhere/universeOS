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


//requires jquery
//adding service to media class:
//1. add regex to gettype
var media = function(url){
    this.URL = url;
    this.type;
    this.authData = {
        soundcloud_client_id:'84102f71034779ad4237c49fd95c388b',
        soundcloud_client_secret:'e5d37909c2fb985d66421d5d253d172b'
    };
    this.getType = function(){
        var url = this.URL;
        if(/((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(url)){
            this.type = 'youtube';
        }else if(/^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/.test(url)){
            this.type = 'vimeo';
        }else if(/^https?:\/\/(soundcloud.com|snd.sc)\/(.*)$/.test(url)){
            this.type = 'soundcloud';
        }
    };
    this.getType();
    this.getId = function(){
        var url = this.URL;
      switch(this.type){
          case 'youtube':
                var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                var match = url.match(regExp);
                if (match&&match[7].length==11){
                    return match[7];
                }else{
                    return false;
                }
                      break;
          case 'vimeo':
                var regExp = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;

                var match = url.match(regExp);

                if (match){
                    return match[2];
                }else{
                    return false;
                }
                      break;
          case 'soundcloud':
                
              
              
                      break;
      };
    };
    this.getTitle = function(){
        
        return handlers[this.type].getTitle(this.URL);
    };
    this.loadPlayer = function($element, options){
        var videoID = this.getId();
        switch(this.type){
            case'youtube':
                var playerID = randomString(5,'aA#');
                //from https://developers.google.com/youtube/iframe_api_reference#Loading_a_Video_Player
                $element.html('<div id='+playerID+'><iframe id="player" type="text/html" width="640" height="390" src="http://www.youtube.com/embed/'+videoID+'?enablejsapi=1&origin=http://example.com" frameborder="0"></iframe></div>');
                // 2. This code loads the IFrame Player API code asynchronously.
                var tag = document.createElement('script');

                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                // 3. This function creates an <iframe> (and YouTube player)
                //    after the API code downloads.
                var player;
                function onYouTubeIframeAPIReady() {
                  player = new YT.Player(playerID, {
                    height: '390',
                    width: '640',
                    videoId: videoID,
                    events: {
                      'onReady': onPlayerReady,
                      'onStateChange': onPlayerStateChange
                    }
                  });
                }

                // 4. The API will call this function when the video player is ready.
                function onPlayerReady(event) {
                  if(options.autoplay)
                    event.target.playVideo();
                }

                // 5. The API calls this function when the player's state changes.
                //    The function indicates that when playing a video (state=1),
                //    the player should play for six seconds and then stop.
                var done = false;
                function onPlayerStateChange(event) {
                  if (event.data == YT.PlayerState.PLAYING && !done) {
                    setTimeout(stopVideo, 6000);
                    done = true;
                  }
                }
                function stopVideo() {
                  player.stopVideo();
                }
                break;
        };
        
    };
    //@param callback: soundcloud doesn't allow asynchronus tasks, so it requires a callback like media.getData(function(data){consoloe.log(data)});
    this.getData = function(callback){
        var authStuff = this.authData;
        var videoId = this.getId();
        switch(this.type){
            case 'youtube':
                var result;
                    $.ajax(
                    {
                            url: "https://gdata.youtube.com/feeds/api/videos/"+videoId+"?v=2&alt=json",
                            async:false,
                            success: function(data)
                            {
                                    result = data;
                            }
                    });

                    return result.entry;
                break;
            case 'vimeo':
                var result;
                    $.ajax(
                    {
                            url: "http://vimeo.com/api/v2/video/"+videoId+".json",
                            async:false,
                            success: function(data)
                            {
                                    result = data;
                            }
                    });

                    return result;
                break;
            case 'soundcloud':
    $.ajax({
        'url': "https://api.soundcloud.com/resolve.json?url="+encodeURIComponent(url)+"&client_id="+authStuff.soundcloud_client_id+'&none=none',
        'dataType': "json",
        'success': function (data) {
            callback(data);
        }
    });
                break;
        };
    };
};
