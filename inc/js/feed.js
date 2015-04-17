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
        


var Feed = function(type, $selector, initTypeId){
    this.initType = type;
    this.lastFeedReceived;
    this.frame_id; //generate rand id
    this.updateLastFeedReceived = function(feedId){
        if(parseInt(feedId)>parseInt(this.last_feed_received)){
            this.last_feed_received = feedId;
            
        }
    };
    this.init = function(initType, initTypeId, limit){
        this.frame_id = gui.generateId();
        
        this.last_feed_received = 0;
        var output = '';
        
        var pointer = this;
        var loadedFeeds = this.loadFeeds(initType, initTypeId, limit);
        
        $.each(loadedFeeds,function(index, value){
            output += pointer.generateSingleFeed(value);
        });
        
        
        
        return '<div class="feedFrame" id="'+this.frame_id+'" data-type="'+this.initType+'" data-last="'+this.last_feed_received+'">'+output+'</div>';
        
    };
    this.generateSingleFeed = function(feedData){
        this.updateLastFeedReceived(parseInt(feedData['id']));
        return feed.generateSingleFeed(feedData);
    };
    this.loadFeeds = function(type, typeId, limit){
        return api.query('api/feed/load/', { type : type, typeId: typeId, limit:limit});
    };
    $($selector).html(this.init(type, initTypeId));
};

