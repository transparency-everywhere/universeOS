
var User = new function(){
    this.userid;
    
    this.updateGUI = function(userid){
            $('.userProfile').each(function(){
                if($(this).attr('data-userid') == userid){
                    $(this).replaceWith(User.generateProfile(userid));
                }
            });
    };
    
    
    this.setUserId = function(id){
        
        this.userid = id;
    };
    this.getBorder = function(lastActivity){
    //every userpicture has a border, this border is green if the lastactivty defines that
    //the user is online and its red if the lastactivity defines that the user is offline.



        var border;
        if(lastActivity === 1){
                border = 'border-color: green';
            }else{
                border = 'border-color: red';
            }

        return border;
    };
    this.showPicture = function(userid, lastActivity, size){
	 
        debug.log('showPicture initialized...');
        var userpicture = getUserPicture(userid);
        if(typeof lastActivity === 'undefined'){
            debug.log('     get user activity for user '+userid);
            var lastActivity = User.getLastActivity(userid); //get last activity so the border of the userpicture can show if the user is online or offline
        }
        if(typeof size === 'undefined'){
            
            debug.log('     set size to standard size');
            var size = 20;
        }
        
        var radius = size/2;

        var ret;
        ret = '<div class="userPicture userPicture_'+userid+'" style="background: url(\''+userpicture+'\'); '+User.getBorder(lastActivity)+'; width: '+size+'px;height:  '+size+'px;background-size: 100%;border-radius:'+radius+'px"></div>';

        debug.log('     update border for all other userpictures of this user');
        $('.userPicture_'+userid).css('border', User.getBorder(lastActivity)); //update all shown pictures of the user

        debug.log('...showSignature finished');
        return ret;
	            
    };
    this.getLastActivity = function(request){
		            //load data from server
                            var result = api.query('api.php?action=getLastActivity',{ request : request });
		            
		            if(is_numeric(request)){
		                if(result.length > 0){
		                    response = result;
		                }
		            }else{
		                var response = new Array();
		                
		                var lastActivityArray = JSON.parse(result);
		                $.each(lastActivityArray, function(index, value) {
		                        response[index]=parseInt(value); 
		                   });
		                
		                
		            }
		            
		            
		            
		            if(is_numeric(request)){
		                return parseInt(response);
		            }else{
		                return response
		            }
        
    };
    this.getProfileInfo = function(userid){
        return api.query('api/user/getProfileInfo/', { user_id:userid });
    }
    this.getAllData = function(userid){
        //data will only be returned if getUser()==userid or userid is on buddylist of getUser()
        return api.query('api/user/getAllData/', { user_id:userid });
    }
    
    this.getGroups = function(){
        return api.query('api/user/getGroups/', { });
    };
    this.inGroup = function(group_id){
        return jQuery.inArray(group_id+'', User.getGroups());
    };
    this.showSignature = function(userid, timestamp, reverse){
        
        debug.log('showSignature for user '+userid+' initialized...');
        var username = useridToUsername(userid);
        
        var output="";
            output += "<div class=\"signature\" style=\"background: #EDEDED; border-bottom: 1px solid #c9c9c9;\">";
            output += "    <table width=\"100%\">";
            output += "        <tr width=\"100%\">";
            if(reverse){
                output += "            <td style=\"width:50px; padding-right:10px;\">"+User.showPicture(userid, undefined, 40)+"<\/td>";
                output += "            <td>";
                output += "                <table>";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 16px;line-height: 17px;\" align=\"left\"><a href=\"#\" onclick=\"showProfile("+userid+");\">"+username+"<\/a><\/td>";
                output += "                    <\/tr>             ";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 12px;line-height: 23px;\">";
                output += "                            <i>";
                output += universeTime(timestamp);
                output += "                            <\/i>";
                output += "                        <\/td>";
                output += "                    <\/tr>";
                output += "                <\/table>";
                output += "            <\/td>";
            }else{
                output += "            <td>";
                output += "                <table>";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 10pt;\">&nbsp;"+username+"<\/td>";
                output += "                    <\/tr>             ";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 08pt;\">&nbsp;<i>";
                output += universeTime(timestamp)+"<\/i>";
                output += "                        <\/td>";
                output += "                    <\/tr>";
                output += "                <\/table>";
                output += "            <\/td>";
                output += "            <td><span class=\"pictureInSignature\">"+User.showPicture(userid, undefined, 40)+"<\/span><\/td>";
            }
            output += "        <\/tr>";
            output += "    <\/table>";
            output += "    <\/div>";

         debug.log('...showSignature finished');
            return output;
        
    };
    this.generateProfile = function(user_id){
        var profile_userdata = this.getProfileInfo(user_id);
        var realname = '', city = '', birthdate = '';
        if(typeof profile_userdata['realname'] !== 'undefined'){
            realname = '<span class="realName">'+profile_userdata['realname']+'</span>';
        }
        if(typeof profile_userdata.home !== 'undefined'){
            city += '<span class="city">from '+profile_userdata.home+'</span>';
        }
        if(typeof profile_userdata.place !== 'undefined'){
            city += '<span class="home">from '+profile_userdata.home+'</span>';
        }
        
        var buttons = '';
        if(!buddylist.isBuddy(user_id) || user_id != User.userid){
            buttons = '<a class="button" onclick="buddylist.addBuddy('+user_id+'); $(this).text(\'request sent\');">Add Friend</a>';
        }
        
        
        var output   = '<div class="profile userProfile" data-userid="'+user_id+'">';
                output += '<header>';
                    output += User.showPicture(user_id);
                        output += '<div class="main">';
                        
                            output += '<span class="userName">';
                                output += useridToUsername(user_id);
                            output += '</span>';
                            output += '<span class="realName">';
                                output += realname;
                            output += '</span>';
                            output += '<span class="place">';
                                output += city;
                            output += '</span>';
                        output += '</div>';
                        
                        output += '<div class="buttons">';
                        output += buttons;
                        output += '</div>';

                output  += '</header>';
                //output  += '<div class="">';
                    output += '<div class="profileNavLeft leftNav dark">';
                        output += '<ul>';
                            output += '<li data-type="favorites"><img src="gfx/profile/sidebar_fav.svg"/>Favorites</li>';
                            output += '<li data-type="files"><img src="gfx/profile/sidebar_files.svg"/>Files</li>';
                            output += '<li data-type="playlists"><img src="gfx/profile/sidebar_playlist.svg"/>Playlists</li>';
                            output += '<li class="openChat"><img src="gfx/chat_icon.svg"/>Open Chat</li>';
                        output += '</ul>';
                        output += '</div>';
                    output += '<div class="profileMain">';
                        output += '<ul class="profileMainNav">';
                            output += '<li class="active" data-type="activity">Activity</li>';
                            output += '<li data-type="friends">Friends</li>';
                            
                            output += '<li data-type="info">Info</li>';
                            output += '<li data-type="groups">Groups</li>';
                        output += '</ul>';
                        output += '<div class="content">';
                        
                            output += '<div class="profile_tab favorites_tab">';
                                output += fav.show(1);
                            output += '</div>';
                            output += '<div class="profile_tab files_tab">'+filesystem.showFileBrowser(profile_userdata['homefolder'])+'</div>';
                            output += '<div class="profile_tab playlists_tab">';
                                
                                output += '<ul>';
                                var profile_playlists = playlists.getUserPlaylists('show',user_id);
                                $.each(profile_playlists['ids'], function(index, value){
                                    output += '<li onclick="playlists.showInfo(\''+value+'\');"><div><span class="icon icon-playlist"></span>';
                                    output += '<span  style="font-size:18px; padding-top: 5px;" onclick="">'+profile_playlists['titles'][index]+'</span>';
                                    //output += item.showItemSettings('user', value);
                                    output += '</div></li>';
                                });
                                output += '</ul>';
                            output += '</div>';
                            
                            
                            output += '<div class="profile_tab activity_tab" style="display:block"></div>';
                            
                            
                            
                            //buddies
                            output += '<div class="profile_tab friends_tab">';
                                output += '<ul>';
                                var profile_buddylist = buddylist.getBuddies(user_id);
                                $.each(profile_buddylist, function(index, value){
                                    output += '<li><div>'+User.showPicture(value);
                                    output += '<span class="username" style="padding-top: 5px; font-size:18px;">'+useridToUsername(value)+'</span>';
                                    output += '<span class="realname">'+useridToUsername(value)+'</span>';
                                    output += item.showItemSettings('user', value);
                                    output += '</div></li>';
                                });
                                output += '</ul>';
                            output += '</div>';
                            
                            output += '<div class="profile_tab info_tab">';
                            
                                $.each(profile_userdata, function(key, value){
                                    switch(key){
                                        case 'home':
                                            key = 'from';
                                            break;
                                        case 'city':
                                            key = 'lives in';
                                            break;
                                        case 'school1':
                                            key = 'school';
                                            break;
                                        case 'university1':
                                            key = 'university';
                                            break;
                                    }
                                    if(!is_numeric(key)){
                                        output += '<span class="'+key+'"><label>'+key+'</label>'+value+'</span>';
                                    }
                                });
                            
                            output += '</div>';
                            
                            
                            output += '<div class="profile_tab groups_tab">';
                            
                                output += '<ul>';
                                var profile_groups = groups.get(user_id);
                                if(typeof profile_groups !== 'undefined'){
                                    $.each(profile_groups, function(index, value){
                                        output += '<li onclick="groups.showProfile('+value+')"><div><span class="icon icon-group"></span>';
                                        output += '<span class="username" style="font-size:18px; padding-top: 5px;">'+groups.getTitle(value)+'</span>';
                                        output += '</div></li>';
                                    });
                                }
                                output += '</ul>';
                            output += '</div>';
                        
                        output += '</div>';
                    //output += '</div>';
                output  += '</div>';
            output  += '</div>';
            return output;
    };
    this.initProfileHandlers = function(user_id){
        
    };
    this.showProfile = function(user_id){
        var output = this.generateProfile(user_id);
        reader.show();
        reader.tabs.addTab('Profile', 'html', output);
        
        //load feed
        var profileFeed = new Feed('user', '.activity_tab', user_id);
        $('.profileMainNav li, .profileNavLeft li').unbind('click');
        $('.profileMainNav li, .profileNavLeft li').bind('click', function(){
            var type = $(this).attr('data-type');
            $(this).parent().parent().parent().find('.profileMainNav li').removeClass('active');
            $(this).parent().parent().parent().find('.profileNavLeft li').removeClass('active');
            $(this).addClass('active');
            $(this).parent().parent().parent().find('.content .profile_tab').hide();
            $(this).parent().parent().parent().find('.content .'+type+'_tab').show();
        });
        
        
    };
    this.logout = function(){
        gui.alert('Goodbye :)', '');
        api.query('api/user/logout/index.php', {},function(data){
             
            window.location.href=window.location.href;
        });
    };
    this.getRealName = function(userid){
        var realname = this.getProfileInfo(userid)['realname'];
        console.log(realname);
        return realname;
    };
    
    this.getPrivacy = function(){
        return api.query('api/user/getPrivacy/', {});
    };
};