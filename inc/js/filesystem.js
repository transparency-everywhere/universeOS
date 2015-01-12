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


var filesystem =  new function() {
    this.init = function(){
        
        var html = '<div id="fileBrowserFrame"></div>';
        
        
        
        this.applicationVar = new application('filesystem');
        this.applicationVar.create('Filesystem', 'html', html,{width: 6, height:  5, top: 0, left: 3});
			  			
        
	this.tabs = new tabs('#fileBrowserFrame');
        this.tabs.init();
	this.tabs.addTab('universe', '',gui.loadPage('modules/filesystem/fileBrowser.php'));
			  	
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
        this.tabs.updateTabContent(1, gui.loadPage('modules/filesystem/fileBrowser.php?reload=1&folder='+folderId));
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
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Document';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The document has been added');
                $('.blueModal').remove();
                //filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            filesystem.createUFF(element, $('#createDocumentFormContainer #title').val(),$('#createDocumentFormContainer #filename').val(), $('#createDocumentFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
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
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            filesystem.createElement(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Create Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.getFileData = function(file_id){
        
        return api.query('api/files/select/', { file_id : file_id});
        
    };
    
    this.openUploadTab = function(element){
        filesystem.applicationVar.show();
        filesystem.tabs.addTab('Upload', '',gui.loadPage('modules/filesystem/upload.php?element='+element));
    }
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
            filesystem.tabs.updateTabContent(elementData.title.substr(0,10) ,gui.loadPage('modules/filesystem/showElement.php?element='+elementData['id']+'&reload=1'));
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
};

