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
//        @author nicZem for transparency-everywhere.com
//        @author pabst for transparency-everywhere.com
        

var elements = new function(){
    this.open = function(element){
        filesystem.show();
        var elementData = this.getData(element);
        var elementAuthorData = this.getAuthorData(elementData['author']);
        var link = "./modules/reader/showfile.php?type=" + elementData['type'];
        var header = "<header class=\"white-header\">";
            header += filesystem.generateIcon(elementData['type'], 'grey');
            header += "<span class=\"elementtitle\">" + elementData['title'] + "</span>";
            header += '<span class="headerbuttons">'; 
                header += filesystem.generateIcon('list', 'grey');
                header += filesystem.generateIcon('list', 'blue');
                header += filesystem.generateIcon('small_symbols', 'grey');
                header += filesystem.generateIcon('small_symbols', 'blue');
                header += filesystem.generateIcon('large_symbols', 'grey');
                header += filesystem.generateIcon('large_symbols', 'blue');
                header += item.showScoreButton('element', element);
                header += '<a href=\"#\" id=\"settingsButton\" onclick=\"$(\'.elementSettings' + element + '\').slideToggle(\'slow\'); return false\" title=\"more...\">' + filesystem.generateIcon('settings', 'grey') + '</a>';  
            header += '</span>';
            header += '<div class="elementSettings dropdown"><ul class="elementSettings elementSettings' + element + '">';		 
                header += '<li onclick="filesystem.showCreateUFFForm(\'' + element + '\'); ">' + filesystem.generateIcon('file', 'white') + '&nbsp;Create an UFF</li>';
                header += '<li onclick="links.showCreateLinkForm(\'' + element + '\');">' + filesystem.generateIcon('link', 'white') + '&nbsp;Add a link</li>';		  			
                header += '<li onclick="filesystem.openUploadTab(\'' + element + '\');">' + filesystem.generateIcon('file', 'white') + '&nbsp;Upload files</li>';
            header += '</ul></div>';
        header += "</header>";
        var html = filesystem.generateLeftNav();
        html += '<div id="showElement" class="frameRight">';
        html += header;
        html += this.showFileList(element);
        filesystem.tabs.addTab(elementData['title'], '', html);
    };
    
    this.showFileList = function(element_id, grid){
        if(typeof grid === 'undefined'){
            grid = false; //if grid=true itemsettings and rightclickmenu will be disabled
        }
        var fileList = this.getFileList(element_id);
        var i = 0;
        var html = "";
        var link = "";
        var rightLink = "";
        var image = "";
        var date = "";
        var type = "";
        html += '<div id="attributes">';
        html += '<span class="icons"></span>';
        html += '<span class="title">Name</span>';
        html += '<span class="date">Date</span>';
        html += '<span class="size">Size</span>';
        html += '<span class="buttons"></span>';
        html += '</div>';
        html += '<ul>';
        html += '<span class="heading">Files</span>';
        $.each(fileList, function(key, value){
            if(value['type'] === 'file' && value['data']['type'] !== 'image' && value['data']['type'] !== 'image/jpeg' && value['data']['type'] !== 'image/png' && value['data']['type'] !== 'image/gif' && value['data']['type'] !== 'image/tiff') {
                //generate fileList for an element with an unordered list <ul>
                var data = value['data'];
                i++;
                if(value['type'] === 'link'){
                    type = "openLink"; // if type is link, use reader.openLink()
                }
                if(value['type'] === 'file'){
                    type = "openFile"; // if type is file, use reader.openFile()
                }
                date = new Date(data['timestamp']*1000).toString().substr(11, 5) + new Date(data['timestamp']*1000).toString().substr(4, 4) + new Date(data['timestamp']*1000).toString().substr(8, 2); //year + month + day
                image = filesystem.generateIcon(data['type']);
                html += '<li data-id="' + data['id'] + '" data-type="' + data['type'] + '" data-title="' + data['title'] + '" data-date="' + date + '" data-size="' + data['size'] + '">';
                    html += '<span class="icons"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + image + '</a></span>';
                    html += '<span class="title"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + data['title'] + '</a></span>';
                    html += '<span class="date">' + date + '</span>';
                    html += '<span class="size">' + Math.round(data['size']/1024) + ' kB</span>';
                    html += '<span class="buttons">'
                        html += item.showScoreButton(value['type'], data['id']);
                        if(data['download']){
                            html += '<a href="./out/download/?fileId=' + data['id'] + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download', 'grey') + '</a>';
                        }
                        if(!grid){
                            html += item.showItemSettings(value['type'], data['id']);
                        }
                    html += '</span>';
                html += '</li>';
                if(!grid){
                    html += ''; //hier muss die rightClick function noch eingebunden werden.
                }
            }
        });
        html += '<span class="heading">Images</span>';
        $.each(fileList, function(key, value){
            if(value['type'] === 'file' && (value['data']['type'] === 'image' || value['data']['type'] === 'image/jpeg' || value['data']['type'] === 'image/png' || value['data']['type'] === 'image/gif' || value['data']['type'] === 'image/tiff')) {
                //generate fileList for an element with an unordered list <ul>
                var data = value['data'];
                i++;
                if(value['type'] === 'link'){
                    type = "openLink"; // if type is link, use reader.openLink()
                }
                if(value['type'] === 'file'){
                    type = "openFile"; // if type is file, use reader.openFile()
                }
                date = new Date(data['timestamp']*1000).toString().substr(11, 5) + new Date(data['timestamp']*1000).toString().substr(4, 4) + new Date(data['timestamp']*1000).toString().substr(8, 2); //year + month + day
                image = filesystem.generateIcon(data['type']);
                html += '<li data-id="' + data['id'] + '" data-type="' + data['type'] + '" data-title="' + data['title'] + '" data-date="' + date + '" data-size="' + data['size'] + '">';
                    html += '<span class="icons"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + image + '</a></span>';
                    html += '<span class="title"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + data['title'] + '</a></span>';
                    html += '<span class="date">' + date + '</span>';
                    html += '<span class="size">' + Math.round(data['size']/1024) + ' kB</span>';
                    html += '<span class="buttons">'
                        html += item.showScoreButton(value['type'], data['id']);
                        if(data['download']){
                            html += '<a href="./out/download/?fileId=' + data['id'] + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download', 'grey') + '</a>';
                        }
                        if(!grid){
                            html += item.showItemSettings(value['type'], data['id']);
                        }
                    html += '</span>';
                html += '</li>';
                if(!grid){
                    html += ''; //hier muss die rightClick function noch eingebunden werden.
                }
            }
        });
        html += '<span class="heading">Links</span>';
        $.each(fileList, function(key, value){
            if(value['type'] === 'link') {
                //generate fileList for an element with an unordered list <ul>
                var data = value['data'];
                i++;
                if(value['type'] === 'link'){
                    type = "openLink"; // if type is link, use reader.openLink()
                }
                if(value['type'] === 'file'){
                    type = "openFile"; // if type is file, use reader.openFile()
                }
                date = new Date(data['timestamp']*1000).toString().substr(11, 5) + new Date(data['timestamp']*1000).toString().substr(4, 4) + new Date(data['timestamp']*1000).toString().substr(8, 2); //year + month + day
                image = filesystem.generateIcon(data['type']);
                html += '<li data-id="' + data['id'] + '" data-type="' + data['type'] + '" data-title="' + data['title'] + '" data-date="' + date + '" data-size="' + data['size'] + '">';
                    html += '<span class="icons"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + image + '</a></span>';
                    html += '<span class="title"><a href="./out/?file=' + data['id'] + '" onclick="reader.' + type + '(\'' + data['id'] + '\'); return false">' + data['title'] + '</a></span>';
                    html += '<span class="date">' + date + '</span>';
                    html += '<span class="size"></span>';
                    html += '<span class="buttons">'
                        html += item.showScoreButton(value['type'], data['id']);
                        if(data['download']){
                            html += '<a href="./out/download/?fileId=' + data['id'] + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download', 'grey') + '</a>';
                        }
                        if(!grid){
                            html += item.showItemSettings(value['type'], data['id']);
                        }
                    html += '</span>';
                html += '</li>';
                if(!grid){
                    html += ''; //hier muss die rightClick function noch eingebunden werden.
                }
            }
        });
        html += '</ul>';
        if(i === 0){
            html += '<ul><li>';
            html += '<span>';
            html += 'This Element is empty.';
            html += '</span>';
            html += '</li></ul>';
        }
        return html;
    };
    
    this.getFileList = function(element_id){
        return api.query('api/elements/getFileList/',{element_id : element_id});
    };
    
    this.getData = function(element_id){
        return api.query('api/elements/select/',{element_id : element_id});
    };
    
    this.getAuthorData = function(user_id){
        return api.query('api/elements/getAuthorData/',{user_id : user_id});
    };
    
    this.getFileNumbers = function(element_id){
        return api.query('api/elements/getFileNumbers/',{element_id : element_id});
    };
    
    this.create = function(folder, title,  type, privacy, callback){
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
        field0['required'] = true;
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
                filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(parent_folder));
            };
            elements.create(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        formModal.init('Create Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.update = function(element_id, folder, title, type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/update/",
            async: false,  
            type: "POST",
            data: $.param({element_id:element_id,folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    }
    
    this.showUpdateElementForm = function(element){
        var formModal = new gui.modal();
        var elementData = elements.getData(element);
        
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
        field0['value'] = elementData['title'];
        fieldArray[0] = field0;
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        field1['preselected'] = elementData['type'];
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = '';
        field2['inputName'] = 'privacy';
        field2['type'] = 'privacy';
        field2['value'] = elementData['privacy'];
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The element has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(elementData.folder));
            };
            elements.update(element, elementData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        formModal.init('Update Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.delete = function(elementId, callback){
        var result="";
	$.ajax({
            url:"api/elements/delete/",
            async: false,  
            type: "POST",
            data: {element_id : elementId},
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    
    this.verifyRemoval = function(elementId){
        var confirmParameters = {};
        var elementData = elements.getData(elementId);
        confirmParameters['title'] = 'Delete Element';
        confirmParameters['text'] = 'Are you sure to delete this element?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            elements.delete(elementId);
            gui.alert('The element has been deleted');
            filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(elementData.folder));
            
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
    
    this.elementIdToElementTitle = function(elementId){
            return elements.getData(elementId)['title'];
    };
              	
};
