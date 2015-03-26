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


//class is required because gui.loadScript is used to load reader.js, chat.js etc
var gui = new function(){
    this.initWysiwyg = false; //is used in generateField and createForm to check if wysiwyg needs to be initialized
    this.initializeUploadify = false;
    this.generateList = function(values, captions, preselected, caption){
        var html = '';
        $.each(values, function( index, value ) {
            var selected;
            if(typeof preselected !== 'undefined'){
                if(preselected == value)
                    {selected = 'selected="selected"';}
                else
                    {selected = '';}
            }
            html += '<option>'+caption+'</option>';
            html += '<option value="' + value + '" '+selected+'>' + captions[index] + '</option>';
        });
        return html;
    };
    this.toggleAdvanced = function(){
        if($('.advanced').hasClass('open')){
            $('.advanced .advancedField').hide();
            $('.advanced').removeClass('open');
        }else{
            $('.advanced .advancedField').show();
            $('.advanced').addClass('open');
        }
    };
    this.generateField = function(fieldData, tr_class){
        var requiredClass = '';
        if(fieldData['required'] === true){
            requiredClass = 'requiredInput';
        }
        
        if((typeof fieldData['value'] === 'undefined')||(fieldData['value'] == 'html')){
            fieldData['value'] = '';
        }else{
            var temp;
            temp = String(fieldData['value']);
            fieldData['value'] = temp.replace(/\"/g, '&quot;');
        }
        
        if(typeof fieldData['appendix'] === 'undefined'){
            fieldData['appendix'] = '';
        }
            
        var mainHTML = '';
        mainHTML += '<tr class='+tr_class+'>';
        
                    //caption
                    switch(fieldData['type']){
                        case'text':
                           
                            break;   
                        case'wysiwyg':
                            break;
                        case'checkbox':
                            mainHTML += '<td colspan="3">' + fieldData.caption + '</td>';
                            break;
                        case'dropdown':
                            break;
                        case'password':
                            mainHTML += '<td class="caption">' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        case'button':
                            mainHTML += '<td class="caption">' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        default:
                            //mainHTML += '';
                            break;
                         
                    }

        mainHTML += '</tr>';
        mainHTML += '<tr>';
                    //body
                    
                    
                    if(typeof fieldData['caption_position'] === 'undefined'){
                        fieldData['caption_position'] = 'left';
                    }
                    
                    var colspan = '3';
                    var textBeforeInput;
                    switch(fieldData['caption_position']){
                        
                        case 'top':
                            colspan = '3';
                            textBeforeInput = fieldData.caption;
                            break;
                        case 'left':
                            colspan = '2';
                            textBeforeInput = '';
                            mainHTML += '<td>'+fieldData.caption+'</td>';
                            break;
                        case 'placeholder':
                            colspan = '3';
                            textBeforeInput = '';
                            //mainHTML += '<td>'+fieldData.caption+'</td>';
                            break;
                    }
                    
                    switch(fieldData['type']){
                        
                        
                        
                        case 'text':
                            if(!fieldData['value']){
                                fieldData['value'] = '';
                            }
                            
                            var disabled = '';
                            if(typeof fieldData['disabled'] != 'undefined'){
                                if(fieldData['disabled']){
                                    disabled = 'disabled="disabled"';
                                }else{
                                    disabled = '';
                                }
                            }
                            
                            
                            
                            mainHTML += '<td colspan="'+colspan+'">'+textBeforeInput+'<input type="text" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" value="' + fieldData['value'] + '" placeholder="'+fieldData.caption+'" style="width:100%;" class="'+requiredClass+'" '+disabled+'/></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                            
                        case 'textarea':
                            if(!fieldData['value']){
                                fieldData['value'] = '';
                            }
                            mainHTML += '<td colspan="3"><textarea name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" class="'+requiredClass+'">'+fieldData['value']+'</textarea></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                            
                            
                        case 'password':
                            mainHTML += '<td><input type="password" name="' + fieldData.inputName + '" placeholder="'+fieldData.caption+'" id="' + fieldData.inputName + '" class="'+requiredClass+'"/></td>';
                            break;
                            
                            
                            
                        case 'checkbox':
                            var checked;
                            if(fieldData.checked === true){
                                checked = 'checked="checked"';
                            }else{
                                checked = '';
                            }
                            mainHTML += '<td><input type="checkbox" value="' + fieldData.value + '" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" class="'+requiredClass+'" '+ checked +'/></td>';
                            break;
                            
                            
                            
                        case 'radio':
                            mainHTML += '<td><input type="text" name="' + fieldData.inputName + '" class="'+requiredClass+'" id="' + fieldData.inputName + '"/></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                            
                            
                        case 'dropdown':
                            
                            mainHTML += '<td colspan="3">';
                                mainHTML += '<span class="custom-dropdown custom-dropdown--white">';
                                    mainHTML += '<select class="custom-dropdown__select custom-dropdown__select--white '+requiredClass+'" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '">';
                                        
                                    mainHTML += gui.createDropdown(fieldData.values, fieldData.captions, fieldData.preselected, fieldData.caption);
                                    mainHTML += ' </select>';
                                mainHTML += '</span>';
                            mainHTML += '</td>';
                            break;
                            
                            
                            
                        case 'space':
                            mainHTML += '<td></td>';
                            break;
                            
                            
                            
                        case 'wysiwyg':
                            gui.initWysiwyg = true;
                            mainHTML += '<td colspan="3"><div class="wysiwyg '+requiredClass+'" id="' + fieldData.inputName + '" contenteditable="true">'+fieldData.value+'</div></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                            
                            
                        case 'button':
                            mainHTML += '<td colspan="1"><a href="#" onclick="'+fieldData.actionFunction+'" class="btn btn-default">'+fieldData.value+'</a></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                            
                            
                        case 'file':
                            gui.initializeUploadify = true;
                            
                            var fileGallery = '';
                            var fieldValue = '';
                            if(fieldData.value){
                                fieldValue = fieldData.value;
                                fileGallery = gui.generateFileGallery(fieldData.value, fieldData.inputName);
                            }
                            mainHTML += '<td colspan="1">'+fileGallery+'<ul id="' + fieldData.inputName + '_fileList"></ul><input type="hidden" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" value="'+fieldValue+'"><div id="' + fieldData.inputName + '_fileField"></div></td><td>'+fieldData['appendix']+'</td>';
                            break;
                            
                        //privacy settings
                        case 'privacy':
                            if(typeof fieldData.value === 'undefined'){
                                fieldData.value = 'p';
                            }
                            mainHTML += '<td colspan="1"><div id="privacyField">'+privacy.show(fieldData.value,'true')+'</div></td>';
                            break;
                            
                        //minifilebrowser
                        case 'folder':
                            break;
                        case 'element':
                            break;
                            
                        case 'html':
                            mainHTML += '<td colspan="'+colspan+'">'+textBeforeInput+fieldData.value+'</td><td>'+fieldData['appendix']+'</td>';
                            break;
                    }
        mainHTML += '</tr>'; 
        return mainHTML;
    };
    this.createForm = function($selector, fields, options){
        var mainHTML = '';
        var advancedHTML = '';
        
        //reset init var
        this.initWysiwyg = false;
        $.each(fields, function(index, fieldData){
            if((typeof fieldData['advanced'] === 'undefined')||fieldData['advanced'] === false){
                mainHTML += gui.generateField(fieldData, '');
            }
            else if(fieldData['advanced'] === true){
                advancedHTML += gui.generateField(fieldData, 'advancedField');
            }
           
        });


        var html =  '<form id="dynForm" class="dynForm">';
        if(options['headline'].length > 0)
            html +=  '<h1>' + options['headline'] + '</h1>';
        html +=  '<table class="gui_form_table">';
        html += mainHTML;
        html += '<tr><td colspan="3">';
        
        if(advancedHTML.length > 0){
            html += '<hr>';
            html += '<table class="advanced">';
            html += '<tr><td colspan="3"><a href="#" class="toggle" onclick="gui.toggleAdvanced();" style="font-size: 20px;">Advanced Settings&nbsp;<i class="icon icon-chevron-down""></i><i class="icon icon-chevron-up""></i></a></td></tr>';
            
            html += advancedHTML;
            html += '</table>';
        }
        
            if(options['noButtons'] != true){
                html += '</td></tr>';
                html += '<tr><td colspan="3"><a href="panel.html" onclick="" class="btn btn-primary" style="margin-right:15px;">Back</a><input type="submit" value="' + options['buttonTitle'] + '" name="submit" id="submitButton" class="btn btn-success"></td></tr>';
                html += '</form>';
            }
        
        $($selector).html(html);
        
        //iniitialize onchange events
        $.each(fields, function(index, fieldData){
            if(typeof fieldData['onChange'] === 'function'){
                $('#dynForm #'+fieldData['inputName']).change(function(){
                    fieldData['onChange']();
                });
            }
           
        });
        
        
        if (typeof options['action'] == 'function'){
            $('#dynForm').submit(function(e){
                e.preventDefault();
                if(gui.checkRequired())
                    options['action']();
                else
                    gui.requiredAlert();
            });
        }
        if(this.initWysiwyg){
            $('.wysiwyg').ckeditor(function(){}, {allowedContent: true});
        }
        if(this.initializeUploadify){
            $.each(fields, function(index, fieldData){
                if(fieldData['type'] == 'file'){
                    gui.initUploadify('#'+fieldData['inputName']+'_fileField',fieldData['inputName']);
                }
            });
        }
        return html;
    };
    this.createDropdown = function(values, captions, preselected, caption){
        var html = '';
        html = '<option>'+caption+'</option>';
        $.each(values, function( index, value ) {
            var selected;
            if(typeof preselected !== 'undefined'){
                if(preselected == value)
                    {selected = 'selected="selected"';}
                else
                    {selected = '';}
            }
            html += '<option value="' + value + '" '+selected+'>' + captions[index] + '</option>';
        });
        return html;
    };
    this.createOverview = function($selector, ids, captions, actions, title){
        
        var html;
        html = '<h3 class="pull-left">'+title+'</h3>';
        if((typeof actions['add'] !== 'undefined') ||(typeof actions[0] !== 'undefined')){
            if(typeof actions[0] === 'object'){
                $.each(actions, function(index, value){
                   html += '<a href="#" onclick="'+value['onclick']+'" class="btn btn-success pull-right">'+value['caption']+'</a>'; 
                });
            }else{
                html += '<a href="#" onclick="'+actions['add']['onclick']+'" class="btn btn-success pull-right">'+actions['add']['caption']+'</a>';
            }
        }
        html += '<table class="table table-striped">';
        $.each(ids, function( index, value ) {
            
            var actionHTML = '';
            
            if(typeof actions['update'] !== 'undefined'){
                actionHTML += '<a href="#" class="btn btn-default" onclick="'+actions['update']['onclick']+'('+value+')'+'"><span class="icon icon-pencil"></span></a>';
            }
            if(typeof actions['delete'] !== 'undefined'){
                actionHTML += '<a href="#" class="btn btn-default" onclick="'+actions['delete']['onclick']+'('+value+')'+'"><span class="icon icon-minus"></span></a>';
            }
            if(actionHTML.length > 0){
                actionHTML = '<div class="btn-group">'+actionHTML+'</div>';
            }
            
            if(!empty(captions[index])){
                html += '<tr>';
                    html += '<td>'+captions[index]+'</td>';
                    html += '<td align="right">'+actionHTML+'</td>';
                html += '</tr>';
            }
        });
        
        html += '</table>';
        $($selector).html(html);
        return true;
        
    };
    this.verifyRemoval = function(type, link){
        
	Check = confirm("Are you sure to delete this "+type+" ?");
	if (Check == true){
            $.ajax({
                  url: link,
                  type: "GET",
                  async: false,
                  success: function(data) {
                                    alert('The '+type+' has been deleted');
                                    window.location.href = window.location.href;
                  }
            });
	}
    };
    this.loadScript = function(url){
        // get some kind of XMLHttpRequest
        var xhrObj = new XMLHttpRequest();
        // open and send a synchronous request
        xhrObj.open('GET', url, false);
        xhrObj.send('');
        // add the returned content to a newly created script tag
        var se = document.createElement('script');
        se.type = "text/javascript";
        se.text = xhrObj.responseText;
        document.getElementsByTagName('head')[0].appendChild(se);
        
    };
    
    this.getRasterWidth = function(numberOfFields){
        var gutterWidth = $('#bodywrap').width()/49;
        return ((numberOfFields*3)+(numberOfFields-1))*gutterWidth;
    };
    this.getRasterHeight = function(numberOfFields){
        var gutterHeight = $('#bodywrap').height()/49;
        return ((numberOfFields*3)+(numberOfFields-1))*gutterHeight;
    };
    this.getRasterMarginLeft = function(numberOfFields){
        var gutterWidth = $('#bodywrap').width()/49;
        return ((numberOfFields*3)+(numberOfFields+1))*gutterWidth;
    };
    this.getRasterMarginTop = function(numberOfFields){
        var gutterHeight = $('#bodywrap').height()/49;
        return ((numberOfFields*3)+(numberOfFields+1))*gutterHeight;
        
    };
    
    this.checkRequired = function(){
        var state = true;
                $('#dynForm .requiredInput').each(function(){
                    var $element = $(this);
                    if($element.is('input[type=text]')){
                        if(empty($element.val())){
                            state = false;
                        }
                    }
                });
        return state;
    };
    this.requiredAlert = function(){
        alert('asd');
    };
    this.modal = function(){
        this.html;
        this.init = function (title, content, options) {
			    	this.html = '';
			    	this.html += '<div class="blueModal container">';
	            		this.html += '<header>';
	            			this.html += title;
	            			this.html += '<a class="modalClose" onclick="$(\'.blueModal\').remove();"><span class="icon white-close"></span></a>';
	            		this.html += '</header>';
	            		this.html += '<div class="content">';
	            		this.html += content;
	            		this.html += '</div>';
	            		this.html += '<footer>';
	            		
	                 		this.html += '<a href="#" onclick="$(\'.blueModal\').remove(); return false;" class="antiButton pull-left">Close</a>';
	                 		if(typeof options['action'] !== 'undefined'){
	                			this.html += '<a href="#" id="action" class="button pull-right">&nbsp;&nbsp;'+options['buttonTitle']+'&nbsp;&nbsp;</a>';
	                 		}
	            		
	            		this.html += '</footer>';
	            		
                                this.html += '</div>';
                                $('.blueModal').remove();
                                $('#popper').append(this.html);

                                if(typeof options['action'] !== 'undefined'){
                                        $('.blueModal #action').click(function(){
                                            if(gui.checkRequired())
                                                options['action']();
                                            else
                                                gui.requiredAlert();
                                        });
                                }
	};
    };
    
    this.shorten = function(text, maxLength){
        var ret = text;
        if (ret.length > maxLength) {
            ret = ret.substr(0,maxLength-3) + "...";
        }
        return ret;
    };
    
    this.generateId = function(){
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    };
    
    this.alert = function(message, title, type){
        var alertClass;
        switch(type){
            default:
                alertClass = 'alert-info';
                break;
        }
        $('#alerter').append('<div class="alert '+alertClass+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>');
        $('.alert').delay(8000).fadeOut(function(){
	    $(this).remove();
	});
    };
    this.confirm = function(parameters){
        var confirmID = gui.generateId();
        var confirmClass = confirmID+' ';
        var buttons = '';
        
            switch(parameters['type']){
                default:
                    confirmClass += 'alert-info';
                    break;
            }
        
        if(typeof parameters['cancelButtonTitle'] !== 'undefined'){
            buttons += '<button type="button" class="btn btn-danger cancelButton">'+parameters['cancelButtonTitle']+'</button>';
        }
        if(typeof parameters['submitButtonTitle'] !== 'undefined'){
            buttons += '<button type="button" class="btn btn-default submitButton">'+parameters['submitButtonTitle']+'</button>';
        }
        
        
        var html =  '<div class="alert '+confirmClass+' alert-dismissible fade in" role="alert">'+
                    '      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
                    '      <h4>'+parameters.title+'</h4>'+
                    '      <p>'+parameters.text+'</p>'+
                    '      <p>'+buttons+'</p>'+
                    '</div>';
            
            
        $('#alerter').append(html);
        
            $('.'+confirmID+' .btn-default').click(function(){
                $('.'+confirmID).remove();
                if(typeof parameters['submitFunction'] === 'function'){
                    parameters['submitFunction']();
                }
            });
        
            $('.'+confirmID+' .btn-danger').click(function(){
                $('.'+confirmID).remove();
                if(typeof parameters['cancelFunction'] === 'function'){
                        parameters['cancelFunction']();
                }
            });
	

    };
    
    this.initUploadify = function($selector, inputName){
              
	            $($selector).uploadify({
	                    'formData'     : {
	                            'timestamp' : 'timestamp',
	                            'token'     : ''
	                    },
	                    'swf'      : 'inc/plugins/uploadify/uploadify.swf',
	                    'uploader' : 'api.php?action=uploadFile',
				        'onUploadSuccess' : function(file, data, response) {
				        	
				        	if(response){
                                                        $('#'+inputName+'_fileList').append('<li class="file_'+data+'">'+files.idToTitle(data)+'<a href="#" onclick="files.removeFileFromUploader(\''+inputName+'\',\''+data+'\');">x</a></li>')
                                                        $('#'+inputName).val($('#'+inputName).val()+data+',');
                                                    
				        	}
				        },
	                    'onUploadError' : function(file, errorCode, errorMsg, errorString) {
	                        alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
	                    }
	            });
               
    };
    this.createPanel = function($selector, ids, captions, actions, title){
        
        var html;
        
        
        var html="";
        html += "<div class=\"panel\">";
        html += "<div class=\"panel-heading\">";
        html += "    <span class=\"icon icon-navicon\"><\/span>"+title;
        html += "    <div class=\"pull-right action-buttons\">";
        html += "        <div class=\"btn-group pull-right\">";
        html += "            <button type=\"button\" class=\"btn btn-default btn-xs dropdown-toggle\" data-toggle=\"dropdown\">";
        html += "                <span class=\"icon icon-gear\" style=\"margin-right: 0px;\"><\/span>";
        html += "            <\/button>";

        if((typeof actions['add'] !== 'undefined') ||(typeof actions[0] !== 'undefined')){
            html += "<ul class=\"dropdown-menu slidedown\">";
            if(typeof actions[0] === 'object'){
                $.each(actions, function(index, value){
                   
                   html += "<li><a href=\"#\" onclick=\""+value['onclick']+"\"><span class=\"icon icon-pencil\"><\/span>"+value['caption']+"\<\/a><\/li>";
                });
            }else{
                html += "<li><a href=\"#\" onclick=\""+actions['add']['onclick']+"\"><span class=\"icon icon-pencil\"><\/span>"+actions['add']['caption']+"\<\/a><\/li>";
            }
            html += "<\/ul>";
        }
        html += "         <\/div>";
        html += "     <\/div>";
        html += " <\/div>";
        html += " <div class=\"panel-body\">";
        html += "     <ul class=\"list-group panel_"+title+"\">";

        
        var numberOfItems = ids.length;
        var multiPaging = false;
        var page = 0;
        var itemsPerPage = 5;
        if(numberOfItems > 10){
            multiPaging = true;
            
        }
        
        
        var i = 0;
        $.each(ids, function( index, value ) {
            
            var actionHTML = '';
            var pageClass = '';
            var itemStyle = '';
            
            if(typeof actions['update'] !== 'undefined'){
                actionHTML += '<a href="#" onclick="'+actions['update']['onclick']+'('+value+')'+'"><span class="icon icon-pencil"></span></a>';
            }
            if(typeof actions['delete'] !== 'undefined'){
                actionHTML += '<a href="#" onclick="'+actions['delete']['onclick']+'('+value+')'+'"><span class="icon icon-minus"></span></a>';
            }
            if(actionHTML.length > 0){
                actionHTML = actionHTML+'';
            }
            if(!empty(captions[index])){
                if(multiPaging){
                    if(i>=itemsPerPage){ 
                        if(i%itemsPerPage == 0){
                            page++;
                        }
                    }
                    pageClass = 'page_'+page;
                    if(page === 0){
                        itemStyle = 'display:block;';
                    }else{
                        itemStyle = 'display:none';
                    }
                }else{
                    pageClass = '';
                }
                html += "                        <li class=\"list-group-item page "+pageClass+"\" style="+itemStyle+">";
                html += captions[index];
                html += "                           <div class=\"pull-right action-buttons\">";
                html += actionHTML;
                html += "                            <\/div>";
                html += "                        <\/li>";
            }
            i++;
        });
        
        //add footer
        html += "    <\/ul>";
        html += "<\/div>";
        html += "<div class=\"panel-footer\">";
        html += "    <div class=\"row\">";
        html += "        <div class=\"col-md-6\">";
        html += "            <h6>";
        html += "                Total Count <span class=\"label label-info\">"+numberOfItems+"<\/span><\/h6>";
        html += "        <\/div>";
        if(page > 0){
        html += "        <div class=\"col-md-6\">";
        html += "            <ul class=\"pagination pagination-sm pull-right\">";
        html += "                <li><a href=\"gui.showPanelPage('"+title+"', "+(page-1)+")\">&laquo;<\/a><\/li>";
        
            if(numberOfItems > 5){
                var i = 1;
                var page = 1;
                
                
                html += '<li class="active"><a href="javascript:gui.showPanelPage(\''+title+'\', '+(page-1)+')">'+(page)+'</a></li>';
            
                
                while(i < numberOfItems){
                    if(i%itemsPerPage == 0){
                        page++;
                        html += '<li class="active"><a href="javascript:gui.showPanelPage(\''+title+'\', '+(page-1)+')">'+page+'</a></li>';
            
                    }
                    i++;
                }
                
            }
        
        html += "                <li><a href=\"javascript:gui.swapPanelPage(\'"+title+"\','up')\">&raquo;<\/a><\/li>";
        html += "            <\/ul>";
        html += "        <\/div>";
        }
        html += "    <\/div>";
        html += "<\/div>";
        $($selector).html(html);
        return true;
        
    };
    this.showPanelPage = function(panelTitle, page){
        $('.panel_'+panelTitle).attr('data-currentpage',page);
        $('.panel_'+panelTitle+' .page').hide();
        $('.panel_'+panelTitle+' .page_'+page).show();
        
    };
    this.swapPanelPage = function(panelTitle, direction){
        var currentPage = parseInt($('.panel_'+panelTitle).attr('data-currentpage'));
        if(direction === 'down'){
            currentPage--;
        }else if(direction === 'up'){
            currentPage++;
        }
        
        this.showPanelPage(panelTitle, currentPage);
        return true;
        
    }
    this.loadPage = function(url){
            var content;
                $.ajax({
                      url: url,
                      type: "GET",
                      async: false,
                      success: function(data) {
                                        content = data;
                      }
                });
                
                return content;
    }
    
    
    /**
    *Generates html for file gallery
    *@param str fileStr String with comma separed file id´s
    *@param str fieldName String with id of the field from which the file_id needs to be removed
    *@return str html with file gallery for gui.createForm
    */
    this.generateFileGallery = function(fileStr, fieldName){
        var html = '<ul>';
        var fileArray = explode(',', fileStr);
        $.each(fileArray, function(key, value){
            if(value){
                html += '<li class="file_'+value+'">'+files.idToTitle(value)+'<a href="#" class="btn btn-default" onclick="gui.removeFileFromGallery('+value+', \''+fieldName+'\');"><span class="icon icon-minus"></span></a></li>';
            }});
        html += '</ul>';
        return html;
    };
    this.removeFileFromGallery = function(file_id, fieldName){
        Check = confirm("Are you sure to delete this file?");
	if (Check === true){
            var field = String(fieldName);
            var newValue = $('#'+field).val();
            newValue.replace(String(file_id+','),'');
            $('#'+field).val(newValue);
            $('.file_'+file_id).remove();
	};
        
    };
    
    this.replaceLinks = function(){
    	
    	$('body').html($(this).html().replaceAll("/(b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|])/ig","<a href='#' onclick='$1'>$1</a>"));
        
    };
    this.generateGrayList = function(items){
      var html;
      html = '<ul class="grayList">';
        $.each(items, function(key, value){
            html += '<li><div>'; 
                   html += value.text;
            html += '</div>';
            html += value.buttons;
            html += '</li>';
        });
            
      html += '</ul>';
      return html;
    };
    
};
              
        
