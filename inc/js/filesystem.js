//        This file is published by Transparency Everywhere with the best deeds.
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
//        @author nicZem for transparency-everywhere.com
//        @author pabst for transparency-everywhere.com


var filesystem =  new function() {
    
    
    this.generateUploadTab = function(element){
        var html = this.generateLeftNav();
        //alles aus upload.php in einer onestep lösung zusammenbauen
        html += '<div class="frameRight">';
            html += '<div class="uploadTab">';
                html += '<form action="api/files/submitUploader/" method="post" target="submitter">';
                    html += '<h2>Upload</h1>';
                    html += '<hr />';
                    html += '<div class="uploaderHeader">';
                        html += '<div><h3>You will upload files to this collection:</h3></div>';
                        html += '<div class="titleAndIcon"><span class="title">' + elements.getTitle(element) + '</span>' + filesystem.generateIcon('element', 'white') + '</div>';
                    html += '</div>';
                    html += '<h3>Privacy settings:</h2>';
                    html += '<div class="uploadPrivacy"></div>';
                    html += '<h3>Add files:</h2>';
                    html += '<div class="uploadify">';
                        html += '<input id="uploader_file" name="feedFile" type="file" multiple="true">';
                        html += '<ul class="tempFilelist"></ul>';
                        html += '<div id="queue"></div>';
                    html += '</div>';
                    html += '<div class="uploaderCancelButton">Cancel</div>';
                    html += '<div class="uploaderUploadButton"><input type="submit" value="Upload" class="submitUpload"></div>';
                html += '</form>';
            html += '</div>';
        html += '</div>';
        privacy.load('.uploadPrivacy', 'p', 'true');
        return html;
    };
    
    this.openUploadTab = function(element){
        filesystem.applicationVar.show();
//        var tabContent = gui.loadPage('modules/filesystem/upload.php?element='+element); //left nav missing in this solution filesystem.generateLeftNav()+
//        filesystem.tabs.addTab('Upload', '', tabContent);
        //wird zu:
        var tabId = filesystem.tabs.addTab('Upload '+element, '', '');
        filesystem.tabs.updateTabContent(tabId,filesystem.generateUploadTab(element));
        initUploadify('#uploader_file', 'api/files/uploadTemp/', element, '', ''); //the two empty strings are timeStamp and salt - could be empty

    }
    
    
    
    this.init = function(){
        var html = '<div id="fileBrowserFrame"></div>';
        var grid = {width: 6, height:  4, top: 7, left: 6};
        if(proofLogin())
            grid = {width: 6, height:  8, top: 1, left: 3};
        this.applicationVar = new application('filesystem');
        this.applicationVar.create('Filesystem', 'html', html, grid);
        this.tabs = new tabs('#fileBrowserFrame');
        this.tabs.init();
	this.tabs.addTab('universe', '', this.generateFullFileBrowser(0));
    };
    this.generateFullFileBrowser = function(folderId){
        var html = this.generateLeftNav();		  			
        html += this.generateFileBrowser(folderId);
        return html;
    };
    this.generateLeftNav = function(){
        var html = '          <div class="leftNav">';		  			
        html += '              <ul>';		  			
        html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'pupularity\'));return false"><a href="#">' + filesystem.generateIcon('suggestion', 'blue') + ' Suggestions</a></li>';		  			
        html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'1\'));return false"><a href="#">' + filesystem.generateIcon('filesystem', 'blue') + ' All Files</a></li>';		  			
        html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'document\'));return false"><a href="#">' + filesystem.generateIcon('file', 'blue') + ' Documents</a></li>';		  			
        html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'audio\'));return false"><a href="#">' + filesystem.generateIcon('playlist', 'blue') + ' Audio Files</a></li>';		  			
        html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'video\'));return false"><a href="#">' + filesystem.generateIcon('play', 'blue') + ' Video Files</a></li>';		  			
        if(proofLogin()){
            html += '                  <li onclick="filesystem.tabs.updateTabContent(1 ,filesystem.generateFullFileBrowser(\'fav\'));return false"><a href="#">' + filesystem.generateIcon('fav', 'blue') + ' Fav</a></li>';		  			
        }
        html += '                  <!-- <li><i class="icon-warning-sign"></i> deleted</li> -->';		  			
        html += '              </ul>';		  			
        html += '          </div>';		  	
        return html;
    };
    this.generateFileBrowser = function(folderId){
        var showFileBrowser = true;
        var favorite = false;
        if(is_numeric(folderId) || empty(folderId)){
            if(empty(folderId) || folderId === 0){
                folderId = '1';
            }else if(folderId === '2'){
                if(proofLogin()){
                    folderId = User.getAllData(User.userid)['homefolder'];
                    showFileBrowser = true;
                }else{
                    showFileBrowser = false;
                }
            }
            var folderData = folders.getData(folderId);
            if(folderData['folder'] === 2){
                folderData['folder'] = "1";
            }
        }else if(folderId === "fav"){
            showFileBrowser = false;
            favorite = true;
        }      
        var html = '<div class="frameRight fileBrowser_' + folderId + '">';		  			

                    html += '    <div class="path">';		  			
                    if(is_numeric(folderId)){
                        html += '         universe/' + folders.getPath(folderId);
                    }
                    if(proofLogin() && !empty(folderId)){
                        html += '         <a href=\"#\" id=\"settingsButton\" onclick=\"$(\'.fileBrowserSettings' + folderId + '\').slideToggle(\'slow\'); return false\" title=\"more...\" class=\"btn btn-mini\">' + this.generateIcon('settings', 'grey') + '</a>';
                    }		  			
                    html += '    </div>';		  			
                    html += '    <div class="underFrame" style="overflow: none;">';		  			
                    html += '        <div class="fileBrowser">';		  			
                    if(!empty(folderId) && is_numeric(folderId)){
                        html += '        	<ul class="fileBrowserSettings fileBrowserSettings' + folderId + '">';		  			
                        if(proofLogin()){
                            html += '                       <li onclick="fav.add(\'folder\', ' + folderId + ')">' + filesystem.generateIcon('fav', 'white') + '<span class="text">Add to favorites</span></li>';
                        }		  			
                        if(privacy.authorize(folderData['privacy'], folderData['creator'])){
                            html += '        		<li onclick="javascript: elements.showCreateElementForm(\'' + folderId + '\');return false">' + filesystem.generateIcon('element', 'white') + '<span class="text">Add element</span></li>';
                            html += '                   <li onclick="javascript: folders.showCreateFolderForm(' + folderId + ');return false">' + filesystem.generateIcon('folder', 'white') + '<span class="text">Add folder</span></li>';
                            html += '        		<li onclick="shortcuts.showChooseShortcutTypeForm(' + folderId + ');">' + filesystem.generateIcon('shortcut', 'white') + '<span class="text">Add shortcut</span></li>';
                        }		  			
                        html += '        	</ul>';
                    }
                    if(showFileBrowser){
                        html += this.showFileBrowser(folderId);
                    }
        
        
            if(favorite){
                html += fav.show(User.userid);
            }
            html += '        </div>';	  			
            html += '    </div>';		  			
        html += '    </div>';
        return html;
    };


    this.showFileBrowser = function(folder){
        var subpath = './';
        var rightClick = true;
        var html = '<table cellspacing="0" class="filetable">';
       
        if(empty(folder)){
            folder = 1;
        }else{
            //userFolder
            var parentFolderData = [];
            if(folder === "2"){
                    //get userfolder
                    var userfolder = User.getAllData(User.userid)['homefolder'];

                    folder = userfolder;
                    parentFolderData['folder'] = 1;
            };

            if(folder === userfolder){
                    parentFolderData['folder'] = 1;
            };
        };

            //generate parent folder row
            if(!empty(folder) && (folder !== "1") && is_numeric(folder)){
                if(parentFolderData['folder'] !== "1")
                    parentFolderData = folders.getData(folder);                    
                html += '                        <tr class="greyHover" onclick="openFolder(' + parentFolderData['folder'] + '); return false;">';		  			
                html += '                            <td>' + filesystem.generateIcon('folder', 'grey') + '</td>';		  			
                html += '                            <td><a href="#">...</a></td>';		  			
                html += '                            <td></td>';		  			
                html += '                            <td></td>';		  			
                html += '                        </tr>';
            }

            var itemsInFolder = folders.getItems(folder);
            if(itemsInFolder !== null){
                $.each(itemsInFolder,function(key, value){
                    //generate row with folders and elements
                    if(value['type'] === "folder"){
                        var name = value['data']['name'];
                        //special folder handlers
                        if(folder === "3"){
                            name = groups.getTitle(value['data']['name']) + '\'s Groupfiles'; // value['data']['name']) because groupid = foldername
                        }
                        html += '                <tr class="greyHover" oncontextmenu="showMenu(\'folder' + value['data']['id'] + '\'); return false;">';
                        if(rightClick){
                            html += ''; //option to add rightclick function
                        }
                        html += '                    <td onclick="openFolder(' + value['data']['id'] + '); return false;">' + filesystem.generateIcon('folder', 'grey') + '</td>';
                        html += '                    <td onclick="openFolder(' + value['data']['id'] + '); return false;">' + name + '</td>';
                        html += '                    <td>';
                        html += item.showScoreButton('folder', value['data']['id']);
                        html += '                    </td>';
                        html += '                    <td>';
                        if(proofLogin()){
                            html += item.showItemSettings('folder', value['data']['id']);
                        }
                        html += '                    </td>';
                        html += '                </tr>';

                    };

                    if(value['type'] === "element"){
                        var title = value['data']['title'];
                        html += "                        <tr class=\"greyHover\" oncontextmenu=\"showMenu('element" + value['data']['id'] + "'); return false;\">";
                        html += "                           <td onclick=\"elements.open('" + value['data']['id'] + "'); return false;\">" + filesystem.generateIcon('element', 'grey') + "</td>";
                        html += "                           <td onclick=\"elements.open('" + value['data']['id'] + "'); return false;\">" + title + "</td>";
                        html += "                           <td>" + item.showScoreButton('element', value['data']['id']) + "</td>";
                        html += "                           <td>";
                        if(rightClick){
                            html += item.showItemSettings('element', value['data']['id']);
                        }
                        html += "                           </td>";
                        html += "                        </tr>";
                        if(rightClick){
                            html += ''; //option to add rightClick function, currently deactivated
                        }
                    }
                });
            }
            html += '</table>';
            return html;
    };
    
    this.openShareModal = function(type, typeId){
              		
              		var title;
              		var content;
              		var kickstarterURL;
              		var embedURL;
              		switch(type){
              			case 'file':
              				var fileTitle = files.fileIdToFileTitle(typeId);
              				title = 'Share "'+fileTitle+'"';
              				kickstarterURL = sourceURL+'/out/kickstarter/files/?id='+typeId;
              				embedURL = sourceURL+'/out/?file='+typeId; //should be the same like fileBrowserURL 
              			break;
              			case 'element':
              				var elementTitle = elements.elementIdToElementTitle(typeId);
              				title = 'Share "'+elementTitle+'"';
              				kickstarterURL = sourceURL+'/out/kickstarter/elements/?id='+typeId;
              				embedURL = sourceURL+'/out/?element='+typeId; //should be the same like fileBrowserURL 
              			break;
              		}
              		
              		var facebook = 'window.open(\'http://www.facebook.com/sharer/sharer.php?u='+kickstarterURL+'&t='+fileTitle+'\', \'facebook_share\', \'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no\');';
              		var twitter = 'window.open(\'http://www.twitter.com/share?url='+kickstarterURL+'\', \'twitter_share\', \'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no\');';
					var googleplus = "window.open('https://plus.google.com/share?url="+kickstarterURL+"','', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;";
              		
              		content = '<ul class="shareList">';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #facebook\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Facebook <img src="gfx/startPage/facebook.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #twitter\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Twitter <img src="gfx/startPage/twitter.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #googleplus\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Google+ <img src="gfx/startPage/googleplus.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #embed\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Embed Code <img src="gfx/startPage/wikipedia.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #url\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">URL <img src="gfx/startPage/wikipedia.png"></li>';
              		content += '</ul>';
              		
              		content += '<ul class="shareBox">';
              			content += '<li id="facebook"><center><a target="_blank" href="#" onclick="'+facebook+'" class="btn btn-success"><img src="gfx/startPage/facebook.png" height="20"> Click Here To Share</a></center></li>';
              			content += '<li id="url"><center><textarea>'+kickstarterURL+'</textarea></center>Just place the HTML code for your Filebrowser wherever<br> you want the Browser to appear on your site.</li>';
              			content += '<li id="embed"><center><textarea><iframe src="'+embedURL+'"></iframe></textarea></center>Just place the HTML code for your Filebrowser wherever<br> you want the Browser to appear on your site.</li>';
              			content += '<li id="googleplus"><center><a href="#" onclick="'+googleplus+'" class="btn btn-success"><img src="gfx/startPage/googleplus.png" height="20"> Click Here To Share</a></center></li>';
              			content += '<li id="twitter"><center><a href="#" onclick="'+twitter+'" class="btn btn-success"><img src="gfx/startPage/twitter.png" height="20"> Click Here To Share</a></center></li>';
              		content += '</ul>';
              		
              		
              		modal.create(title, content);
              	};
    this.openFolder = function(folderId){
        this.tabs.updateTabContent(1, this.generateFullFileBrowser(folderId));
    };
    
    this.createUFF = function(element, title, filename, privacy, callback){
        var result="";
	$.ajax({
            url:"api/files/uff/create/",
            async: false,  
            type: "POST",
            data: $.param({element : element, title: title, filename: filename})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.showCreateUFFForm = function(element){
        var formModal = new gui.modal();
        var elementData = elements.getData(element);
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
        field1['caption'] = 'Filename';
        field1['inputName'] = 'filename';
        field1['type'] = 'text';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = '';
        field2['inputName'] = 'privacy';
        field2['type'] = 'privacy';
        field2['value'] = elementData['privacy'];
        fieldArray[2] = field2;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Document';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The document has been added');
                $('.blueModal').remove();
                //filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            filesystem.createUFF(element, $('#createDocumentFormContainer #title').val(),$('#createDocumentFormContainer #filename').val(), $('#createDocumentFormContainer #privacyField :input').serialize(),callback);
        };
        formModal.init('Create Document', '<div id="createDocumentFormContainer"></div>', modalOptions);
        gui.createForm('#createDocumentFormContainer',fieldArray, options);
    };
    
    this.createElement = function(folder, title,  type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.showCreateElementForm = function(parent_folder){
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
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = '';
        field2['inputName'] = 'privacy';
        field2['type'] = 'privacy';
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The element has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            filesystem.createElement(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        formModal.init('Create Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.getFileData = function(file_id){
        
        return api.query('api/files/select/', { file_id : file_id});
        
    };
    this.getFileTitle = function(file_id){
        var fileData = this.getFileData(file_id);
        return fileData['title'];
    };
    this.downloadFile = function(fileId){
        $('#submitter').attr('src','out/download/?fileId='+fileId);
    };
    this.deleteFile = function(fileId){
        var fileData = filesystem.getFileData(fileId);
        var elementData = elements.getData(fileData['folder']);
        
        var callback = function(){
            $('.blueModal').hide();
            gui.alert('The file has been removed');
            console.log(elementData.title.substr(0,10));
            filesystem.tabs.updateTabContent(elementData.title.substr(0,10) ,this.generateFullFileBrowser(elementData['id']));
        };
        api.query('api/files/delete/', { file_id : fileId }, callback);
    };
    
    this.verifyFileRemoval = function(fileId){
        var confirmParameters = {};
        confirmParameters['title'] = 'Delete File';
        confirmParameters['text'] = 'Are you sure to delete this file?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            filesystem.deleteFile(fileId);
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
    
    this.show = function(){
        this.applicationVar.show();
    };
    
    this.readFile = function(file_id){
        return api.query('api/files/read/', { file_id : file_id});
    };
    
    this.generateIcon = function(fileType, color, id, onclick){
    var icons = {};
    var identifier;
    var onclicker;
    
    //general
    icons['home'] = 'home';
    icons['settings'] = 'gear';
    icons['folder'] = 'folder';
    icons['element'] = 'filesystem';
    icons['download'] = 'download';
    icons['link'] = 'external-link';
    icons['RSS'] = 'rss';
    icons['trash'] = 'trash';
    icons['dislike'] = 'dislike';
    icons['like'] = 'like';
    icons['minus'] = 'minus';
    icons['plus'] = 'plus';
    icons['up'] = 'arrow-up';
    icons['down'] = 'arrow-down';
    icons['left'] = 'arrow-left';
    icons['right'] = 'arrow-right';
    icons['chev_up'] = 'chevron-up';
    icons['chev_down'] = 'chevron-down';
    icons['chev_left'] = 'chevron-left';
    icons['chev_right'] = 'chevron-right';
    icons['small_symbols'] = 'small-symbols';
    icons['large_symbols'] = 'large-symbols';
    icons['list'] = 'list';
    icons['fav'] = 'heart';
    icons['clock'] = 'clock';
    icons['suggestion'] = 'star';
    icons['star'] = 'star';
    icons['playlist'] = 'playlist';
    icons['play'] = 'play';
    icons['location'] = 'location';
    icons['maximize'] = 'maximize';
    icons['minimize'] = 'minimize';
    icons['close'] = 'close';
    icons['question'] = 'question';
    icons['user'] = 'user';
    icons['group'] = 'group';
    
    
    //files
    icons['filesystem'] = 'filesystem';
    icons['undefined'] = 'question';
    icons['file'] = 'file';
    icons['shortcut'] = 'share-apple';
    icons['audio/mpeg'] = 'file';
    icons['audio/wav'] = 'file';
    icons['audio'] = 'file';
    icons['video/avi'] = 'file';
    icons['video/mp4'] = 'file';
    icons['video'] = 'file';
    icons['UFF'] = 'file';
    icons['text/plain'] = 'file';
    icons['text/x-c++'] = 'file';
    icons['application/pdf'] = 'file';
    icons['application/vnd.ms-office'] = 'file';
    icons['application/zip'] = 'file';
    
    //images
    icons['image/jpeg'] = 'image';
    icons['image/png'] = 'image';
    icons['image/tiff'] = 'image';
    icons['image/gif'] = 'image';
    icons['image'] = 'image';
    
    //3rd parties
    icons['rss'] = 'rss';
    icons['youtube'] = 'youtube';
    icons['wiki'] = 'wikipedia';
    icons['facebook'] = 'sc-facebook';
    icons['github'] = 'sc-github';
    icons['google'] = 'sc-google-plus';
    icons['instragram'] = 'sc-instagram';
    icons['linkedin'] = 'sc-linkedin';
    icons['twitter'] = 'sc-twitter';
    icons['vk'] = 'sc-vk';

    if(typeof fileType === 'undefined' || fileType === undefined || icons[fileType] === 'undefined'){
        icons[fileType] = 'archive'; //should be replaced with unknown file icon
    }
    if(color === 'grey' || color === 'gray'){
        color = 'icon'; //because uk spelling is 'grey' and in the usa it's spelled 'gray'
    }
    if(color === 'blue'){
        color = 'blue';
    }
    if(typeof color === 'undefined' || color === undefined || color !== 'white' && color !== 'icon' && color !== 'blue'){
        color = 'dark';
    }
    
    if(typeof id === 'undefined' || id === undefined || id === ''){
        identifier = '';
    } else {
        identifier = ' id="' + id + '"';
    }
    
    if(typeof onclick === 'undefined' || onclick === undefined || onclick === ''){
        onclicker = '';
    } else {
        onclicker = ' onclick="' + onclick + '"';
    }
    

    return '<span class="icon ' + color + '-' + icons[fileType] + '"' + identifier + onclicker + '></span>';
    

};

    this.getMiniFileBrowser = function(folder, element, level, showGrid, select){
        return api.query('api/item/loadMiniFileBrowser/', {folder: folder, element: element, level: level, showGrid: showGrid, select: select});
    };
    
    this.showReportFileForm = function(file_id){
        
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var captions = ['Copyrights', 'Human Rights', 'Other'];
        var type_ids = ['Copyrights', 'Human Rights', 'Other'];
        
        var field0 = [];
        field0['caption'] = 'Reason';
        field0['required'] = true;
        field0['inputName'] = 'reason';
        field0['type'] = 'dropdown';
        field0['values'] = type_ids;
        field0['captions'] = captions;
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Message';
        field1['inputName'] = 'message';
        field1['type'] = 'text';
        fieldArray[1] = field1;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Report File';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The message has been sent. We will process it as soon as possible.');
                $('.blueModal').remove();
            };
            filesystem.reportFile(file_id, $('#reportFileFormContainer #reason').val(), $('#reportFileFormContainer #message').val(), callback);
        };
        formModal.init('Report File', '<div id="reportFileFormContainer"></div>', modalOptions);
        gui.createForm('#reportFileFormContainer',fieldArray, options);
    };
    this.reportFile = function(file_id, reason, message, callback){
      api.query('api/files/report/', {file_id: file_id, reason:reason, message:message});
      callback();
    };
              	
    this.getPopularItemsArray = function(){
        var items = folders.getItems('pupularity');            
        var popArray = [];
            $.each(items, function(key, value){
                if(value['type'] === 'folder')
                    value['data']['title'] = value['data']['name'];
                popArray.push({type: value['type'], itemId: value['data']['id'], title: value['data']['title'], timestamp: ''});
            });
	return popArray;
    };
    this.getMyFiles = function(userid){
        var data = api.query('api/files/getMyFiles/', {userid : userid});
        var files = data['files'];
        var folder = data['folder'];
        var elements = data['elements'];
        var myFiles = [];
        
        if(typeof folder !== 'string'){
            $.each(folder, function(key, value){
                myFiles.push({type: 'folder', itemId: value['id'], title: value['name'], timestamp: value['timestamp']});
            });
        }
        
        if(typeof elements !== 'string'){
            $.each(elements, function(key, value){
                myFiles.push({type: 'element', itemId: value['id'], title: value['title'], timestamp: value['timestamp']});
            });
        }
        
        if(typeof files !== 'string'){
            $.each(files, function(key, value){
                myFiles.push({type: value['type'], itemId: value['id'], title: value['filename'], timestamp: value['timestamp']});
            });
        }
        
        return myFiles;
    };
};
//@param select folder/element
function loadMiniFileBrowser($target, folder, element, level, showGrid, select){
        $target.html(filesystem.getMiniFileBrowser(folder, element, level, showGrid, select));
}