var feed = new function(){
    this.reload = function(type){
        
        $('.feedFrame[data-type="'+type+'"]').each(function(){
            
            var last_feed_loaded = $(this).attr('data-last');
            var typeId = 0;
            var loadedFeeds = feed.loadFeedsFrom(last_feed_loaded, type, typeId);
            var output = '';

            
            //if php select is empty it returns a string, 
            //which js will handle as object 
            //which fucks jQuery.each up. 
            //.length was the easiest solution 
            $.each(loadedFeeds,function(index, value){
                output += feed.generateSingleFeed(value);
            });
            $(this).prepend(output);

            
        });
        
    };
    this.generateSingleFeed = function(feedData){
        debug.log('generateSingleFeedInitalized...');
        
        var feedContent = '<div class="feedContent">'+feedData['feed']+'</div>';
                if(feedData['type'] === 'showThumb'){
                    debug.log('     showItemThumb');
                    feedContent += '<div class="feedAttachment">'+item.showItemThumb(feedData['attachedItem'], feedData['attachedItemId'])+'</div>';
                }
        
        //load comments
        
        //load contextmenue(s)
        
        var output = '<div class="feedEntry feedNo'+feedData['id']+'">';
            debug.log('     showSignature');
            output += User.showSignature(feedData['author'], feedData['timestamp'])+feedContent;
            output += '<div class="options">';
                output += item.showScoreButton('feed', feedData['id']);//load score button
                
                output += item.showItemSettings('feed', feedData['id']);
                
                output += '<a href="javascript:comments.loadComments(\'feed\',\''+feedData['id']+'\');" class="btn btn-xs" style="color: #dcdcdc"><i class="icon icon-comment"></i></a>';
             
            output += '</div>';
            output += '<div class="commentLoadingArea" id="comment_feed_'+feedData['id']+'" style="display:none;"></div>';
            output += '</div>';
            
        debug.log('...generateSingleFeed finished');
        return output;
    };
    this.init = function(){
        
        var html = this.generateHeader();
        html += "        <div id=\"feedFrame\">";
        html += "            <div class=\"addFeed\">";
        html += "            <\/div>";
        html += "            <div class=\"feedMain\">";
        html += "            <\/div>";
        html += "            ";
        html += "        <\/div>";
        
        this.applicationVar = new application('feed');
	this.applicationVar.create('Feed', 'html', html,{width: 2, height:  5, top: 0, left: 0});
	
        
        var feedClass = new Feed('global', '.feedMain');
        
        privacy.load('#addFeedPrivacy', '"h//f"', true);
        
        $('#feedInput').html(function(){
                 $(this).focus(function(){
                    $( "#feedheader" ).animate({ height: "100px" }, 500 );
                    $( "#feedFrame" ).animate({ top: "100px" }, 500 );
                    $('#feedInputBar').slideDown(500);
                    $( "#addFeedPrivacy" ).animate({ top: "100px" }, 500 );
                    privacy.init();
                });
                    $(this).focusout(function(){
                        
//                        if($('#feedInput').val().length == 0){
//                            $( "#feedheader" ).animate({ height: "61px" }, 500 );
//                            $( "#feedFrame" ).animate({ top: "61px" }, 500 );
//                            $('#feedInputBar').slideUp(500);
//                            $( "#addFeedPrivacy" ).animate({ top: "61px" }, 500 );
//                            $('#addFeedPrivacy').slideUp(500);
//
//                        }
                    });
            });
            
            $('#feedInputForm').submit(function(){
                $(this).submit();
                $('#feedInput').val('');
            });
            
            
            //everything here could probaly be deleted
            $("#showGroups").click(function () {
                $("#selectGroups").toggle("slow");
            });
            $("#hideGroups").click(function () {
                $("#selectGroups").toggle("slow");
            });
            $("#selectGroups").click(function () {
                //maybe toggle
            });
            $("#enlargeSubmitArea").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $("#beforeToggle").css(cssOb);
                    var header = {
                        'height' : '120'
                        }
                    
                    var frame = {
                        'top' : '120'
                        }   
                    $("#feedheader").css(header);
                    $("#feedFrame").css(frame);
            $('#submitToggle').show();
            $('#submitText').show();
            });
            $("#minimizeSubmitArea").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    var headera = {
                        'height' : '110px'
                        }
                    
                    var framea = {
                        'top' : '110px'
                        }   
                    $("#feedheader").css(headera);
                    $("#feedFrame").css(framea);    
                    $("#submitToggle").css(cssOb);
                    $(".submitArea").css(cssOb);
            $('#beforeToggle').show();
            });
            $("#feedAddText").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitText').show();
            });
            $("#feedAddFile").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitFile').show();
            });
            $("#feedAddVote").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitVote').show();
            });
    };
    this.generateHeader = function(){
        var output="";
        output += "<div class=\"windowHeader\" id=\"feedheader\">";
        output += "            <i class=\"icon dark-pencil\" style=\"height: 32px; width: 32px; top: 14px;position: absolute;right: 226px;\"></i>";
        output += "            <form id=\"feedInputForm\" method=\"post\" action=\" doit.php?action=createFeed\" target=\"submitter\">";
        output += "            <div style=\"margin: 15px;\">";
        output += "                        <textarea id=\"feedInput\" name=\"feedInput\" onclick=\"$(this).val('');\">What's Up?<\/textarea>";
        output += "                        <div style=\"\" id=\"feedInputBar\">";
        output += "                                <div class=\"btn-toolbar\" style=\"float: left;\">";
        output += "                                        <a class=\"privacyButton\" href=\"#\" onclick=\"$('#feedInput').focus(); $('#addFeedFile').hide('slow'); $('#addFeedPrivacy').slideToggle(500);\" title=\"privacy\"> Privacy <\/a>";
        output += "                                <\/div>";
        output += "                                <input type=\"submit\" style=\"float:right;\" value=\"submit\" class=\"btn\">";
        output += "                        <\/div>";
        output += "                    <\/div>";
        output += "                    <div id=\"addFeedPrivacy\" style=\"background: #030303\">";
        output += "                    <\/div>";
        output += "                    <\/form>";
        output += "                    <\/div>";
        
        
        return output;

    };
    this.getData = function(feedId){
        return api.query('api/feed/select/', { feedId : feedId});
    };
    this.loadFeedsFrom = function(startId, type, typeId){
        return api.query('api/feed/loadFrom/', {start_id: startId, type : type, typeId: typeId});
    };
};
			  