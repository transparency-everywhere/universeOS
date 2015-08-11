
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
//        @author nicZem for transparency-everywhere.com
//        @author pabst for transparency-everywhere.com
        

var reader = new function(){
    this.tabs;
    
    this.uffChecksums = []; //var to store checksums for reload
    
    this.init = function(){
        var grid = {width: 5, height:  4, top: 6, left: 3, hidden: true};
        if(proofLogin())
            grid = {width: 8, height:  8, top: 0, left: 2, hidden: true};
        this.applicationVar = new application('reader');
        this.applicationVar.create('Display', 'url', 'modules/reader/index.php', grid);
        
        
	this.tabs = new tabs('#readerFrame');
        this.tabs.init();
	this.tabs.addTab('Home', '', reader.generateStartpage());
    };
    this.show = function(){
        
        applications.show('reader');
    };
    this.generateStartpage = function(){
        console.log('generateStartpage');
        var html = '';
        if(proofLogin()){
            html += '<div class="readerStartpage">';
                html += '<div class="welcomearea"><div class="userPicture"><img style="max-width:100px; max-height: 100px;" src="' + getUserPicture(User.userid) + '" onclick="User.showProfile(User.userid); return false"></div><div class="welcomeText">Hidiho, <a href="#" onclick="User.showProfile(User.userid); return false">'+ useridToUsername(User.userid) +'</a>,<br />good to see you!<div></div>';
                html += '<div class="navi">';
                    html += '<ul>';
                        html += "<li onclick=\"$( '.hometab' ).show();$( '.groupstab' ).hide();$( '.favoritestab' ).hide();$( '.playliststab' ).hide();$( '.myFilestab' ).hide();return false\">" + filesystem.generateIcon('home', 'white') + '</li>';
                        html += "<li onclick=\"$( '.hometab' ).hide();$( '.groupstab' ).show();$( '.favoritestab' ).hide();$( '.playliststab' ).hide();$( '.myFilestab' ).hide();return false\">Groups</li>";
                        html += "<li onclick=\"$( '.hometab' ).hide();$( '.groupstab' ).hide();$( '.favoritestab' ).show();$( '.playliststab' ).hide();$( '.myFilestab' ).hide();return false\">Favorites</li>";
                        html += "<li onclick=\"$( '.hometab' ).hide();$( '.groupstab' ).hide();$( '.favoritestab' ).hide();$( '.playliststab' ).show();$( '.myFilestab' ).hide();return false\">Playlists</li>";
                        html += "<li onclick=\"$( '.hometab' ).hide();$( '.groupstab' ).hide();$( '.favoritestab' ).hide();$( '.playliststab' ).hide();$( '.myFilestab' ).show();return false\">My Files</li>";
                    html += '</ul>';
                html += '</div>';
                //generate tabs
                html += reader.initTabs();
            html += '</div>';
        } else {
            //hier die "not logged in startpage" zusammenfriemeln
            html = '';
        }
        
        console.log('generateStartpage done');
        return html;
            
    }
    this.initTabs = function(){
        console.log('initTabs');
        
        var html = '';
            
            
        var history_items = User.getHistoryArray(); //get array with 5 dummy entries (functions.js)
        
        var popular_items = filesystem.getPopularItemsArray(); //get popular public items
        
        var feature = false; //maybe later
        
        
        var group_items = groups.getGroupArray(User.userid); //get groups of the user
        
        var popular_groups = groups.getPublicGroupArray(User.userid); //popular groups > public and with the highest membercount
        
        
        var fav_history = fav.getFavHistory(); //get latest 5 favorites of the user
        
        var fav_items = fav.getFavArray(User.userid); //get favorites of the user   
        
        
        var playlist_items = playlists.getPlaylistArray(User.userid); //get playlists of the user
        
        var public_playlists = playlists.getPublicPlaylistArray(); //get latest public playlists
        
        
        var myFiles_items = filesystem.getMyFiles(User.userid); //get folder, elements and files of the user


        html += '<div class="tabs">';
        
            //generate home view
            html += this.buildTab('home', 'clock', 'My current history', history_items, 'suggestion', 'Popular in the universeOS', popular_items, feature);

            //generate groups view
            html += this.buildTab('groups', 'group', 'My groups', group_items, 'suggestion', 'Popular groups', popular_groups);

            //generate favorites view
            html += this.buildTab('favorites', 'clock', 'My latest favorites', fav_history, 'fav', 'All my favorites', fav_items);

            //generate playlist view
            html += this.buildTab('playlists', 'playlist', 'My playlists', playlist_items, 'suggestion', 'The latest public playlists', public_playlists);

            //generate my files view
            html += this.buildTab('myFiles', 'file', 'My files', myFiles_items);

        html += '</div>';
        console.log('initTabs done');
        return html;
    }
    this.buildTab = function(tab, iconA, titleA, itemsA, iconB, titleB, itemsB, feature){
        console.log('buildTab ' + tab);
        //generate view
        var html = '';
        html += '<div class="' + tab + 'tab singletab">';
            html += '<div class="' + tab + ' sectionA">';
                html += '<div class="' + tab + ' iconA">';
                    html += filesystem.generateIcon(iconA, 'blue');
                html += '</div>';
                html += '<div class="' + tab + ' titleA">';
                    html += titleA;
                html += '</div>';
                if(tab === 'home'){
                    html += '<div class="' + tab + ' itemsA">';
                        html += this.buildHistory(itemsA);
                    html += '</div>';
                } else {
                    html += '<div class="' + tab + ' itemsA">';
                        html += '<ul>';
                        if(typeof itemsA !== 'undefined' || itemsA !== undefined){
                            var onclick = "";
                            $.each(itemsA, function(key, value){
                                if( value['type'] === 'element'){
                                    onclick = "onclick=\"elements.open('" + value['itemId'] + "'); return false;\"";
                                } else if( value['type'] === 'file'){
                                    onclick = "onclick=\"reader.openFile('" + value['itemId'] + "'); return false;\"";
                                } else if( value['type'] === 'link'){
                                    onclick = "onclick=\"reader.openLink('" + value['itemId'] + "'); return false;\"";
                                } else if( value['type'] === 'folder'){
                                    onclick = "onclick=\"folders.open('" + value['itemId'] + "'); return false;\"";
                                } else if( value['type'] === 'group'){
                                    onclick = "onclick=\"groups.show('" + value['itemId'] + "'); return false;\"";
                                } else if( value['type'] === 'playlist'){
                                    onclick = "onclick=\"playlists.playPlaylist('" + value['itemId'] + "'); return false;\"";
                                }
                                html += '<li ' + onclick + '>';
                                    html += '<div class="' + tab + ' itemsA icon">' + filesystem.generateIcon(value['type']) + '</div>';
                                    html += '<div class="' + tab + ' itemsA title">' + gui.shorten(value['title'], 30) + '</div>';
                                html += '</li>';
                            });
                        };
                        html += '</ul>';
                    html += '</div>';
                }
            html += '</div>';
            
            if(typeof itemsB !== 'undefined' || itemsB !== undefined){
                html += '<div class="' + tab + ' sectionB">';
                    html += '<div class="' + tab + ' iconB">';
                        html += filesystem.generateIcon(iconB, 'blue');
                    html += '</div>';
                    html += '<div class="' + tab + ' titleB">';
                        html += titleB;
                    html += '</div>';
                    if(feature){
                        html += '<div class="' + tab + ' feature">';
                            html += '<ul>';
                                html += '<li>Featured1</li><li>Featured2</li><li>Featured3</li>';
                            html += '</ul>';
                        html += '</div>';
                    }
                    html += '<div class="' + tab + ' itemsB">';
                    html += '<ul>';
                    var onclick = "";
                    $.each(itemsB, function(key, value){
                        if( value['type'] === 'element'){
                            onclick = "onclick=\"elements.open('" + value['itemId'] + "'); return false;\"";
                        } else if( value['type'] === 'file'){
                            onclick = "onclick=\"reader.openFile('" + value['itemId'] + "'); return false;\"";
                        } else if( value['type'] === 'link'){
                            onclick = "onclick=\"reader.openLink('" + value['itemId'] + "'); return false;\"";
                        } else if( value['type'] === 'folder'){
                            onclick = "onclick=\"folders.open('" + value['itemId'] + "'); return false;\"";
                        } else if( value['type'] === 'group'){
                            onclick = "onclick=\"groups.show('" + value['itemId'] + "'); return false;\"";
                        } else if( value['type'] === 'playlist'){
                            onclick = "onclick=\"playlists.playPlaylist('" + value['itemId'] + "'); return false;\"";
                        }
                        html += '<li ' + onclick + '>';
                            html += '<div class="' + tab + ' itemsB icon">' + filesystem.generateIcon(value['type']) + '</div>';
                            html += '<div class="' + tab + ' itemsB title">' + gui.shorten(value['title'], 30) + '</div>';
                        html += '</li>';
                    });
                    html += '</ul>';
                html += '</div>';
            html += '</div>';
        }
        html += '</div>';
        return html;
    }
    this.openFile = function(file_id){
        var fileData = filesystem.getFileData(file_id);
        var zoomInString = 'zoomIn(' + fileData['folder'] + ')';
        var zoomOutString = 'zoomOut(' + fileData['folder'] + ')';
        
        var header = "<header class=\"white-gradient\">";
        header += filesystem.generateIcon(fileData['type'], 'grey');
        header += "<span class=\"title\">" + fileData['title'] + "</span>";
        header += '<div class="whiteGradientScoreButton">' + item.showScoreButton('file', file_id) + '</div>';
        header += '<a href="./out/download/?fileId=' + file_id + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download', 'grey') + '</a>';
        header += item.showItemSettings('file', file_id);
        if(fileData['type'] === 'image' || fileData['type'] === 'image/jpeg' || fileData['type'] === 'image/png' || fileData['type'] === 'image/tiff' || fileData['type'] === 'image/gif') {
            header += '<div id="zoom_in">' + filesystem.generateIcon('plus', 'grey', '', zoomInString) + '</div>';
            header += '<div id="zoom_out">' + filesystem.generateIcon('minus', 'grey', '', zoomOutString) + '</div>';
        }
        header += "</header>";
        
        var output = '';
        var halfPath = '' + encodeURIComponent(folders.getPath(elements.getData(fileData['folder'])['folder']) + fileData['filename']);
        var secondHalf = halfPath.replace(/%2F/g, '/');
        var path = "./upload/" + secondHalf;
        userHistory.push(fileData['type'], file_id, fileData['title']);
        
        switch(fileData['type']){
            //cases: text, uff, image, pdf?, audio?, video?
            case 'image':
            case 'image/jpeg':
            case 'image/png':
            case 'image/tiff':
            case 'image/gif':
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"imageReader\">";
                            output += "<div class=\"mainImage\">";
                                output += "<img src=\"" + path + "\" / id=\"viewedPicture_" + fileData['folder'] + "\">";
                            output += "</div>";
                            output += "<div class=\"previewImages\">";
                                $.each(elements.getFileList(fileData['folder']), function(key, value){
                                    var thumbPath = "./upload/" + folders.getPath(elements.getData(fileData['folder'])['folder']) + "thumbs/" + value['data']['filename'];
                                    if(value['data']['type'] === "image" || value['data']['type'] === "image/jpeg" || value['data']['type'] === "image/png" || value['data']['type'] === "image/tiff" || value['data']['type'] === "image/gif"){
                                        output += "<img src=\"" + thumbPath + "\" onclick=\"reader.openFile('" + value['data']['id'] + "'); return false\"/>";
                                    }
                                });
                            output += "</div>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            
            case 'text/plain'||'text/x-c++':
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"textReader\">";
                            output += nl2br(htmlentities(filesystem.readFile(fileData['id'])));
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            
            case 'audio':
            case 'audio/wav':
            case 'audio/ogg':
            case 'audio/mpeg':
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"audioReader\">";
                            output += "<audio controls>";
                                if(fileData['type'] === "audio/wav"){
                                    output += "<source src=" + path + " type=\"audio/wav\">";
                                }
                                else if(fileData['type'] === "audio/mpeg"){
                                    output += "<source src=" + path + " type=\"audio/mpeg\">";
                                }
                                else if(fileData['type'] === "audio/ogg"){
                                    output += "<source src=" + path + " type=\"audio/ogg\">";
                                }
                                else if(fileData['type'] === "audio"){
                                    output += "This audio file isn't compatible with HTML5. Please convert it to Ogg, Wav or MP3 files.";
                                }
                                output += "Your browser does not support the audio element.";
                            output += "</audio>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            
            case 'video':
            case 'video/mp4':
            case 'video/ogg':
            case 'video/webm':
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"videoReader\">";
                            output += "<video controls>";
                                if(fileData['type'] === "video/mp4"){
                                    output += "<source src=" + path + " type=\"video/mp4\">";
                                }
                                else if(fileData['type'] === "video/ogg"){
                                    output += "<source src=" + path + " type=\"video/ogg\">";
                                }
                                else if(fileData['type'] === "video/webm"){
                                    output += "<source src=" + path + " type=\"video/webm\">";
                                }
                                else if(fileData['type'] === "video"){
                                    output += "This video isn't compatible with HTML5. Please convert it to Ogg, WebM or MP4 files.";
                                }
                                output += "Your browser does not support HTML5 video.";
                            output += "</video>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            
            case 'application/pdf':
                //@sec including pdf could be a privacy problem because you can get informations by include analytic images etc
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"pdfReader\">";
                            output += "<div class=\"iframeFrame\">";
                                output += "<iframe src=\"./" + path + "\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"></iframe>";
                            output += "</div>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            case 'UFF':
                //by nicZem
                if(privacy.authorize(fileData['privacy'], "edit", fileData['owner'])){
                    var readOnly = "false";
                }else{
                    var readOnly = "true";
                }
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        //this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js
                        //dirty solution!!!
                        output += "<div class=\"uffViewerNav\">";
                                output += "<div style=\"margin: 10px;\">";
                                        output += "<ul>";
                                            output += '<li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon icon-user"></i>&nbsp;<strong>Active Users</strong></li>';
                                        output += "</ul>";
                                output += "</div>";
                        output += "</div>";
                        //document frame
                        output += "<div class=\"uffViewerMain\">";
                                output += "<textarea class=\"uffViewer_"+file_id+" WYSIWYGeditor\" id=\"editor1\">";
                                output += "</textarea>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            default:
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        output += "<div class=\"swwReader\">";
                            output += "<span>Sorry, but this file isn't compatible with the universeOS Reader. :( You just can download it by clicking on the download button above.</span>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
        }
        
        reader.tabs.addTab(title, 'html', output, function(){
            //onclose
            delete reader.uffChecksums[file_id];
        });
        switch(fileData['type']){
            case'UFF':
                
                
                
                api.query('api/files/read/', { file_id : file_id}, function(uffContent){
                    
                    initUffReader(file_id, uffContent, "false");
                    
                    
                    //store hash
                    reader.uffChecksums[file_id] = hash.MD5(uffContent);
                });
                
                break;
        }
        applications.showApplication('reader');
        return output;
        
    };
    this.openLink = function(type, link, title){
        player.openItem(type, link);
        userHistory.push('link', id, title);
    };
    this.buildHistory = function(userHistoryArray){
        var itemsA = userHistoryArray;
        var tab = 'home';
        var html = '<ul>';
        var i = 0;
        if(typeof itemsA !== 'undefined' || itemsA !== undefined){
            var onclick = "";
            $.each(itemsA, function(key, value){
                i++;
                if( value['type'] === 'element'){
                    onclick = "onclick=\"elements.open('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'file'){
                    onclick = "onclick=\"reader.openFile('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'link'){
                    onclick = "onclick=\"reader.openLink('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'folder'){
                    onclick = "onclick=\"folders.open('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'group'){
                    onclick = "onclick=\"groups.show('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'playlist'){
                    onclick = "onclick=\"playlists.playPlaylist('" + value['itemId'] + "'); return false;\"";
                } else if( value['type'] === 'user'){
                    onclick = "onclick=\"User.showProfile('" + value['itemId'] + "'); return false;\"";
                }
                html += '<li ' + onclick + '>';
                    html += '<div class="' + tab + ' itemsA icon">' + filesystem.generateIcon(value['type']) + '</div>';
                    html += '<div class="' + tab + ' itemsA title">' + gui.shorten(value['title'], 30) + '</div>';
                html += '</li>';
            });
        };
        if (i === 0){
            html += '<li class="emptyHistory">';
                html += '<div>Your history ist empty. The history is only stored locally in your cache and it\'s reseted every time you log out or reload the universeOS. We don\'t log or analyze your activities!</div>';
            html += '</li>';
        }
        html += '</ul>'; 
        return html;
    };
};