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
        

var elements = new function(){
    
    this.open = function(element){
        filesystem.show();
        var elementData = this.getData(element);
        var elementAuthorData = this.getAuthorData(elementData['author']);
        var link = "./modules/reader/showfile.php?type=" + elementData['type'];
        var html = filesystem.generateLeftNav();
        html += '    <div id="showElement" class="frameRight">';
        html += '        <h2 style="margin-left: 5%; margin-bottom:0px; margin-top:5%;">';
        html += '            ' + htmlspecialchars(elementData['title']) + '&nbsp;<i class="icon-info-sign" onclick="$(\'.elementInfo(' + elementData['id'] + '\').slideDown();"></i>';
        html += '        </h2>';
        html += '        <div class="elementInfo' + elementData['id'] + ' hidden">';
        html += '    <table width="100%" class="fileBox" cellspacing="0">';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td width="110px">Element-Type:</td>';
        html += '            <td>' + elementData['type'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td width="110px">Author:</td>';
        html += '            <td>' + elementData['creator'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>Title:</td>';
        html += '            <td>' + elementData['name'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td>Year:</td>';
        html += '            <td>' + elementData['year'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>Original Title:</td>';
        html += '            <td>' + elementData['originalTitle'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td>Language:</td>';
        html += '            <td>' + elementData['language'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>License:</td>';
        html += '            <td>' + elementData['license'] + '</td>';
        html += '        </tr>';
        html += '    </table>';
        html += '    <div style="display:none; float:left; width:40%; margin-top: 3%; background: #c9c9c9; height: 250px;">';
        html += '    </div>';
        html += '    </div>';
        html += '    <div style=" clear: left;margin-left: 5%;">';
        html += '        <a target="_blank" href="http://www.amazon.de/gp/search?ie=UTF8&camp=1638&creative=6742&index=aps&keywords=' + htmlentities(elementData['title']) + '%20' + elementData['creator'] + '&linkCode=ur2&tag=universeos-21">find on amazon</a><img src="http://www.assoc-amazon.de/e/ir?t=universeos-21&l=ur2&o=3" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
        html += '    </div>';
        html += '    <h3 style="margin-left:5%; margin-bottom:-10px;"><i class="icon-file"></i>&nbsp;Files</h3>';
        html += '        <table cellspacing="0" class="filetable" style="width: 90%; margin-left: 5%; border: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9;">';
        html += '            <tr class="grayBar" height="35">';
        html += '                <td></td>';
        html += '                <td>Title</td>';
        html += '                <td></td>';
        html += '                <td></td>';
        html += '                <td></td>';
        html += '            </tr>';
//            html += elements.showFileList(elementData['id']); //muss ich noch machen :(
        html += '        </table>';
        html += '    <center style="margin-top: 20px; margin-bottom: 20px;">';
        if(proofLogin()){
            html += '    	<a class="btn btn-info" href="#" onclick="filesystem.showCreateUFFForm(\'' + element + '\'); " target="submitter"><i class="icon-file icon-white"></i> Create Document</a>';
            html += '        <a href="#" onclick="filesystem.openUploadTab(\'' + element + '\');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;Upload File</a>';
            html += '        &nbsp;<a href="javascript: links.showCreateLinkForm(\'' + element + '\');" class="btn btn-info"><i class="icon white-link"></i>&nbsp;Add Link</a>';
        }
        html += '    </center>';
        html += '    <hr>';
        html += '    <div>';
//            html += comments.show(elementData['id']); //nic macht function in js fertig
        html += '    </div>';
        html += '</div>';
        filesystem.tabs.addTab(elementData['title'], '', html);
    };
    
    this.showFileList = function(element_id, grid){
        if(typeof grid === 'undefined'){
            grid = false; //if grid=true itemsettings and rightclickmenu will be disabled
        }
        var fileList = this.getFileList(element_id);
        var i = 0;
        var html;
        var link;
        var rightLink;
        var image;
        $.each(fileList, function(key, value){
            var data = value['data'];
            if(value['type'] === 'file'){
                i++;
                if(data['type'] === "audio/mpeg"){
                    link = "openFile('" + data['type'] + "', '" + data['id'] + "', '" + data['title'] + "')";
                    rightLink = "startPlayer('file', '" + data['id'] + "')";
                }
                else if(data['type'] === "video/mp4"){
                    link = "openFile('video', '" + data['id'] + "', '" + data['title'] + "');";
                    rightLink = "reader.tabs.addTab('See " + data['title'] + "', '',gui.loadPage('./modules/reader/player.php?id='" + data['id'] + "')); return false";
                }
                else if(data['type'] === "UFF"){
                    link = "openFile('" + data['type'] + "', '" + data['id'] + "', '" + data['title'] + "')";
                }
                else if(data['type'] === "text/plain" || data['type'] === "application/pdf" || data['type'] === "text/x-c++"){
                    link = "openFile('document', '" + data['id'] + "', '" + data['title'] + "');";
                }
                else if(data['type'] === "image/jpeg" || data['type'] === "image/png" || data['type'] === "image/gif"){
                    //if a image is opened the tab is not named like the file
                    //it is named like the parent element, because images are
                    //shown in a gallery with all the images listed in the parent
                    //element
                    var elementData = this.getData(data['folder']);
                    link = "openFile('image', '" + data['id'] + "', '" + elementData['title'] + "');";
                }
                image = filesystem.generateIcon(data['type']);
                html += '<tr class="strippedRow file_' + data['id'] + '" oncontextmenu="showMenu(\'file' + data['id'] + '\'); return false;" height="40px">';
                html += '<td width="30px">&nbsp;' + image + '</td>';
                html += '<td><a href="./out/?file=' + data['id'] + '" onclick="' + link + ' return false">' + data['title'] + '</a></td>';
                html += '<td width="80" align="right">';
                html += item.getScore('file', data['id']);
                html += '</td>';
                html += '<td width="50">';
                if(data['download']){
                    html += '<a href="./out/download/?fileId=' + data['id'] + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download') + '</a>';
                }
                if(!grid){
                    html += item.showItemSettings(data['type'], data['id']);
                }
                html += '</td>';
                html += '</tr>';
                if(!grid){
                    html += ''; //hier muss die rightClick function noch eingebunden werden.
                }
            }
            if(value['type'] === 'link'){
                i++;
                if(data['type'] === "youTube"){
                    link = "openFile('" + data['type'] + "', '" + data['id'] + "', '" + data['title'] + "')";
                }
                else if(data['type'] === "audio/mp3"){
                    rightLink = "startPlayer('file', '" + data['id'] + "')";
                }
                else if(data['type'] === "RSS"){
                    link = "openFile('" + data['type'] + "', '" + data['id'] + "', '" + data['title'] + "')";
                }
                image = filesystem.generateIcon(data['type']);
                html += '<tr class="strippedRow file_' + data['id'] + '" oncontextmenu="showMenu(\'file' + data['id'] + '\'); return false;" height="40px">';
                html += '<td width="30px">&nbsp;' + image + '</td>';
                html += '<td><a href="./out/?file=' + data['id'] + '" onclick="' + link + ' return false">' + data['title'] + '</a></td>';
                html += '<td width="80" align="right">';
                html += item.getScore('link', data['id']);
                html += '</td>';
                html += '<td width="50">';
                if(data['download']){
                    html += '<a href="./out/download/?fileId=' + data['id'] + '" target="submitter" class="btn btn-mini" title="download file">' + filesystem.generateIcon('download') + '</a>';
                }
                if(!grid){
                    html += item.showItemSettings('link', data['id']);
                }
                html += '</td>';
                html += '</tr>';
                if(!grid){
                    html += ''; //hier muss die rightClick function noch eingebunden werden.
                }
            }
        });
        if(i === 0){
            html += '<tr class="strippedRow" style="height: 20px;">';
            html += '<td colspan="3">';
            html += 'This Element is empty.';
            html += '</td>';
            html += '</tr>';
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
            elements.create(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
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
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            };
            elements.update(element, elementData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', elementData['privacy'], true);
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
            filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            
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
