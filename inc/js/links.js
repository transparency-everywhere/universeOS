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
        
        
        
var links = new function(){
    this.getData = function(linkId){
	return api.query('api/links/select', {link_id : linkId});
    };
    this.update = function(linkId, element, title,  type, privacy, link, callback){
        
        var result="";
	$.ajax({
            url:"api/links/update/",
            async: false,  
            type: "POST",
            data: $.param({link_id:linkId,element : element, title: title, type: type, link:link})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.create = function(element, title,  type, privacy, link, callback){
        var result="";
	$.ajax({
            url:"api/links/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : element, title: title, type: type, link:link})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.showCreateLinkForm = function(element){
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Link';
        field0['inputName'] = 'link';
        field0['type'] = 'text';
        field0['onChange'] = function(){
          var value = $('#link').val();
          //check if type is youtube
          if(isYoutubeURL(value)){
              //get youtube id
              $('#link_title').val(getYoutubeTitle(youtubeURLToVideoId(value)));
              $('#createLinkFormContainer #type').val('youTube');
          }
          
        };
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Title';
        field1['inputName'] = 'link_title';
        field1['type'] = 'text';
        fieldArray[1] = field1;
        
        var captions = ['Standard Link', 'RSS', 'Youtube', 'Soundcloud', 'File', 'Other'];
        var type_ids = ['link', 'RSS', 'youTube', 'soundcloud', 'file', 'other'];
        
        var field2 = [];
        field2['caption'] = 'Type';
        field2['inputName'] = 'type';
        field2['values'] = type_ids;
        field2['captions'] = captions;
        field2['type'] = 'dropdown';
        fieldArray[2] = field2;
        
        var field3 = [];
        field3['caption'] = '';
        field3['inputName'] = 'privacy';
        field3['type'] = 'html';
        field3['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[3] = field3;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Add Link To Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The links has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(2 , gui.loadPage('modules/filesystem/showElement.php?element='+element+'&reload=1'));
            };
            links.create(element, $('#createLinkFormContainer #link_title').val(), $('#createLinkFormContainer #type').val(),  $('#createLinkFormContainer #privacyField :input').serialize(), $('#createLinkFormContainer #link').val(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Add Link To Element', '<div id="createLinkFormContainer"></div>', modalOptions);
        gui.createForm('#createLinkFormContainer',fieldArray, options);
    };
    
    this.showUpdateLinkForm = function(linkId){
        var formModal = new gui.modal();
        var linkData = links.getData(linkId);
        
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Link';
        field0['inputName'] = 'link';
        field0['type'] = 'text';
        field0['value'] = linkData['link'];
        field0['onChange'] = function(){
          var value = $('#link').val();
          //check if type is youtube
          if(isYoutubeURL(value)){
              //get youtube id
              $('#link_title').val(getYoutubeTitle(youtubeURLToVideoId(value)));
              $('#createLinkFormContainer #type').val('youTube');
          }
          
        };
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Title';
        field1['inputName'] = 'link_title';
        field1['type'] = 'text';
        field1['value'] = linkData['title'];
        fieldArray[1] = field1;
        
        var captions = ['Standard Link', 'RSS', 'Youtube', 'Soundcloud', 'File', 'Other'];
        var type_ids = ['link', 'RSS', 'youTube', 'soundcloud', 'file', 'other'];
        
        var field2 = [];
        field2['caption'] = 'Type';
        field2['inputName'] = 'type';
        field2['values'] = type_ids;
        field2['captions'] = captions;
        field2['preselected'] = linkData['type'];
        field2['type'] = 'dropdown';
        fieldArray[2] = field2;
        
        var field3 = [];
        field3['caption'] = '';
        field3['inputName'] = 'privacy';
        field3['type'] = 'html';
        field3['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[3] = field3;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Link';
        var element = linkData['folder'];
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The link has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(2 , gui.loadPage('modules/filesystem/showElement.php?element='+element+'&reload=1'));
            };
            links.update(linkId, element, $('#createLinkFormContainer #link_title').val(),  $('#createLinkFormContainer #type').val(), $('#createLinkFormContainer #privacyField :input').serialize(), $('#createLinkFormContainer #link').val(), callback);
        };
        privacy.load('#privacyField', linkData['privacy'], true);
        formModal.init('Update Link', '<div id="createLinkFormContainer"></div>', modalOptions);
        gui.createForm('#createLinkFormContainer',fieldArray, options);
    };
    this.delete = function(linkId, callback){
        
        var result="";
	$.ajax({
            url:"api/links/delete/",
            async: false,  
            type: "POST",
            data: {link_id : linkId},
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.verifyRemoval = function(linkId){
        var confirmParameters = {};
        
        confirmParameters['title'] = 'Delete Link';
        confirmParameters['text'] = 'Are you sure to delete this link?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            links.delete(linkId);
            gui.alert('The link has been deleted');
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
};
