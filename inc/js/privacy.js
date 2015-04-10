

var privacy = new function(){
	
	this.load = function(selector, val, editable){
			  		if(typeof editable == 'undefined')
			  			editable = false;
			  		
			  		
			  		$.post("api/item/privacy/load/", {
	                       val:val, editable : editable
	                       }, function(result){
		                   		$(selector).html(result);
	                       }, "html");
			  		
			  		
			  	};
	this.show = function(val, editable){

			  		if(typeof editable == 'undefined')
			  			editable = false;
                                    
                                        return api.query('api/item/privacy/load/', { val : val, editable : editable });
			  	};
         
        this.updatePrivacy = function(type, item_id, privacy, callback){
            
            var result="";
            $.ajax({
                url:"api/item/privacy/",
                async: false,  
                type: "POST",
                data: $.param({type : type, itemId: item_id})+'&'+privacy,
                success:function(data) {
                   result = data;
                   if(typeof callback === 'function'){
                       callback(); //execute callback if var callback is function
                   }
                }
            });
            return result;
            
        };
        this.showUpdatePrivacyForm = function(type, item_id){
            var title;
            switch(type){
                case 'folder':
                    var itemData = folders.getData(item_id);
                    title = 'Folder '+itemData['name'];
                    break;
                case 'element':
                    var itemData = elements.getData(item_id);
                    title = 'Element '+ itemData['title'];
                    break;
                case 'comment':
                    title = 'Comment';
                    break;
                case 'feed':
                    var itemData = feed.getData(item_id);
                    title = 'Feed';
                    break;
                case 'file':
                    title = 'File '+files.fileIdToFolderTitle(item_id);
                    break;
                case 'link':
                    var itemData = links.getData(item_id);
                    title = 'Link '+linkData['title'];
                    break;
            }
            
            
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = '';
        field0['inputName'] = 'privacy';
        field0['type'] = 'privacy';
        field0['value'] = itemData['privacy'];
        fieldArray[0] = field0;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = title;
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The privacy has been updated');
                $('.blueModal').remove();
            };
            privacy.updatePrivacy(type, item_id, $('#createLinkFormContainer #privacyField :input').serialize(), callback);
        };
        formModal.init(title, '<div id="createLinkFormContainer"></div>', modalOptions);
        gui.createForm('#createLinkFormContainer',fieldArray, options);
            
        };
	
	//checks if user is authorized, to edit an item with privacy.
	this.authorize = function(privacy, author){
			  		if(author == localStorage.currentUser_userid)
			  			return true;
			  			
			  		var result = api.query('api.php?action=authorize', {privacy: privacy, author: author});
				    
                                        if(parseInt(result) === 1)
                                                     return true;
                                        else
                                                     return false;
			  	};
                                
        this.init = function(){
                                    $('.privacyPublicTrigger').click(function(){
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyGroupTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').addClass('active');
                                        
                                        $('input[type=checkbox].privacyPublicTrigger').prop('checked',true);
                                        
                                        //maybe $(this).next('input[type=checkbox].uncheckPublic')
                                        $('input[type=checkbox].uncheckPublic').prop('checked', false);

                                    });


                                    $('.privacyHiddenTrigger').click(function(){
                                        
                                        $('li.privacyHiddenTrigger').addClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                        $('li.privacyGroupTrigger').removeClass('active');
                                        $('input[type=checkbox].privacyHiddenTrigger').prop('checked',true);
                                        if(1){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyShowFriendDetails').click(function(){
                                    	$('.privacyShowBuddy').show();
                                    });

                                    $('.privacyCustomTrigger').click(function(){
                                        
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                        
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });


                                    
                                    $('.privacyOnlyMeTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckOnlyMe').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyBuddyTrigger').click(function(){
                                    	
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                    	var buddyTriggerId = '.privacyBuddyTrigger';
                                        if($(this).is(':checked')){
                                        	if($(this).data('privacytype') == "edit")
                                            	$(buddyTriggerId+'_see').prop('checked', true);
                                        }else{
                                        	if($(this).data('privacytype') == "see")
                                            	$(buddyTriggerId+'_edit').prop('checked', false);
                                        	if($(this).data('privacytype') == "edit")
                                            	$(buddyTriggerId+'_see').prop('checked', false);
                                        }
                                    	$('.privacyShowBuddy').show();
                                    });
                                    
                                    $('.privacyGroupTrigger').click(function(){
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                    	$('.privacyShowGroups').show();
                                    	var groupTriggerId = '.privacyGroupTrigger_'+$(this).data('groupid');
                                        if($(this).is(':checked')){
                                                $('.privacyBuddyTrigger').prop('checked', false);
                                        	if($(this).data('privacytype') == "edit")
                                            	$(groupTriggerId+'_see').prop('checked', true);
                                        }else{
                                        	if($(this).data('privacytype') == "see")
                                            	$(groupTriggerId+'_edit').prop('checked', false);
                                        	if($(this).data('privacytype') == "edit")
                                            	$(groupTriggerId+'_see').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.uncheckOnlyMe').click(function(){
                                        if($(this).is(':checked')){
                                            $('.privacyOnlyMeTrigger').prop('checked', false);
                                        }
                                    });
                                    
                                    
                                    
                                    $('.uncheckGroups').click(function(){
                                        
                                        if($(this).is(':checked')){
                                            $('input[type=checkbox].privacyGroupTrigger').prop('checked',false);
                                        }
                                        
                                    });
                                    
                                    $('.privacyHiddenTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    $('.privacyCustomTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.checkPrev').click(function(){
                                        //prev see check
                                    });
        };
	
};