//initialize
var sourceURL = 'http://localhost/universe';


var usernames = [];
var userPictures = [];

var privateKeys = [];
var messageKeys = [];

var openDialogueInterval;

var focus = true;

$(document).ready(function(){
        
        universe.init();
    
});

//window functions
var init = new function(){
	
	
	this.draggableApplications = function(){
		
              $(".fenster").not('.ui-draggable').draggable({
                      cancel: '.inhalt',
                      containment: '#bodywrap',
                      scroll: false,
                      drag: function(){
                          //disable textmarking
                          $('*').disableSelection();
                      },
                      stop: function(){
                          //enable textmarking
                          $('*').enableSelection();
                      }
              });
	};
	this.resizableApplications = function(){
              $(".fenster").not('.ui-resizable').resizable({
                      handles: 'n, e, s, w, ne, se, sw, nw',
                      containment: '#bodywrap',
                      start: function(){
                          //disable textmarking
                          $('*').disableSelection();
                          if($(this) != undefined){
                                  //bring window to front 
                                  $(this).css('z-index', 9999);
                                  $(this).css('position', 'absolute');
                          }
                      },
                      stop: function(){
                          //enable textmarking
                          $('*').enableSelection();
                      }
              });
	};
	this.applicationOnTop = function(){
          $('.fenster').children().mousedown(function(){
					
             	if($(this) != undefined){
             		
    			$(".fenster").css('z-index', 999);
              $(this).parent(".fenster").css('z-index', 9999); 
              $(this).parent(".fenster").css('position', 'absolute');
              }
          });
	};
	this.setApplicationsToStartupSizes = function(){
		//old creepy way to initalize windows => in future => css media width
  	
  	
      var oneSixthWidth = ($(document).width())/6;
      var oneSixthHeight = $(document).height()/6;
      
      var offsetTop = oneSixthHeight/2;
      var offsetRight = oneSixthWidth/2;
      var offsetLeft = offsetRight;
      
      var widthSm = oneSixthWidth;
      var heightSm = oneSixthHeight*4;
      
      var widthBig = oneSixthWidth*3;
      var heightBig = heightSm;
      
      
      
//              
//          $("#buddylist").css({
//          'top' : offsetTop,
//          'right' : offsetRight+20,
//          'width' : widthSm,
//          'height' : heightBig,
//          'z-index' : '9998'
//              });
//          
//          $("#feed").css({
//          'top' : offsetTop+20,
//          'right' : offsetRight,
//          'width' : widthSm,
//          'height' : heightBig,
//          'z-index' : '9997'
//              });
//              
//              
//          $("#chat").css({
//          'top' : offsetTop,
//          'left' : offsetLeft,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '997'
//              });
//              
//          $("#filesystem").css({
//          'top' : offsetTop+20,
//          'left' : offsetLeft+20,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '998'
//              });
//              
//          $("#reader").css({
//          'top' : offsetTop+40,
//          'left' : offsetLeft+40,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '999'
//              });
	};
	
	this.dashBox = function(){
            //init dashcloses 
			$('.dashBox .dashClose').click(function(){
				$(this).parent('.dashBox').slideUp();
			});	
	};
	
	this.toolTipper = function(){
  
  

          $(document).mousemove(function(event){
              window.mouseX = event.pageX;
              window.mouseY = event.pageY;
              $('.mousePop').hide();
          });
          
          
          
          //initialize mousePop(tooltip)
          $('.tooltipper').mouseenter(function(){
              
              var type = $(this).attr("data-popType");
              var id = $(this).attr("data-typeId");
              var text = $(this).attr("data-text");
              mousePop(type, id, text);
          }).mouseleave(function(){
              $('.mousePop').hide();
          });
		
	};
	this.search = function(){
		//init search
			$("#searchField").keyup(function()
			{
				
				delay(function(){
					var search;
					
					search = $("#searchField").val();
					if (search.length > 1)
					{
						$.ajax(
						{
							type: "POST",
							url: "modules/suggestions/dockSearch.php",
							data: "search=" + search,
							success: function(message)
							{
								$("#suggest").empty();
						  		if (message.length > 1)
								{						
									$("#suggest").append(message);
								}
							}
						});
					}
					else
					{
						$("#suggest").empty();
					}
					
				}, 500 );
			});
};
	
	//this function is called to initialzie GUI
	//all needed functions are collected here
	this.GUI = function(){
		this.draggableApplications();
		this.resizableApplications();
		this.applicationOnTop();
		this.setApplicationsToStartupSizes();
		
		this.dashBox();
		
		this.toolTipper();
		this.search();
		
		dashBoard.init();
		//fade in applications
                $("#filesystem:hidden").fadeIn(3000);
                $("#buddylist:hidden").fadeIn(3000);

                $("#feed:hidden").fadeIn(3000);
                $("#chat:hidden").fadeIn(3000);
                
                $('*').on('scroll',function(){
                 $('.itemSettingsWindow, .rightClick').hide();
                });
          
	};
	
};

//class is required because gui.loadScript is used to load reader.js, chat.js etc
var gui = new function(){
    this.initWysiwyg = false; //is used in generateField and createForm to check if wysiwyg needs to be initialized
    this.initializeUploadify = false;
    this.generateList = function(values, captions, preselected){
        var html = '';
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
                            mainHTML += '<td>' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;   
                        case'wysiwyg':
                            break;
                        case'checkbox':
                            mainHTML += '<td>' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        case'dropdown':
                            mainHTML += '<td>' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        case'password':
                            mainHTML += '<td>' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        case'button':
                            mainHTML += '<td>' + fieldData.caption + '</td><td>&nbsp;</td>';
                            break;
                        default:
                            mainHTML += '<td colspan="4">' + fieldData.caption + '</td></tr><tr class='+tr_class+'>';
                            break;
                         
                    }

        mainHTML += '</tr>';
        mainHTML += '<tr>';
                    //body
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
                            mainHTML += '<td colspan="3"><input type="text" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" value="' + fieldData['value'] + '" style="width:100%;" '+disabled+'/></td><td>'+fieldData['appendix']+'</td>';
                            break;
                        case 'textarea':
                            if(!fieldData['value']){
                                fieldData['value'] = '';
                            }
                            mainHTML += '<td colspan="3"><textarea name="' + fieldData.inputName + '" id="' + fieldData.inputName + '">'+fieldData['value']+'</textarea></td><td>'+fieldData['appendix']+'</td>';
                            break;
                        case 'password':
                            mainHTML += '<td><input type="password" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '"/></td>';
                            break;
                        case 'checkbox':
                            var checked;
                            if(fieldData.checked === true){
                                checked = 'checked="checked"';
                            }else{
                                checked = '';
                            }
                            mainHTML += '<td><input type="checkbox" value="' + fieldData.value + '" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '" '+ checked +'/></td>';
                            break;
                        case 'radio':
                            mainHTML += '<td><input type="text" name="' + fieldData.inputName + '" id="' + fieldData.inputName + '"/></td><td>'+fieldData['appendix']+'</td>';
                            break;
                        case 'dropdown':
                            mainHTML += '<td><select name="' + fieldData.inputName + '" id="' + fieldData.inputName + '">';
                            mainHTML += gui.createDropdown(fieldData.values, fieldData.captions, fieldData.preselected);
                            mainHTML += '</select></td><td>'+fieldData['appendix']+'</td>';
                            break;
                        case 'space':
                            mainHTML += '<td></td>';
                            break;
                        case 'wysiwyg':
                            gui.initWysiwyg = true;
                            mainHTML += '<td colspan="3"><div class="wysiwyg" id="' + fieldData.inputName + '" contenteditable="true">'+fieldData.value+'</div></td><td>'+fieldData['appendix']+'</td>';
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
                        case 'html':
                            mainHTML += '<td colspan="3">'+fieldData.value+'</td><td>'+fieldData['appendix']+'</td>';
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
                options['action']();
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
    this.createDropdown = function(values, captions, preselected){
        var html = '';
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
        var gutterWidth = $(document).width()/49;
        return ((numberOfFields*3)+(numberOfFields-1))*gutterWidth;
    };
    this.getRasterHeight = function(numberOfFields){
        var gutterHeight = $(document).height()/49;
        return ((numberOfFields*3)+(numberOfFields-1))*gutterHeight;
    };
    this.getRasterMarginLeft = function(numberOfFields){
        var gutterWidth = $(document).width()/49;
        return ((numberOfFields*3)+(numberOfFields+1))*gutterWidth;
    };
    this.getRasterMarginTop = function(numberOfFields){
        var gutterHeight = $(document).height()/49;
        return ((numberOfFields*3)+(numberOfFields+1))*gutterHeight;
        
    };
    
    this.modal = function(){
        this.html;
        this.init = function (title, content, options) {
			    	this.html = '';
			    	this.html += '<div class="blueModal border-radius container">';
	            		this.html += '<header>';
	            			this.html += title;
	            			this.html += '<a class="modalClose" onclick="$(\'.blueModal\').remove();">X</a>';
	            		this.html += '</header>';
	            		this.html += '<div class="content">';
	            		this.html += content;
	            		this.html += '</div>';
	            		this.html += '<footer>';
	            		
	                 		this.html += '<a href="#" onclick="$(\'.blueModal\').remove(); return false;" class="btn pull-left">Close</a>';
	                 		if(typeof options['action'] !== 'undefined'){
	                			this.html += '<a href="#" id="action" class="btn btn-primary pull-right">&nbsp;&nbsp;'+options['buttonTitle']+'&nbsp;&nbsp;</a>';
	                 		}
	            		
	            		this.html += '</footer>';
	            		
                                this.html += '</div>';
                                $('.blueModal').remove();
                                $('#popper').append(this.html);

                                if(typeof options['action'] !== 'undefined'){
                                        $('.blueModal #action').click(function(){
                                                options['action']();
                                        });
                                }
	};
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
        
    }
};
              
var universe = new function(){
    this.notificationArray = [];
    this.init = function(){
        gui.loadScript('inc/js/item.js');
        
        gui.loadScript('inc/js/links.js');
        gui.loadScript('inc/js/api.js');
        
        gui.loadScript('inc/js/folders.js');
        
        gui.loadScript('inc/js/elements.js');
        
        gui.loadScript('inc/js/fav.js');
        
        gui.loadScript('inc/js/playlists.js');
        
        
        applications.init();
        
        
        //init draggable windows
        init.GUI();
        
        //init bootstrap popover
        $('.bsPopOver').popover();
        
        //init bootstrap alert
        $(".alert").alert();
        
    };
    this.reload = function(){
        //fetch request data like open filebrowsers & feeds
        var requests = [];
        
        $.each(reader.uffChecksums,function(index, value){
                    if(typeof(value) != 'undefined'){
                        requests.push({
                                                            action : 'UFF',
                                                            subaction:'sync',
                                                            data: {
                                                                file_id:index,
                                                                checksum:value
                                                            }
                        });
                    }
                });
        
        requests.push({
                    action:'buddylist',
                    subaction:'reload',
                    data: {
                        buddy_checksum: buddylist.checksum
                    }
                });
                
                
        requests.push({
            action : 'IM',
            subaction:'sync',
            data: {
                last_message_received:im.lastMessageReceived
            }
        });
        
        var feedsArray = [];
        $('.feedFrame').each( function(){
            feedsArray.push({'type':$(this).data('type'), 'last_feed_received':$(this).data('last')});
        });
        
        requests.push({
            action : 'feed',
            subaction: 'sync',
            data:feedsArray
        });
                
        var requestData = {
            request:requests
        };
        
        var response = api.query('api/reload/', requestData);
        var temp = this;
        $.each(response, function(key, value){
            temp.handleReloadTypes(value);
        });
        
    };
    this.handleReloadTypes = function(responseElement){
        switch(responseElement.action){
            
            
            case'buddylist':
                if(responseElement.subaction === 'reload'){
                    buddylist.reload();
                }else if(responseElement.subaction === 'openRequest'){
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.userid)+useridToUsername(responseElement.data.userid)+' want\'s to be your friend',
                                                    acceptButton:{
                                                        action: 'buddylist.acceptBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'accept'
                                                    },
                                                    cancelButton:{
                                                        action: 'buddylist.denyBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'decline'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                }
                break;
                
            case 'groups':
                if(responseElement.subaction === 'openRequest'){
                    
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.author)+useridToUsername(responseElement.data.author)+' invited you into the group '+responseElement.data.group_title,
                                                    acceptButton:{
                                                        action: 'groups.join('+responseElement.data.group_id+')',
                                                        value: 'join'
                                                    },
                                                    cancelButton:{
                                                        action: 'groups.declineRequest('+responseElement.data.group_id+')',
                                                        value: 'decline'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                }
                break;
                
            case 'IM':
                if(responseElement.subaction === 'sync'){
                    im.sync(responseElement.data);
                }
                break;
            case 'UFF':
                if(responseElement.subaction === 'sync'){
                    if($('.uffViewer_'+responseElement.data['file_id']).length > 0){
                            $('.uffViewer_'+responseElement.data['file_id']).html(function(){
                                
                                var caretPosition = getCaretPosition(this);
                                
                                $.get('doit.php?action=loadUff&id='+responseElement.data['file_id']+'&noJq=true', function(uffContent) {
                                    $('.uffViewer_'+responseElement.data['file_id']).val(uffContent);
                                });
                                
                            });
                    }
                };
            case 'notification':
                if(responseElement.subaction === 'push'){
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.user)+useridToUsername(responseElement.data.user)+responseElement.data.description,
                                                    acceptButton:{
                                                        action: responseElement.data.link.replace(/\\/g,''),
                                                        value: 'show'
                                                    },
                                                    cancelButton:{
                                                        action: '',
                                                        value: 'ignore'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                    
                };
                break;
            case 'feed':
                
                if(responseElement.subaction === 'sync'){
                    feed.reload(responseElement.data.type, responseElement.data.typeId);
                };
                break;
        };
    };
};

var im = new function(){
    this.lastMessageReceived = 1;
    this.openChatWindows = []; //array(userid1, userid2, userid3 etc...)
    
    //opens message either as a notification, as a chat window or updates the chatwindow
    this.openMessage = function(messageData){
        
    };
    this.openDialogue = function(parameter){
        if(is_numeric(parameter)){
            var username = usernameToUserid(parameter);
        }else{
            var username = parameter;
        }
        openChatDialoge(username);
    };
    
    //proceeds data from reload function
    this.sync = function(data){
        $.each(data, function(key,value){
            im.openMessage(value);
        });
    };
};

var applications = new function(){
    this.getList = function(){
        
        var apps = [];
        
        var standardIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAARNJREFUWMNjYBgFo2AUkAdEgTgTiKcC8RQgTgdiYXpYzAjEeUD8DYhfAfE2KH4DxJ+BOJXWDigD4t9AnA/ErEjibFC5v0CcTSvLVaCWJ+NRAwqdH0AsRwsHtALxVWg04ALMQHwPiGtp4YCdQDyZCHUxQBxECwccgYbCgAGQA1oGyvJIIP4IzWpviMB3gTiUWpazAPEXIJ4GjV9i8Eyog5mo4QAeIP4PxDZY5OyA2BqLuANUDwctHWAAFQdhrYFwgBIQ/wLin1gKHro4AAQWAvGcgYoCEDgMxPsG0gHrgHj1QDoAF6CJA+xI0OMM1cNOrUbIDWhNuIFIfB2IL1GzKJYH4k4gnkEk7gBi2dGW6ygYBcQAAHKhV6RVQmrvAAAAAElFTkSuQmCC';
        
        //reader
        var app = [];
        app['title'] = 'reader';
        app['source'] = 'reader.js';
        app['className'] = 'reader'; // name of the the javascript class object
        app['active'] = true;
        app['position'] = {width: 5, height:  4, top: 0, left: 4, hidden: true};
        app['icon'] = standardIcon;
        apps[0] = app;
        
        //filesystem
        var app = [];
        app['title'] = 'filesystem';
        app['source'] = 'filesystem.js';
        app['className'] = 'filesystem'; // name of the the javascript class object
        app['active'] = true;
        app['position'] = {width: 6, height:  5, top: 0, left: 3};
        app['icon'] = standardIcon;
        apps[1] = app;
        
        
        
        
        if(proofLogin()){
            //feed
            var app = [];
            app['title'] = 'feed';
            app['source'] = 'feed.js';
            app['active'] = true;
            app['className'] = 'feed'; // name of the the javascript class object
            app['position'] = {width: 2, height:  5, top: 0, left: 0};
            app['icon'] = standardIcon;
            apps[2] = app;
            
            //chat
            var app = [];
            app['title'] = 'chat';
            app['source'] = 'chat.js';
            app['className'] = 'chat'; // name of the the javascript class object
            app['active'] = true;
            app['position'] = {width: 2, height:  2, top: 0, left: 5};
            app['icon'] = standardIcon;
            apps[3] = app;

            //settings
            var app = [];
            app['title'] = 'settings';
            app['source'] = 'settings.js';
            app['className'] = 'settings'; // name of the the javascript class object
            app['active'] = false;
            app['position'] = {width: 2, height:  5, top: 0, left: 0};
            app['icon'] = standardIcon;

            apps[4] = app;

            //calendar
            var app = [];
            app['title'] = 'calendar';
            app['source'] = 'calendar.js';
            app['className'] = 'calendar'; // name of the the javascript class object
            app['active'] = false;
            app['position'] = {width: 2, height:  2, top: 0, left: 9};
            app['icon'] = standardIcon;
            apps[5] = app;


            //buddylist
            var app = [];
            app['title'] = 'buddylist';
            app['className'] = 'buddylist'; // name of the the javascript class object
            app['source'] = 'buddylist.js';
            app['active'] = true;
            app['position'] = {width: 2, height:  5, top: 0, left: 9};
            app['icon'] = standardIcon;
            apps[6] = app;
        }
        
        return apps;
    };
    
    this.init = function(){
        var sessionApplications = this.getList();
        
        $.each(sessionApplications, function(index, value){
            if(value)
            if(value['source']){
                gui.loadScript('inc/js/'+value['source']);
                if(value['active']){

                    //eval is evil!!!!!!!! maybe we should look for another solution
                    eval(value['className']).init();
                };
            }
        });
    };
    
};

//options.data                  array( 0 => [caption=data-CAPTION, value]
//options.id                    id of the notification
//options.message               string notification message
//options.acceptButton.action   action of accept button
//options.acceptButton.value    value of accept button
//options.cancelButton.action   action of cancel button
//options.cancelButton.value    value of cancel button
var notification = function(options){
    this.options = options;
    this.init = function(){
        
    };
    this.initClicks = function(){
      $('#notifications .messageButton .btn').click(function(){
         $(this).parent('li').hide(); 
      });
    };
    this.push = function(){
        var note = this.generateNotification();
        $('#notifications>ul').append(note);
        this.initClicks();
    };
    this.generateNotification = function(){
        var options = this.options;
        
        
        //generate data attributes
        var data = '';
        if(options.data)
        $.each(options.data, function(key, value){
            data = 'data-'+value.caption+'="'+value.value+'"';
        });
        
        
        //if empty id => generate random id
        if(!options.id)
            options.id = randomString(6, '#aA');
        
        
        
        var html = '<li id="'+options.id+'" '+data+'>\n\
            <div class="messageMain">\n\
                '+options.message+'\n\
            </div>\n\
            <div class="messageButton">\n\
                <a href="#" onclick="'+options.acceptButton.action+'" class="btn btn-success btn-xs" style="margin-right:25px;">'+options.acceptButton.value+'</a>';
            if(options.cancelButton){
                html += '<a href="#" onclick="'+options.cancelButton.action+'" class="btn btn-default btn-xs" style="margin-right:25px;">'+options.cancelButton.value+'</a>';
            }
            html += '</div>\n\
        </li>';
        
        return html;
    };
};
              
var application = function(id){
        this.id = id;
	this.create = function(title, type, value, style){
            var id = this.id;
            var content;
            if(type === 'html'){
                content = value;
            }else if(type === 'url'){
                
                $.ajax({
                      url: value,
                      type: "GET",
                      async: false,
                      success: function(data) {
                                        content = data;
                      }
                });
            }
            
		var windowStyle = '';
		
                
                //check if style parameters are set
                //for width, height, top, left
                //if value is not nummeric(eg '222px') use value,
                //otherwise use pixelraster(see gui class)
		if(typeof style['width'] != 'undefined'){
                    if(isNaN(style['width'])){
			windowStyle +='width:'+style['width']+';';
                    }else{
                        windowStyle +='width:'+gui.getRasterWidth(style['width'])+'px;';
                    }
		}
                
		if(typeof style['height'] != 'undefined'){
                    if(isNaN(style['width'])){
			windowStyle +='height:'+style['width']+';';
                    }else{
                        windowStyle +='height:'+gui.getRasterHeight(style['height'])+'px;';
                    }
		}
                
		if(typeof style['top'] != 'undefined'){
                    if(isNaN(style['top'])){
			windowStyle +='top:'+style['top']+';';
                    }else{
                        windowStyle +='top:'+gui.getRasterMarginTop(style['top'])+'px;';
                    }
		}
                
		if(typeof style['left'] != 'undefined'){
                    if(isNaN(style['left'])){
			windowStyle +='left:'+style['left']+';';
                    }else{
                        windowStyle +='left:'+gui.getRasterMarginLeft(style['left'])+'px;';
                    }
		}
                
		if(typeof style['hidden'] != 'undefined'){
                    if(style['hidden'] === true){
                        
                        windowStyle +='display:none;';
                    }
		}
                
		
		
		var output = '<div class="fenster" id="'+id+'" style="'+windowStyle+'">';
			output += '<header class="titel">';
			output += '<p>'+title+'&nbsp;</p>';
			output += '<p class="windowMenu">';
				output += '<a href="javascript:'+id+'.applicationVar.hide();"><span class="icon dark-minimize"></span></a>';
				output += '<a href="#" onclick="'+id+'.applicationVar.fullscreen();" class="fullScreenIcon"><span class="icon dark-maximize"></span></a>';
                                output += '<a href="#"><span class="icon dark-close"></span></a>'
			output += '</p>';
		output += '</header>';
		output += '<div class="inhalt autoflow" id="'+id+'Main">'+content+'</div>';
		output += '</div>';

		
		$('#bodywrap').append(output);
                  
		
              init.draggableApplications();
              init.resizableApplications();
              this.onTop();
	};
	
	this.onTop = function(){
            var id = this.id;
		
          $(".fenster").css('z-index', 998);
          $("#"+id+"").css('z-index', 999);
          $("#"+id+"").css('position', 'absolute');
		
	};
	
	this.show = function(){
          var id = this.id;
          this.onTop();
          $("#" + id +"").show();
	};
	
	this.hide = function(){
          var id = this.id;
          $("#" + id +"").hide();
	};
	
	this.fullscreen = function(){
            var moduleId = this.id;
              	$('#'+moduleId+' .fullScreenIcon').attr("onClick",moduleId+".applicationVar.returnFromFullScreen()");
              	 window.fullScreenOldX = $('#'+moduleId).width();
              	 window.fullScreenOldY = $('#'+moduleId).height();
              	 var position = $('#'+moduleId).position();
              	 window.fullScreenOldMarginX = position.left;
              	 window.fullScreenOldMarginY = position.top;
                  
                  var fullscreenCss = {
                        'position' : 'absolute',
                        'top' : '5px',
                        'bottom' : '10px',
                        'left' : '5px',
                        'right' : '5px',
                        'width' : 'auto',
                        'height' : 'auto'
                       };
                  $("#" + moduleId + "").css(fullscreenCss);
	};
	
	this.returnFromFullScreen = function(){
          var id = this.id;
          $('#'+id+' .fullScreenIcon').attr("onClick",id+".applicationVar.fullscreen()");
            var returnFullScreenCSS = {
                  'position' : 'absolute',
                  'top' : window.fullScreenOldMarginY,
                  'left' : window.fullScreenOldMarginX,
                  'width' : window.fullScreenOldX,
                  'height' : window.fullScreenOldY
                 };
            $("#" + id + "").css(returnFullScreenCSS);
	};
};
			  
var tasks = new function(){
	
	this.getData = function(taskId){
		var res;
		$.ajax({
	      url:"api.php?action=getTaskData",
	      async: false,  
		  type: "POST",
		  data: { 
		  	taskId : taskId
		  	 },
	      success:function(data) {
	         res = $.parseJSON(data); 
	      }
	   });
	   return res;
	};
	
	this.addForm = function(startstamp){
		if(typeof startstamp === undefined)
			var d = new Date(startstamp*1000);
		else
			var d = new Date();
			
		
		var formattedDate = calendar.beautifyDate((d.getMonth())+1)+'/'+calendar.beautifyDate(d.getDate())+'/'+d.getFullYear();
		
		var content  = '<table class="formTable">';
				content += '<form id="createTask" method="post">';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Title:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<input type="text" name="title" id="taskTitle">';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Description:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<textarea name="description" id="taskDescription"></textarea>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Day:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<input type="text" name="date" id="date" class="date datepicker" value="'+formattedDate+'" style="width: 72px;">';
		  		    	content += '&nbsp;<input type="text" name="time" id="time" class="time eventTime" value="15:30" style="width: 37px;">';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Status:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<select name="status">';
		  		    	content += '<option value="pending">Pending</option>';
		  		    	content += '<option value="done">Done</option>';
		  		    	content += '</select>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Privacy:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += privacy.show('f//f', true);
		  		    	content += '</td>';
		  		    content += '</tr>';
		    	content += '</form>';
		    content += '</table>';
		var onSubmit = function() {
			$('#createTask').submit();
		};
		
		//create modal
              modal.create('Create New Task', content, [onSubmit, 'Save']);

              //init datepicker in modal
              $('.datepicker').datepicker();

              $('#createTask').submit(function(e){
                      e.preventDefault();
                      if($('#taskTitle').val().length > 0 && $('#date').val().length > 0 && $('#time').val().length > 0){

                              $.post("api.php?action=createTask",$(this).serialize(),function(data){
                                            if(data.length === 0){
                                              calendar.loadTasks();
                                              jsAlert('','The Task has been added.');
                                              $('.blueModal').slideUp();
                                              updateDashbox('task');
                                          }else{
                                              jsAlert('', data);
                                          }
                                      });

                      }else{
                              jsAlert('', 'You need to fill out all the fields.');
                      }


                      return false;
              });
	};
	
	this.get = function(startStamp, stopStamp, privacy){
		var result;
	    $.ajax({
	      url:"api.php?action=getTasks",
	      async: false,  
		  type: "POST",
		  data: { 
		  	 startStamp: startStamp,
		  	 stopStamp: stopStamp,
		  	 privacy: privacy
		  	 },
	      success:function(data) {
	      	if(data){
	        	result = $.parseJSON(data); 
	      	}
	      }
	   });
	   
	   return result;
	};
	
	this.show = function(taskId, editable){
		
		var taskData = tasks.getData(taskId);
		
		var date = new Date(taskData.timestamp*1000);
		
		
		var editableToken;
		
		
		var status = taskData.status;
		//generate formstuff from eventdata
		if(editable){
			editableToken = 'contentEditable';
			var checked;
			if(editable == true){
				
				checked = 'checked="checked"';
				status = '<select name="status" id="status">';
						if(taskData.status == 'pending'){
			  		    	status += '<option value="pending">Pending</option>';
			  		    	status += '<option value="done">Done</option>';
						}else{
			  		    	status += '<option value="done">Done</option>';
			  		    	status += '<option value="pending">Pending</option>';
						}
					status += '</select>';
			}
			
		}else{
		}
		
		var time = calendar.beautifyDate(date.getHours())+':'+calendar.beautifyDate(date.getMinutes());
		
		
		
		
		var content  = '<table class="formTable">';
				content += '<form id="updateTask" method="post">';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Title:';
		  		    	content += '</td>';
		  		    	content += '<td '+editableToken+' id="title">';
		  		    	content += taskData.title;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Description:';
		  		    	content += '</td>';
		  		    	content += '<td '+editableToken+' id="description">';
		  		    	content += taskData.description;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Day:';
		  		    	content += '</td>';
		  		    	content += '<td><div id="date" class="datepicker" '+editableToken+'>';
		  		    	content += date.getMonth()+1+'/'+date.getDate()+'/'+date.getFullYear();
		  		    	content += '</div>&nbsp;<div id="time" '+editableToken+'>'+time+'</div>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Status:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += status;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Privacy:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += privacy.show(taskData.privacy, editable);
		  		    	content += '</td>';
		  		    content += '</tr>';
		    	content += '</form>';
		    content += '</table>';
		
		var onSubmit = function() {
			$('#updateTask').submit();
		};
		
		//create modal
              modal.create('Task '+taskData.title, content, [onSubmit, 'Save']);

              //init datepicker in modal
              $(".datepicker").datepicker({
                                  dateFormat: 'dd/mm/yy',
                                  showOn: "button",
                                  buttonImage: "images/calendar.gif",
                                  buttonImageOnly: true,
                                  onClose: function(dateText, inst) {
                                      $(this).parent().find("[contenteditable=true]").focus().html(dateText).blur();
                                  }
                              });

                              $('#updateTask').submit(function(e){
                                      e.preventDefault();
                                      if($('#title').text().length > 0 && $('#date').text().length > 0 && $('#time').text().length > 0){

                                              var privacy = $('.blueModal .privacySettings  :input').serialize();

                                              var searchString  = 'taskId='+encodeURIComponent(taskId);
                                                      searchString += '&title='+encodeURIComponent($('.blueModal #title').text());
                                                      searchString += '&description='+encodeURIComponent($('.blueModal #description').text());
                                                      searchString += '&date='+encodeURIComponent($('.blueModal #date').justtext());
                                                      searchString += '&status='+encodeURIComponent($('.blueModal #status').val());
                                                      searchString += '&time='+encodeURIComponent($('.blueModal #time').text());
                                                      searchString += '&'+privacy;

                                              $.post("api.php?action=updateTask",searchString,function(data){

                                                          if(empty(data)){
                                                              jsAlert('', 'The event has been updated.');
                                                              $('.blueModal').slideUp();
                                                          }else{
                                                              jsAlert('', data);
                                                          }
                                                      });

                                      }else{
                                              jsAlert('', 'You need to fill out all the fields.');
                                      }


                                      return false;
                              });
	};
	this.create = function(user, timestamp, title, description, privacy){
		
	    $.ajax({
	      url:"api.php?action=createTask",
	      async: false,  
		  type: "POST",
		  data: { 
		  	user : user,
		  	timestamp : timestamp,
		  	title : title,
		  	description : description,
		  	privacy : privacy
		  	 },
	      success:function(data) {
	         result = data; 
	      }
	   });
	};
	this.markAsDone = function(id){
		$.ajax({
	      url:"api.php?action=markTaskAsDone",
	      async: false,  
		  type: "POST",
		  data: { 
		  	eventid : id,
		  	 },
	      success:function(data) {
	         result = data; 
	      }
	   });
	   $('.task_'+id).addClass('doneTask');
	   if(!calendar.showDoneTasks){
	   	$('.task_'+id).hide();
	   }
	};
	this.markAsPending = function(id){
		$.ajax({
	      url:"api.php?action=markTaskAsPending",
	      async: false,  
		  type: "POST",
		  data: { 
		  	eventid : id,
		  	 },
	      success:function(data) {
	         result = data; 
	      }
	   });
	  $('.task_'+id).removeClass('doneTask');
	  $('.task_'+id).show();
	};
	this.update = function(){
		updateDashbox('task');
	};
};

var User = new function(){
    this.userid;
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
		            var result="";
		            
                            $.ajax({
		                url:sourceURL+"/api.php?action=getLastActivity",
		                async: false,  
		                    type: "POST",
		                    data: { 
		                                    request : request 
		                                },
		                success:function(data) {
		                           result = data; 
		                        }
		            });
		            
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
    this.getAllData = function(userid){
        //data will only be returned if getUser()==userid or userid is on buddylist of getUser()
        
            return api.query('api/user/getAllData/', { user_id:userid });
    }
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
};
		
var browser = new function(){
	this.tabs;
        this.applicationVar;
	this.startUrl = 'http://transparency-everywhere.com';
	this.init = function(){
            
			  		var html = '<div class="browser">';
							html += '<header>';
								html += '<form onsubmit=" return false;" class="urlForm">';
									html += '<span><a href="#" class="browserBack btn btn-small"><<</a> <a href="#" class="browserNext btn btn-small">>></a> <a href="#" class="browserToggleProxy btn btn-small" title="You are currently not using your proxy"><i class="icon icon-eye"></i></a> </span><input type="text" class="browserInput" placeholder="'+this.startUrl+'" value="'+this.startUrl+'">';
								html += '</form>';
							html += '</header>';
						html += '</div>';
			  			applicationVar = new application('calendarFenster');
                                                applicationVar.create('Calendar', 'html', html,{width: ($(document).width()*0.9)+"px", height:  ($(document).height()*0.8)+"px"});
			  			
                                                $('.browser .urlForm').submit(function(){
                                                    var currentTab = $('.browser .tabFrame header li.active').attr('data-tab');
                                                    browser.openUrl($(this).children('.browserInput').val(),currentTab);
                                                });
                                                
						this.tabs = new tabs('.browser');
                                                this.tabs.init();
						this.tabs.addTab('start', '',browser.loadPage('http://transparency-everywhere.com'));
			  	};
	this.loadPage = function(url){
			  return '<iframe src="'+url+'"></iframe>';
                      };
	this.openUrl = function(url, tabIdentifier){
                                        alert(url+tabIdentifier);
                                        if(typeof tabIdentifier == 'undefined')
                                            this.tabs.addTab('start', '',browser.loadPage(url));
                                        else
                                            this.tabs.updateTabContent(tabIdentifier ,browser.loadPage(url));
			  	};
};
          
          
var debug = new function(){
  this.log = function(input){
  };
};

var privacy = new function(){
	
	this.load = function(selector, val, editable){
			  		if(typeof editable == 'undefined')
			  			editable = false;
			  		
			  		
			  		$.post("api/item/privay/load/", {
	                       val:val, editable : editable
	                       }, function(result){
		                   		$(selector).html(result);
	                       }, "html");
			  		
			  		
			  	};
	this.show = function(val, editable){

			  		if(typeof editable == 'undefined')
			  			editable = false;
			  			
				    var result="";
				    
				    $.ajax({
				      url:"api/item/privay/load/",
				      async: false,  
					  type: "POST",
					  data: { val : val, editable : editable },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   return result;
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
        field0['caption'] = 'Privacy';
        field0['inputName'] = 'privacy';
        field0['type'] = 'html';
        field0['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[0] = field0;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = title;
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The privacy has been updated');
                $('.blueModal').remove();
            };
            privacy.updatePrivacy(type, item_id, $('#createLinkFormContainer #privacyField :input').serialize(), callback);
        };
        privacy.load('#privacyField', itemData['privacy'], true);
        formModal.init(title, '<div id="createLinkFormContainer"></div>', modalOptions);
        gui.createForm('#createLinkFormContainer',fieldArray, options);
            
        };
	
	//checks if user is authorized, to edit an item with privacy.
	this.authorize = function(privacy, author){
			  		if(author == localStorage.currentUser_userid)
			  			return true;
			  			
			  		var result;
				    $.ajax({
				      url:"api.php?action=authorize",
				      async: false,  
					  type: "POST",
					  data: { 
					  	 privacy: privacy,
					  	 author: author
					  	 },
				      success:function(data) {
				      	if(data){
				        	result = data;
				      	}
				      }
				   });
				   if(parseInt(result) === 1)
				   		return true;
				   else
				   		return false;
			  	};
                                
        this.init = function(){
               $('.privacyPublicTrigger').click(function(){

                                        if($(this).is(':checked')){

                                            $('.uncheckPublic').prop('checked', false);

                                        }

                                    });

                                    $('.privacyCustomTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });


                                    $('.privacyHiddenTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyOnlyMeTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckOnlyMe').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyBuddyTrigger').click(function(){
                                    	
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
                                    	$('.privacyShowGroups').show();
                                    	var groupTriggerId = '.privacyGroupTrigger_'+$(this).data('groupid');
                                        if($(this).is(':checked')){
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

var groups = new function(){
	
        this.show = function(groupId){
            
                  reader.applicationVar.show();
                    reader.tabs.addTab("" + groupId + "", '',gui.loadPage("./group.php?id=" + groupId + ""));
              
                  return false;
        };
        this.create = function(title, type, description, invitedUsers){
            var callback = function(data){
                       if(data != '1'){
                           gui.alert('The group could not be created', 'Create Group');
                       };
            };
            api.query('api/groups/create/', { title : title, type: type, description: description, invitedUsers: JSON.stringify(invitedUsers) }, callback);
        };
	this.get = function(){
			  		
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=getGroups",
				      async: false,  
					  type: "POST",
					  data: { val : 'val' },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   	if(result != null){
				   		return $.parseJSON(result);
				   	}
	};
        this.getData = function(groupId){
            
            return api.query('api/groups/getData/', { group_id : groupId });
            
        };
        this.deleteUserFromGroup = function(groupId, userId){
            
            
            callback = function(){
              gui.alert('The user has been removed'); 
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
            };
            api.query('api/groups/removeUser/', { group_id : groupId, user_id: userId },callback);
            
        };
	this.getTitle = function(groupId){
			  		
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=getGroupTitle",
				      async: false,  
					  type: "POST",
					  data: { groupId : groupId },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   	if(result){
				   		return result;
				   	}
			  		
			  	};
        this.update = function(groupId, title, description, type, membersInvite){
            
            callback = function(){
              gui.alert('The group has been updated'); 
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              };
            };
            api.query('api/groups/update/', { group_id : groupId, title: title, description: description, type: type, members_invite: membersInvite },callback);
        };
                     
        this.makeUserAdmin = function(groupId, userId){
            callback = function(){
              gui.alert('The admin has been added');
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
            };
            api.query('api/groups/makeUserAdmin/', { group_id : groupId, user_id: userId },callback);
        };
        this.removeFromAdmins = function(groupId, userId){
            callback = function(){
              gui.alert('The admin has been removed');
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
              
            };
            api.query('api/groups/removeFromAdmins/', { group_id : groupId, user_id: userId },callback);
            
        };
        this.getUsers = function(groupId){
            
            return api.query('api/groups/getUsers/', { group_id : groupId });
        
        };
        
        this.verifyGroupRemoval = function(groupId){
            
            var confirmParameters = {};
            confirmParameters['title'] = 'Delete Group';
            confirmParameters['text'] = 'Are you sure to delete this grouü?';
            confirmParameters['submitButtonTitle'] = 'Delete';
            confirmParameters['submitFunction'] = function(){
                var callback = function(){
                    $('.blueModal').hide();
                    gui.alert('The group has been removed');
                };
                api.query('api/groups/delete/', { group_id : groupId }, callback);
            };
            confirmParameters['cancelButtonTitle'] = 'Cancel';
            confirmParameters['cancelFunction'] = function(){
                //alert('cancel');
            };

            gui.confirm(confirmParameters);
        };
        this.generateMemberOptionsButton = function(group_id, user_id, admins){
            var output = '';
            output += "<div class='btn-group'>";
                output += "<button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>";
                    output += "Action <span class='caret'></span>";
                output += '</button>';
             
                output += "<ul class='dropdown-menu' role='menu'>";
                    if($.inArray(''+user_id, admins) === -1){
                      output += "<li><a href='#' onclick='groups.makeUserAdmin("+group_id+","+user_id+");'>Make Admin</a></li>";
                    }else{
                      output += "<li><a href='#' onclick='groups.removeFromAdmins("+group_id+","+user_id+");'>Remove from Admins</a></li>";
                    }
                    
                    
                  output += "<li class='divider'></li>";
                    output += "<li><a href='#' onclick='groups.deleteUserFromGroup("+group_id+","+user_id+");'>Remove from group</a></li>";
                output += '</ul>';
            output += '</div>';
            
            
            return output;
        };
        this.showUpdateGroupForm = function(group_id){
            var formModal = new gui.modal();
            var groupData = groups.getData(group_id);
            var admins = groupData['admin'];
            
            var admins = admins.split(';');

            var fieldArray = [];
            var options = [];
            options['headline'] = '';
            options['buttonTitle'] = 'Save';
            options['noButtons'] = true;

            var field0 = [];
            field0['caption'] = 'Title';
            field0['inputName'] = 'title';
            field0['type'] = 'text';
            field0['value'] = groupData['title'];
            fieldArray[0] = field0;

            var captions = ['Public', 'Private'];
            var type_ids = ['1', '0'];

            var field1 = [];
            field1['caption'] = 'Type';
            field1['inputName'] = 'type';
            field1['values'] = type_ids;
            field1['captions'] = captions;
            field1['type'] = 'dropdown';
            field1['preselected'] = groupData['public'];
            fieldArray[1] = field1;
            
            var checked = '';
            if(groupData['membersInvite'] === '1'){
                checked = 'checked';
            }
            
            var field2 = [];
            field2['caption'] = '';
            field2['value'] = "<input type='checkBox' name='membersInvite' id='membersInvite' value='1' "+checked+"> Allow members to invite users";
            field2['type'] = 'html';
            fieldArray[2] = field2;

            var field3 = [];
            field3['caption'] = 'Description';
            field3['inputName'] = 'description';
            field3['type'] = 'text';
            field3['value'] = groupData['description'];
            fieldArray[3] = field3;
            
            var buddies = groups.getUsers(group_id);
            var html = '<ul>';
            $.each(buddies,function(index, value){
                html += "<li>"+useridToUsername(value)+" "+groups.generateMemberOptionsButton(group_id, value, admins)+"</li>";
            });
            html += '<ul>';
            
            var field4 = [];
            field4['caption'] = 'Users';
            field4['type'] = 'html';
            field4['value'] = html;
            fieldArray[4] = field4;
            
            var field5 = [];
            field5['caption'] = 'Delete Group';
            field5['inputName'] = 'deleteGroup';
            field5['value'] = 'Delete Group';
            field5['type'] = 'button';
            field5['actionFunction'] = 'groups.verifyGroupRemoval(\''+group_id+'\')';
            fieldArray[5] = field5;
            
            


            var modalOptions = {};
            modalOptions['buttonTitle'] = 'Update Group';

            modalOptions['action'] = function(){
                var callback = function(){
                    jsAlert('', 'The group has been updated');
                    $('.blueModal').remove();
                };
                
                
                //needs to be done
                
                
                
                var invitedUsers;
                groups.update(group_id, $('.blueModal #title').val(), $('.blueModal #description').val(), $('.blueModal #type').val(),  $('.blueModal #membersInvite').is(':checked'));
            };
            formModal.init('Update Group', '<div id="updateGroupFormContainter"></div>', modalOptions);
            gui.createForm('#updateGroupFormContainter',fieldArray, options);
            $('.dropdown-toggle').dropdown();
        };
        
        
        this.showCreateGroupForm = function(){
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

            var captions = ['Public', 'Private'];
            var type_ids = ['1', '0'];

            var field1 = [];
            field1['caption'] = 'Type';
            field1['inputName'] = 'type';
            field1['values'] = type_ids;
            field1['captions'] = captions;
            field1['type'] = 'dropdown';
            fieldArray[1] = field1;

            var field2 = [];
            field2['caption'] = 'Description';
            field2['inputName'] = 'description';
            field2['type'] = 'text';
            fieldArray[2] = field2;

            
            var buddies = buddylist.getBuddies();
            var html = '';
            if(buddies.length === 0){
                html += 'You have no user in your buddylist';
            }
            html += '<ul>';
            $.each(buddies,function(index, value){
                html += "<li><input type='checkbox' class='invitedBuddy' value='"+value+"'> "+useridToUsername(value)+"</li>";
            });
            html += '<ul>';
            var field3 = [];
            field3['caption'] = 'Invite Users';
            field3['type'] = 'html';
            field3['value'] = html;
            fieldArray[3] = field3;



            var modalOptions = {};
            modalOptions['buttonTitle'] = 'Create Group';

            modalOptions['action'] = function(){
                var callback = function(){
                    jsAlert('', 'The group has been created');
                    $('.blueModal').remove();
                };
                var invitedUsers = [];
                $('.blueModal .invitedBuddy').each(function(){
                   invitedUsers.push($(this).val()); 
                });
                groups.create($('.blueModal #title').val(), $('.blueModal #type').val(), $('.blueModal #description').val(), invitedUsers);
            };
            formModal.init('Update CreateGroup', '<div id="createGroupFormContainer"></div>', modalOptions);
            gui.createForm('#createGroupFormContainer',fieldArray, options);
        };
        
        //join public group or accept invitation
        this.join = function(group_id){
            var callback = function(){
                $('#profileWrap.group_'+group_id+' #joinButton .btn').hide();
                $('#favTab_Group').load('doit.php?action=showUserGroups');
                updateDashbox('group');
            };
            api.query('api/groups/join/', { group_id : group_id}, callback);
        };
        
        //decline invitation
        this.declineRequest = function(group_id){
            alert('needs to be written');
        };
	
};

var modal =  new function() {
			    this.html;
			    this.create = function (title, content, action) {
			    	this.html = '';
			    	this.html += '<div class="blueModal border-radius container">';
	            		this.html += '<header>';
	            			this.html += title;
	            			this.html += '<a class="modalClose" onclick="$(\'.blueModal\').remove();">X</a>';
	            		this.html += '</header>';
	            		this.html += '<div class="content">';
	            		this.html += content;
	            		this.html += '</div>';
	            		this.html += '<footer>';
	            		
	                 		this.html += '<a href="#" onclick="$(\'.blueModal\').remove(); return false;" class="btn pull-left">Close</a>';
	                 		if(typeof action !== 'undefined'){
	                			this.html += '<a href="#" id="action" class="btn btn-primary pull-right">&nbsp;&nbsp;'+action[1]+'&nbsp;&nbsp;</a>';
	                 		}
	            		
	            		this.html += '</footer>';
	            		
                                this.html += '</div>';
                                $('.blueModal').remove();
                                $('#popper').append(this.html);

                                if(typeof action !== 'undefined'){
                                        $('.blueModal #action').click(function(){
                                                action[0]();
                                        });
                                }
			    };
			};
       
function universeTime(timestamp){
    var time = Math.floor(Date.now() / 1000);
    
    var unTime;
    
     var difference = (time - timestamp);
     if(difference < 60){
         unTime = "just";
     } else if(difference > 60 && difference < 600){
         unTime = "some minutes ago";
     } else if(difference > 600 && difference < 3600){
         unTime = Math.floor(difference / 60);
         unTime = unTime+" minutes ago";
     } else if(difference > 3600 && difference < 3600*24){
         unTime = "one day ago";
     } else if(difference > 3600*24 && difference < 3600*24*31){
         var udTime = Math.floor(difference / 86400);
         unTime = udTime+" days ago";
     } else if(difference > 3600*24*31){
         unTime = "more than one month ago";
     }
     
     return unTime;
};
       
function universeText(string){
    
    string = string.replace(":'(", '<a class="smiley smiley1"></a>');
    string = string.replace(":'(", '<a class="smiley smiley1"></a>');//crying smilye /&#039; = '
    string = string.replace(':|', '<a class="smiley smiley2"></a>');
    string = string.replace(';)', '<a class="smiley smiley3"></a>');
    string = string.replace(':P', '<a class="smiley smiley4"></a>');
    string = string.replace(':-D', '<a class="smiley smiley5"></a>');
    string = string.replace(':D', '<a class="smiley smiley5"></a>');
    string = string.replace(':)', '<a class="smiley smiley6"></a>');
    string = string.replace(':(', '<a class="smiley smiley7"></a>');
    string = string.replace(':-*', '<a class="smiley smiley8"></a>');
    string = string.replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4");
       // # Links
        //$str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);
   return string;
};
       
var Feed = function(type, $selector){
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
    $($selector).html(this.init(type));
};

function proofLogin(){
    var result;
    $.ajax({
          url: 'api.php?action=proofLogin',
          type: "GET",
          async: false,
          success: function(data) {
              result = data;
          }
    });
              if(result == '1'){
                  return true;
              }else{
                  return false;
              }
}

function getUserPicture(request){
            debug.log('getUserPicture initizialized with request'+request);
			            var post;
			            var userid;
			            if(is_numeric(request)){
                                        debug.log('     numeric request');
			                userid = request;
			                //check if username is stored
			                if(typeof userPictures[userid] !== 'undefined'){
			                    //return stored username
                                            
                                            debug.log('     username for userid '+userid+'is stored:'+userPictures[userid]);
			                    return userPictures[userid];
			                }else{
                                            
			                    post = request;
			                }
			            }else{
                                        
                                        debug.log('     string request - request should be a username');
			                post = request;
			            }
			            
			            //load data from sercer
			            var result = '';
                                    
                                    debug.log('     ajax request initialized');
			            $.ajax({
			                url:sourceURL+"/api.php?action=getUserPicture",
			                async: false,  
					type: "POST",
					data: { request : post },
			                success:function(data) { result = data;
                                        
                                        debug.log('     userpicture request successfull');
                                        }
			            });
			            
			            if(is_numeric(request)){
                                        
                                        debug.log('     numeric request');
			                if(result.length > 0){
			                    
			                }
			                userPictures[userid]=result;
			            }else{
			                var response = new Array();
			                
			                var userPictureObject = JSON.parse(result);
			                $.each(userPictureObject, function(index, value) {
			                        //add value to userPictures var
			                        userPictures[index]=htmlentities(value);
			                        response[index]=htmlentities(value);
			                    });
			                
			                
			            }
			            if(is_numeric(request)){
                                        
                                        debug.log('     return numeric response');
			                return userPictures[userid];
			            }else{
			                return response;
			            }
                                    
                                    
				
			}
			
function searchUserByString(string, limit){
    var result = [];
    $.ajax({
	              url:sourceURL+"/api.php?action=searchUserByString",
	              async: false,  
	              type: "POST",
	              data: { string : string, limit : limit },
	              success:function(data) {
		              if(data === '"null"')
		                  return false;
		              else{
			              var res = JSON.parse(data);
			              if(res.length !== 0 && res != null){
			                
			                result = res;
			                
			              }else{
			              	result = false;
			              }
		              }
	              }
	            });
    return result;
}

function jsAlert(type, message){
	              	var alertClass;
	              	if(empty(type)){
	              		alertClass = 'alert-info';
	              	}else if(type == 'success'){
	              		alertClass = 'alert-success';
	              	}else if(type == 'error'){
	              		alertClass = 'alert-error';
	              	}
	              	
	              	$('#alerter').append('<div class="alert '+alertClass+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>');
	              	$('.alert').delay(8000).fadeOut(function(){
	              		$(this).remove();
	              	});
              }
      

       
//encryption functions

//not in use because of async
function generateAsymKeyPair(){
      var keySize = 1024;
      var crypt;
      var ret = [];
      crypt = new JSEncrypt({default_key_size: keySize});

        crypt.getKey(function () {
      		ret['privateKey'] = crypt.getPrivateKey();
      		ret['publicKey'] = crypt.getPublicKey();
        });
      
      
	}

function getSalt(type, itemId, key){
			var encryptedSalt = '';
			$.ajax({
			  url:"api.php?action=getSalt",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     encryptedSalt = data; 
			  }
			});
	    	var salt = sec.symDecrypt(key, encryptedSalt); //encrypt salt using key
	    	return salt;
}

function createSalt(type, itemId, receiverType, receiverId, salt){
			var ret;
			$.ajax({
			  url:"api.php?action=createSalt",
			  async: false,  
			  type: "POST",
			  data: { type: type, itemId: itemId, receiverType: receiverType, receiverId: receiverId, salt: salt },
			  success:function(data) {
			     ret = data; 
			  }
			});
			
			return ret;
	}

var hash = new function(){
		this.MD5 = function(string){
			var hash = CryptoJS.MD5(string);
			return hash.toString(CryptoJS.enc.Hex);
		};
		this.SHA512 = function(string){
			var hash = CryptoJS.SHA512(string);
			return hash.toString(CryptoJS.enc.Hex);
		};
	};

//different to the one in the guest.js (the one in the guest.js needs to be deleted and the functions on offline page(login, updatepass need to be checked...))
var cypher = new function(){
	
	this.generateRand = function(){
		return hash.SHA512(randomString(64, '#aA'));
	};
	
	this.getKey = function(type, typeId, shaPass){
		var salt = getSalt(type, typeId, shaPass);
	    var response = hash.SHA512(shaPass+salt);
	    return response;
	};
	
	/*returns pwHash/salt and keyHash/salt. for a user*/
	this.createKeysForUser = function(pass){
		var shaPass = hash.SHA512(pass);
		
		var authSaltDecrypted = cypher.generateRand();
		var keySaltDecrypted = cypher.generateRand();
		
                var authHash = hash.SHA512(shaPass+authSaltDecrypted);
                var keyHash = hash.SHA512(shaPass+keySaltDecrypted);
		
		var authSaltEncrypted = sec.symEncrypt(shaPass, authSaltDecrypted);
		var keySaltEncrypted = sec.symEncrypt(shaPass, keySaltDecrypted);
		
		var result = new Object();
			result['authHash'] = authHash;
			result['keyHash'] = keyHash;
			result['authSaltEncrypted'] = authSaltEncrypted;
			result['keySaltEncrypted'] = keySaltEncrypted;
		
		return result;
		
	};
	
	this.getPrivateKey = function(type, itemId){
	    var privateKey;
            var index = type+'_'+itemId;
            if(typeof privateKeys[index] === 'undefined'){
                    var encryptedKey = '';
			$.ajax({
			  url:"api.php?action=getPrivateKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     encryptedKey = data; 
			  }
			});
		
				var shaPass = localStorage.currentUser_shaPass;
			
			var salt = getSalt('privateKey', itemId, shaPass);
		    var keyHash = hash.SHA512(shaPass+salt);
			
	    	privateKey = sec.symDecrypt(keyHash, encryptedKey); //encrypt private Key using password
                privateKeys[index] = privateKey;
            }else{
                
                privateKey = privateKeys[index];
                
            }
	    	return privateKey;
	    };
            
        this.getPublicKey = function(type, itemId){
			var key = '';
			$.ajax({
			  url:"api.php?action=getPublicKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     key = data; 
			  }
			});
	    	return key;
	};
	
};

var sec =  new function() {
		
    //standard password cypher used in processRegistration(), login() and updatePassword();
    this.passwordCypher = function (password, type, itemId, salt) {

                                    //md5 has to be replaced with more secure hashing function
                                            var passwordHashMD5  = hash.MD5(password);

                                            if(type.length > 0){
                                                    var salt = getSalt(type, itemId, passwordHashMD5); //get auth salt, using md5 hash as key
                                            }


                                            var passwordHash = hash.SHA512(salt+passwordHashMD5);;

                                    var keyHash =  hash.SHA512(passwordHashMD5+salt);

                                        return [passwordHash, passwordHashMD5, keyHash, salt];
                                };

    this.getPrivateKey = function(type, itemId, salt, password){
                                    return cypher.getPrivateKey(type, itemId, salt, password);
    };

    this.symEncrypt = function(key, message){
        var msg;
        msg = CryptoJS.AES.encrypt(message, key);
        return String(msg);
    };
    this.symDecrypt = function(key, message){
        var msg;
        msg = CryptoJS.AES.decrypt(message, key);
        var output = CryptoJS.enc.Utf8.stringify(msg);
        return String(output);
    };

    this.asymEncrypt = function(publicKey, message){
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(publicKey);
        return encrypt.encrypt(message);
    };

    this.asymDecrypt = function(privateKey, encryptedMessage){
        var message;
        var decrypt = new JSEncrypt();
        decrypt.setPrivateKey(privateKey);
        message = decrypt.decrypt(encryptedMessage);
        return message;
    };


    this.randomString = function(){
			    	return hash.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
			    	
			    };
};
var tabs = function(parentIdentifier){
    this.parentIdentifier = parentIdentifier;
    this.tabHistory = [];
		this.init = function(){
                    parentIdentifier = this.parentIdentifier;
			$(parentIdentifier).append('<div class="tabFrame"><header><ul></ul></header></div>');
                        
		};
                this.initClicks = function(){
                    parentIdentifier = this.parentIdentifier;
                    var classVar = this;
                    $('.tabFrame>header li').click(function(){
                            var tabId = $(this).attr('data-tab');
                            var tabParentIdentifier = $(this).attr('data-parent-identifier');
                            classVar.showTab(tabId);
                            
                            $(parentIdentifier+' .tabFrame header ul li').removeClass('active');
                            $(this).addClass('active');
                    });
                    
                    $('.tabFrame>header li .close').click(function(){
                        var tabId = $(this).parent('li').attr('data-tab');
                        var tabParentIdentifier = $(this).parent('li').attr('data-parent-identifier');
                        classVar.removeTab(tabId);
                    });
                };
		this.addTab = function(title, contentType, content, onClose){
                    parentIdentifier = this.parentIdentifier;
			var numberOfTabs = $(parentIdentifier+' .tabFrame .tab').length;
                        
                        var headerId = randomString(6, '#aA');
                        
			$(parentIdentifier+' .tabFrame header ul').append('<li id='+headerId+' data-tab="'+(numberOfTabs+1)+'" data-parent-identifier="'+parentIdentifier+'" data-title="'+title+'" class="active">'+title+'<span class="close">x</span></li>');

                        $(parentIdentifier+' .tabFrame .tab').hide();
                        
                        if(typeof onClose === 'function'){
                            $('#'+headerId+' .close').click(function(){
                                onClose();
                            });
                        }
                        
                        this.tabHistory.push(numberOfTabs+1);
                        $(parentIdentifier+' .tabFrame').append('<div class="tab tab_'+(numberOfTabs+1)+'">'+content+'</div>');
                        this.initClicks();
		};
                this.getTabByTitle = function(tabTitle){
                    parentIdentifier = this.parentIdentifier;
                    var ret;
                    $(parentIdentifier+' .tabFrame header ul li').each(function(){
                        if($(this).attr('data-title') == tabTitle){
                            ret = $(this).attr('data-tab');
                        }
                    });
                            return ret;
                };
		this.showTab = function(tab){
                    this.tabHistory.push(tab);
                    parentIdentifier = this.parentIdentifier;
                    $(parentIdentifier+' .tabFrame .tab').hide();
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab).show();
		};
                
                //is used after closing a tab to show the last tab that was shown
                this.showLastTab = function(current_tab){
                    var last_tab;
                    var i = this.tabHistory.length-1;
                    
                    
                    //counts down until i = 0 (each array element)
                    //proofs if tab exists
                    //until last tab isnt the current tab or undefined 
                    while((last_tab==current_tab||last_tab==undefined)&&i!==0){
                        if(this.tabExists(this.tabHistory[i]))
                            last_tab = this.tabHistory[i];
                        i--;
                    }
                    this.showTab(last_tab);
                };
                this.tabExists = function(tab_identifier){
                    parentIdentifier = this.parentIdentifier;
                    if($(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).length > 0){
                        return true;
                    }else{
                        return false;
                    }
                };
		this.updateTabContent = function(tab_identifier ,content){
                    if(parseInt(tab_identifier) != tab_identifier){
                        tab_identifier = this.getTabByTitle(tab_identifier);
                    }
                    parentIdentifier = this.parentIdentifier;
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).html(content);
			
		};
		this.removeTab = function(tab_identifier){
                    parentIdentifier = this.parentIdentifier;
                    
                    
                    if($(parentIdentifier+' .tabFrame header ul li').length === 1){
                        return true;
                    }
                    
                    this.showLastTab(tab_identifier);
                    $(parentIdentifier+' .tabFrame header ul li').each(function(){
                        if($(this).attr('data-tab') == tab_identifier)
                            $(this).remove();
                    });
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).remove();
                    
		};
		this.moveTab = function(parentIdentifier, tab){
			
		};
};

	
function getPublicKey(type, itemId){
			var key = '';
			$.ajax({
			  url:"api.php?action=getPublicKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     key = data; 
			  }
			});
	    	return key;
	}
	
function storeMessageKey(messageId, key){
        messageKeys[messageId] = key;
    }

function getStoredKey(messageId){
        return messageKeys[messageId];
    }
    
function isStored(messageId){
        if(messageKeys[messageId] !== undefined){
            return true;
        }else{
            return false;
        }
    }
    
    
              
//general functions
        

function empty(value){
	  	if(value.length == 0) {
	  		return true;
	  	}else{
	  		return false;
	  	}
	  }

function maxLength(string, maxlength){
	  	if(string.length <= maxlength)
	  		return string;
	  	else{
	  		return string.substring(0, maxlength-5)+'(..)';
	  		
	  	}
	  }
	  
//updates 
function updatePictureStatus(userId, borderColor){
    $('.userPicture_'+userId).css('border-color', borderColor);
}

function showContent(content, title){
  reader.applicationVar.show();
  reader.tabs.addTab(title, '',gui.loadPage('showContent.php?content='+content));
}

function mousePop(type, id, html){
      $('.mousePop').remove();
      if($('#mousePop_'+type+id).length == 0){   
      $("#popper").load("doit.php?action=mousePop&type=&id=&html", {
          'type': type,
          'id':id,
          'html':html
      });
      }
}
      
function getCaretPosition(editableDiv) {
     var caretPos = 0, containerEl = null, sel, range;
     if (window.getSelection) {
         sel = window.getSelection();
         if (sel.rangeCount) {
             range = sel.getRangeAt(0);
             if (range.commonAncestorContainer.parentNode == editableDiv) {
                 caretPos = range.endOffset;
             }
         }
     } else if (document.selection && document.selection.createRange) {
         range = document.selection.createRange();
         if (range.parentElement() == editableDiv) {
             var tempEl = document.createElement("span");
             editableDiv.insertBefore(tempEl, editableDiv.firstChild);
             var tempRange = range.duplicate();
             tempRange.moveToElementText(tempEl);
             tempRange.setEndPoint("EndToEnd", range);
             caretPos = tempRange.text.length;
         }
     }
     return caretPos;
}
            
function getElementsByClassName(node,classname) {
		  if (node.getElementsByClassName) { // use native implementation if available
		    return node.getElementsByClassName(classname);
		  } else {
		    return (function getElementsByClass(searchClass,node) {
		        if ( node == null )
		          node = document;
		        var classElements = [],
		            els = node.getElementsByTagName("*"),
		            elsLen = els.length,
		            pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)"), i, j;
		
		        for (i = 0, j = 0; i < elsLen; i++) {
		          if ( pattern.test(els[i].className) ) {
		              classElements[j] = els[i];
		              j++;
		          }
		        }
		        return classElements;
		    })(classname, node);
		  }
		}


//loads URL into an iFrame
function loadIframe(iframeName, url) {
                    $('#' + iframeName).html('');
                    var $iframe = $('#' + iframeName);
                    if ( $iframe.length ) {
                        $iframe.attr('src',url);   
                        return false;
                    }
                    return true;
                }
//api connection stuff
function useridToUsername(id){
		if(usernames[id] == undefined){
			
		    var result="";
		    $.ajax({
		      url:"api.php?action=useridToUsername",
		      async: false,  
			  type: "POST",
			  data: { request : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   usernames[id] = result;
		   return result;
		}else{
			return usernames[id];
		}
		
	}

function usernameToUserid(username){
			
		    var result="";
		    $.ajax({
		      url:"api.php?action=usernameToUserid",
		      async: false,  
			  type: "POST",
			  data: { username : username },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   usernames[result] = username;
		   return result;
		
	}

function getUserCypher(id){
		var result="";
		    $.ajax({
		      url:"api.php?action=getUserCypher",
		      async: false, 
			  type: "POST",
			  data: { userid : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   
		   return result;
	}

function getUserSalt(id){
		//returns user salt (aes encrypted with pw hash)
		
		
		var result="";
		    $.ajax({
		      url:"api.php?action=getUserSalt",
		      async: false, 
			  type: "POST",
			  data: { userid : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   
		   return result;
	}
        
//reload


//feed
function feedLoadMore(destination ,type, user, limit){
	    $.get("doit.php?action=feedLoadMore&user="+user+"&limit="+limit+"&type="+type,function(data){
	    	$(destination).append(data);
		},'html');
	}
       
//can be deleted
function del_reloadFeed(type){
        if(type === "friends"){
            $.post('api.php?action=checkForFeeds&type=friends', function(data) {
                console.log('reloadFeed - check for new feeds');
                if(data === "1"){
                    console.log('reloadFeed - no new feeds');
                }else{
                    console.log('reloadFeed - loading new feeds..');
                    
                    
                    $(".feedMain").slideDown("200", function () {
                        $(this).load("doit.php?action=reloadMainFeed");
                        console.log('reloadFeed - new feed loaded');
                    });
                }
            });
        }
    }

//filesystem

function initUploadify(id, uploader, element, timestamp, token){
		
	    $(function() {
	            $(id).uploadify({
	                    'formData'     : {
	                            'timestamp' : timestamp,
	                            'token'     : token,
	                            'element'     : element
	                    },
	                    'swf'      : 'inc/plugins/uploadify/uploadify.swf',
	                    'uploader' : uploader,
				        'onUploadSuccess' : function(file, data, response) {
				        	
				        	if(response){
				        		eval(data); //no esta bien! que?
				        	}
				        },
	                    'onUploadError' : function(file, errorCode, errorMsg, errorString) {
	                        alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
	                    }
	            });
	    });
                                
	}
	
//reader
function toggleProfileTabs(id){
        $(".profileSlider").hide();
        $("#" + id + "").slideDown();
    }                
function toggleGroupTabs(id){
        $(".groupSlider").hide();
        $("#" + id + "").slideDown();
    }


function openFolder(folderId){
        filesystem.show();
        filesystem.openFolder(folderId);
        return false;
        
    }

function openElement(elementId, title){
        filesystem.show();
        
        filesystem.tabs.addTab(title, '',gui.loadPage('modules/filesystem/showElement.php?element='+elementId));
}

function openFile(type, typeId, title, typeInfo, extraInfo1, extraInfo2){
        
        title = 'Open '+title;
        
        //bring reader to front
        reader.applicationVar.show();
        
        
        //Link types
        if(type == 'youTube'){
        	if(extraInfo1 == undefined){
        		var extraInfo1 = '';
        	}
        	if(extraInfo2 == undefined){
        		var extraInfo2 = '';
        	}
        	if(typeInfo == undefined){
        		var typeInfo = '';
        	}
        	
        	var playlist = extraInfo1;
        	var row = extraInfo2;
        	var vId = typeInfo;
        	var linkId = typeId;
        	if(linkId.length == 0){
                         
                reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&typeInfo='+vId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1'));
 	
        	}else{
        		
                reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1'));
 
        	}return false;
        }
        
        if(type == 'RSS'){
            
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=RSS&linkId='+typeId));
            return false;
        }
        
        if(type == 'wikipedia'){
        	//typeId needs to be changed to title
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=wiki&title='+typeId));
            return false;
        }
        
        //real files
        if(type == 'UFF'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=UFF&fileId='+typeId));
            return false;
        }
        if(type == 'document' ||type == 'application/pdf' ||type == 'text/plain'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?fileId='+typeId));
            return false;
        }
        if(type == 'video' ||type == 'video/mp4' ||type == 'video/quicktime'  ){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=video&fileId='+typeId));
            return false;
        }
        if(type == 'audio' ||type == 'audio/wav' ||type == 'audio/mpeg'  ){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=audio&fileId='+typeId));
            return false;
        }
        if(type == 'image/png' ||type == 'image/jpeg' || type == 'image'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=image&fileId='+typeId));
            return false;
        }else{
            alert(type);
            return false;
        }
        return false;
    }

//zoom functions for pictures
function zoomIn(element){
       var PictureWidth = $("#viewedPicture_"+element).width();
       var newWidth = PictureWidth*1.25;
       $("#viewedPicture_"+element).css("width", newWidth);
    }

function zoomOut(element){
       var PictureWidth = $("#viewedPicture_"+element).width();
       var newWidth = PictureWidth/1.25;
       $("#viewedPicture_"+element).css("width", newWidth);
    }

	//UFF
//what you see is what you get            
function initWysiwyg(id, readOnly){
    if(readOnly == 'false'){
		        readOnly = false;
		    }
    if(readOnly == 'true'){
		        readOnly = true;
		    }
    
    var config = {
        
	extraPlugins: 'autogrow',
        // toolbarGroups: [
                                    // { name: 'document',	   groups: [ 'mode', 'document' ] },			
                                    // { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },			
                                    // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                                    // { name: 'links' }],
        removePlugins: 'resize',
        readOnly: readOnly,
        autoGrow_onStartup: true,
                    on: {
		                        instanceReady: function() {
		
		                                    //add eventlistener for onchange
		                                    this.document.on("keyup", function () {
		
		                                        //if changed update file
		                                        // ich muss ein lastudated feld zur db und eine javascript-lastupdated variable erstellen, um konflike zu vermiden
		                                        var input = $('.uffViewer_'+id).val();
                                                        UFF.write(id, input);
		                                    });
		                        }
		                    }
            };

    $('.uffViewer_'+id).ckeditor(config);
}

function initUffReader(id, content, readOnly){
		    initWysiwyg(id, readOnly);
		    
		    $('.uffViewer_'+id).val(content);
}


    
    
var UFF = new function(){
    this.write = function(file_id, input){
       api.query('api/files/uff/write/', {file_id:file_id, input:input},function(){reader.uffChecksums[file_id] = hash.MD5(input);});
    };
};
    
//opens articles out of the universe wiki
//located in reader cause it will be placed there in future
function openUniverseWikiArticle(title){
    	openURL("http://wiki.universeos.org/index.php?title="+title, title);
    }

function openURL(url, title){
    		url = encodeURI(url);
    		url = 'modules/reader/browser/?url='+url;
                
            reader.tabs.addTab(title, '',gui.loadPage(url));
            reader.applicationVar.show();
            return false;
    }
    
    
 
    
function initDashClose(){
	//init dashcloses
	$('.dashBox .dashClose').click(function(){
		$(this).parent('.dashBox').slideUp();
	});	
	
}
    
//dashboard

var dashBoard = new function(){
	
	
	
	this.view = 'down'; // up or down 
	
	this.init = function(){
		$('#dashBoard a, #dashBoard li').not('.disableToggling').click(function(){dashBoard.slideUp();});
                $("#dashBoard").draggable({
    		axis: "y", 
    		cancel : '#dashBoxFrame',
    		containment: "#dashGrid",
    		stop: function( event, ui ) {
    			if(parseInt($('#dashBoard').css('top').replace(/[^-\d\.]/g, '')) > 191)
    				$('#dashBoard').css('top', '191px');
    		}
                });
                
		var content = "<ul class=\"appList\">";
                
                $.each(applications.getList(),function(index,value){
                    content+= "<li onclick=\""+value.className+".show()\" onmouseup=\"closeDockMenu()\"><img src=\""+value.icon+"\" border=\"0\" height=\"16\">"+value.title+"</li>";
                });
            
                $('#dashBoard #scrollFrame').prepend(dashBoard.generateDashBox('Applications', content, '', 'appBox'));
                
	
	};
	
	this.slideUp = function(){
			$('#dashGrid').animate({marginBottom: 0}, 1000, function() {
				$('#dashBoard').removeClass('up');
				$('#dashBoardBG').removeClass('up');
				$('#dashBoard footer a i').removeClass('icon-chevron-down');
				$('#dashBoard footer a i').addClass('icon-chevron-up');
			});
			this.view = 'up';
	};
	this.slideDown = function(){
		
			$('#dashGrid').animate({marginBottom: -300}, 750, function() {
				$('#dashBoard').addClass('up');
				$('#dashBoardBG').addClass('up');
				$('#dashBoard footer a i').removeClass('icon-chevron-up');
				$('#dashBoard footer a i').addClass('icon-chevron-down');
			});
			this.view = 'down';
	};
	this.toggle = function(){
		if(this.view === 'up'){
			this.slideDown();
		}else if(this.view === 'down'){
			this.slideUp();
		}
	};
        
        this.generateDashBox = function(title, content, footer, id){
            var output = '';
            if(id){
                output += "<div class=\"dashBox\" id=\""+id+".Box\">";
            }
		
			output += "<a class=\"dashClose\"></a>";
			output += "<header>"+title+"</header>";
		
			output += "<div class=\"content\">"+content+"</div>";
			
			if(!empty(footer)){
			output += "<footer>"+footer+"</footer>";
			}
			
		if(id){
			output += "</div>";
		}
		return output;
        };
        
};

function updateDashbox(type){
	$('.dashBox#'+type+'Box').load('modules/desktop/updateDashboard.php?type='+type, function(){
		
		initDashClose();
	});
}

function toggleDashboard(){
	$('#dashboard:visible').slideUp();
	$('#dashboard:hidden').slideDown();
}

//rightclick
function clearMenu() { //used to make the menu disappear
                    //this function should be used at the beginning of any function that is called from the menu
                    var cssObj = {
                        'display' : 'none'
                       };
                    $(".rightclick").css(cssObj);
                }

function showMenu(id) {
                    /*  check whether the event is a right click 
                    *  because different browser (ahem IE) assign different numbers to the keys to
                    *  your mouse buttons and different values to the event, you'll have to do some evaluation
                    */
                    var rightclick; //will be set to true or false
                    if (event.button) {
                        rightclick = (event.button == 2);
                    } else if (e.button) {
                        rightclick = (event.which == 3);
                    }

                    if(rightclick) { //if the secondary mouse botton was clicked
                        $(".rightclick").hide();
                        var menu = document.getElementById("rightClick" + id + "");
                        var Event = event;
                        menu.style.position = "fixed"; //show menu
                        menu.style.display = "block"; //show menu
                        menu.style.left  = Event.clientX + "px";
                        menu.style.top = Event.clientY + "px";

                        
                        $(".rightclick").css('z-index', '99999');
                    }
                }


   
function playPlaylist(playlist, row, fileId){
                  
	              alert("lol a" + fileId + " b" + playlist + " c" + row + " ");
	              $("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1&playList=" + playlist +"&row=" + row + "");
	              play();
              }
function playFileDock(fileId){
              	$("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1");
              }
  
function nextPlaylistItem(playList, row){
             	  $("#playListPlayer").load("playListplayer.php?playList=" + playList +"&row=" + row +"");
              }


var comments = new function(){
  this.loadSubComments = function(commentId){
      
    $("#comment" + commentId + "").load("showComment.php?id=" + commentId +"");
    $("#comment" + commentId + "").toggle("slow");
  };
  this.loadFeedComments = function(feedId){
      
    $("#feed" + feedId + "").load("showComment.php?type=feed&feedid=" + feedId +"");
    $("#feed" + feedId + "").toggle("slow");
  };
};

//requires jquery
//adding service to media class:
//1. add regex to gettype

var media = function(url){
    this.URL = url;
    this.type;
    this.authData = {
        soundcloud_client_id:'84102f71034779ad4237c49fd95c388b',
        soundcloud_client_secret:'e5d37909c2fb985d66421d5d253d172b'
    };
    this.getType = function(){
        var url = this.URL;
        if(/((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(url)){
            this.type = 'youtube';
        }else if(/^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/.test(url)){
            this.type = 'vimeo';
        }else if(/^https?:\/\/(soundcloud.com|snd.sc)\/(.*)$/.test(url)){
            this.type = 'soundcloud';
        }
    };
    this.getType();
    this.getId = function(){
        var url = this.URL;
      switch(this.type){
          case 'youtube':
                var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                var match = url.match(regExp);
                if (match&&match[7].length==11){
                    return match[7];
                }else{
                    return false;
                }
                      break;
          case 'vimeo':
                var regExp = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;

                var match = url.match(regExp);

                if (match){
                    return match[2];
                }else{
                    return false;
                }
                      break;
          case 'soundcloud':
                
              
              
                      break;
      };
    };
    this.getTitle = function(){
        var data = this.getData();
        switch(this.type){
            case'youtube':
                return data.title.$t;
                break;
            case'vimeo':
                return data[0].title;
                break;
        }
    };
    this.loadPlayer = function($element, options){
        var videoID = this.getId();
        switch(this.type){
            case'youtube':
                var playerID = randomString(5,'aA#');
                //from https://developers.google.com/youtube/iframe_api_reference#Loading_a_Video_Player
                $element.html('<div id='+playerID+'><iframe id="player" type="text/html" width="640" height="390" src="http://www.youtube.com/embed/'+videoID+'?enablejsapi=1&origin=http://example.com" frameborder="0"></iframe></div>');
                // 2. This code loads the IFrame Player API code asynchronously.
                var tag = document.createElement('script');

                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                // 3. This function creates an <iframe> (and YouTube player)
                //    after the API code downloads.
                var player;
                function onYouTubeIframeAPIReady() {
                  player = new YT.Player(playerID, {
                    height: '390',
                    width: '640',
                    videoId: videoID,
                    events: {
                      'onReady': onPlayerReady,
                      'onStateChange': onPlayerStateChange
                    }
                  });
                }

                // 4. The API will call this function when the video player is ready.
                function onPlayerReady(event) {
                  if(options.autoplay)
                    event.target.playVideo();
                }

                // 5. The API calls this function when the player's state changes.
                //    The function indicates that when playing a video (state=1),
                //    the player should play for six seconds and then stop.
                var done = false;
                function onPlayerStateChange(event) {
                  if (event.data == YT.PlayerState.PLAYING && !done) {
                    setTimeout(stopVideo, 6000);
                    done = true;
                  }
                }
                function stopVideo() {
                  player.stopVideo();
                }
                break;
        };
        
    };
    //@param callback: soundcloud doesn't allow asynchronus tasks, so it requires a callback like media.getData(function(data){consoloe.log(data)});
    this.getData = function(callback){
        var authStuff = this.authData;
        var videoId = this.getId();
        switch(this.type){
            case 'youtube':
                var result;
                    $.ajax(
                    {
                            url: "https://gdata.youtube.com/feeds/api/videos/"+videoId+"?v=2&alt=json",
                            async:false,
                            success: function(data)
                            {
                                    result = data;
                            }
                    });

                    return result.entry;
                break;
            case 'vimeo':
                var result;
                    $.ajax(
                    {
                            url: "http://vimeo.com/api/v2/video/"+videoId+".json",
                            async:false,
                            success: function(data)
                            {
                                    result = data;
                            }
                    });

                    return result;
                break;
            case 'soundcloud':
    $.ajax({
        'url': "https://api.soundcloud.com/resolve.json?url="+encodeURIComponent(url)+"&client_id="+authStuff.soundcloud_client_id+'&none=none',
        'dataType': "json",
        'success': function (data) {
            callback(data);
        }
    });
                break;
        };
    };
};

function isYoutubeURL(url){
    return /((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(url);
}

function youtubeURLToVideoId(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
        return false;
    }
}

function getYoutubeData(videoId){
    var result;
    	$.ajax(
	{
		url: "https://gdata.youtube.com/feeds/api/videos/"+videoId+"?v=2&alt=json",
                async:false,
		success: function(data)
		{
			result = data;
		}
	});
        
        return result.entry;
    
}

function getYoutubeTitle(videoId){
    var data = getYoutubeData(videoId);
    return data.title.$t;
}
function loader(id, link){
                  $("#" + id + "").load("" + link + "");
              }
              
function popper(url) {
              $("#loader").load("" + url +"");
                }
              
function deleteFromPersonals(id){
                  $("#loader").load("doit.php?action=deleteFromPersonals&id=" + id + "");
              }
              

function showProfile(userId){
    var username = useridToUsername(userId);
    reader.tabs.addTab(username, '',gui.loadPage("./profile.php?user=" + userId));
    reader.applicationVar.show();
    return false;
};
  
function showPlaylist(id){
              	popper('doit.php?action=showPlaylist&id='+id);
              }
  
function startPlayer(type, typeid){
              $("#dockplayer").load("player/dockplayer.php?reload=1&" + type +"=" + typeid + "");
              }
function closeDockMenu(){
                $("#dockMenu").hide("fast");
              }
  
function clock() {
    
    var dayNames=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]
    var monthNames=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                var now = new Date();

                    var hours = now.getHours();
                    var minutes = now.getMinutes();
                    var pad = "00";
                    var day =  now.getDay();
                    var month =  now.getMonth();

                    var minutesRoundedOne = "" + minutes;
                    var minutesRoundedTwo = pad.substring(0, pad.length - minutesRoundedOne.length)+''+minutesRoundedOne;
                    var hoursRoundedOne = "" + hours;
                    var hoursRoundedTwo = pad.substring(0, pad.length - hoursRoundedOne.length) + hoursRoundedOne;

                var outStr = dayNames[day]+', '+monthNames[month]+'. '+now.getDate()+'.&nbsp;&nbsp;'+hoursRoundedTwo+':'+minutesRoundedTwo;
                $('#clockDiv').html(outStr);
                setTimeout('clock()',36000);
              }

//PLUGINS
//PLUGINS
//PLUGINS

/*
                 * AutoSuggest
                 * Copyright 2009-2010 Drew Wilson
                 * www.drewwilson.com
                 * code.drewwilson.com/entry/autosuggest-jquery-plugin
                 *
                 * Version 1.4   -   Updated: Mar. 23, 2010
                 *
                 * This Plug-In will auto-complete or auto-suggest completed search queries
                 * for you as you type. You can add multiple selections and remove them on
                 * the fly. It supports keybord navigation (UP + DOWN + RETURN), as well
                 * as multiple AutoSuggest fields on the same page.
                 *
                 * Inspied by the Autocomplete plugin by: J�rn Zaefferer
                 * and the Facelist plugin by: Ian Tearle (iantearle.com)
                 *
                 * This AutoSuggest jQuery plug-in is dual licensed under the MIT and GPL licenses:
                 *   http://www.opensource.org/licenses/mit-license.php
                 *   http://www.gnu.org/licenses/gpl.html
                 */

(function($){
        $.fn.autoSuggest = function(data, options) {
                                var defaults = { 
                                        asHtmlID: false,
                                        startText: "Enter Name Here",
                                        emptyText: "No Results Found",
                                        preFill: {},
                                        limitText: "No More Selections Are Allowed",
                                        selectedItemProp: "value", //name of object property
                                        selectedValuesProp: "value", //name of object property
                                        searchObjProps: "value", //comma separated list of object property names
                                        queryParam: "q",
                                        retrieveLimit: false, //number for 'limit' param on ajax request
                                        extraParams: "",
                                        matchCase: false,
                                        minChars: 1,
                                        keyDelay: 400,
                                        resultsHighlight: true,
                                        neverSubmit: false,
                                        selectionLimit: false,
                                        showResultList: true,
                                        start: function(){},
                                        selectionClick: function(elem){},
                                        selectionAdded: function(elem){},
                                        selectionRemoved: function(elem){ elem.remove(); },
                                        formatList: false, //callback function
                                        beforeRetrieve: function(string){ return string; },
                                        retrieveComplete: function(data){ return data; },
                                        resultClick: function(data){},
                                        resultsComplete: function(){}
                                };  
                                var opts = $.extend(defaults, options);	 	

                                var d_type = "object";
                                var d_count = 0;
                                if(typeof data == "string") {
                                        d_type = "string";
                                        var req_string = data;
                                } else {
                                        var org_data = data;
                                        for (k in data) if (data.hasOwnProperty(k)) d_count++;
                                }
                                if((d_type == "object" && d_count > 0) || d_type == "string"){
                                        return this.each(function(x){
                                                if(!opts.asHtmlID){
                                                        x = x+""+Math.floor(Math.random()*100); //this ensures there will be unique IDs on the page if autoSuggest() is called multiple times
                                                        var x_id = "as-input-"+x;
                                                } else {
                                                        x = opts.asHtmlID;
                                                        var x_id = x;
                                                }
                                                opts.start.call(this);
                                                var input = $(this);
                                                input.attr("autocomplete","off").addClass("as-input").attr("id",x_id).val(opts.startText);
                                                var input_focus = false;

                                                // Setup basic elements and render them to the DOM
                                                input.wrap('<ul class="as-selections" id="as-selections-'+x+'"></ul>').wrap('<li class="as-original" id="as-original-'+x+'"></li>');
                                                var selections_holder = $("#as-selections-"+x);
                                                var org_li = $("#as-original-"+x);				
                                                var results_holder = $('<div class="as-results" id="as-results-'+x+'"></div>').hide();
                                                var results_ul =  $('<ul class="as-list"></ul>');
                                                var values_input = $('<input type="hidden" class="as-values" name="as_values_'+x+'" id="as-values-'+x+'" />');
                                                var prefill_value = "";
                                                if(typeof opts.preFill == "string"){
                                                        var vals = opts.preFill.split(",");					
                                                        for(var i=0; i < vals.length; i++){
                                                                var v_data = {};
                                                                v_data[opts.selectedValuesProp] = vals[i];
                                                                if(vals[i] != ""){
                                                                        add_selected_item(v_data, "000"+i);	
                                                                }		
                                                        }
                                                        prefill_value = opts.preFill;
                                                } else {
                                                        prefill_value = "";
                                                        var prefill_count = 0;
                                                        for (k in opts.preFill) if (opts.preFill.hasOwnProperty(k)) prefill_count++;
                                                        if(prefill_count > 0){
                                                                for(var i=0; i < prefill_count; i++){
                                                                        var new_v = opts.preFill[i][opts.selectedValuesProp];
                                                                        if(new_v == undefined){ new_v = ""; }
                                                                        prefill_value = prefill_value+new_v+",";
                                                                        if(new_v != ""){
                                                                                add_selected_item(opts.preFill[i], "000"+i);	
                                                                        }		
                                                                }
                                                        }
                                                }
                                                if(prefill_value != ""){
                                                        input.val("");
                                                        var lastChar = prefill_value.substring(prefill_value.length-1);
                                                        if(lastChar != ","){ prefill_value = prefill_value+","; }
                                                        values_input.val(","+prefill_value);
                                                        $("li.as-selection-item", selections_holder).addClass("blur").removeClass("selected");
                                                }
                                                input.after(values_input);
                                                selections_holder.click(function(){
                                                        input_focus = true;
                                                        input.focus();
                                                }).mousedown(function(){ input_focus = false; }).after(results_holder);	

                                                var timeout = null;
                                                var prev = "";
                                                var totalSelections = 0;
                                                var tab_press = false;

                                                // Handle input field events
                                                input.focus(function(){			
                                                        if($(this).val() == opts.startText && values_input.val() == ""){
                                                                $(this).val("");
                                                        } else if(input_focus){
                                                                $("li.as-selection-item", selections_holder).removeClass("blur");
                                                                if($(this).val() != ""){
                                                                        results_ul.css("width",selections_holder.outerWidth());
                                                                        results_holder.show();
                                                                }
                                                        }
                                                        input_focus = true;
                                                        return true;
                                                }).blur(function(){
                                                        if($(this).val() == "" && values_input.val() == "" && prefill_value == ""){
                                                                $(this).val(opts.startText);
                                                        } else if(input_focus){
                                                                $("li.as-selection-item", selections_holder).addClass("blur").removeClass("selected");
                                                                results_holder.hide();
                                                        }				
                                                }).keydown(function(e) {
                                                        // track last key pressed
                                                        lastKeyPressCode = e.keyCode;
                                                        first_focus = false;
                                                        switch(e.keyCode) {
                                                                case 38: // up
                                                                        e.preventDefault();
                                                                        moveSelection("up");
                                                                        break;
                                                                case 40: // down
                                                                        e.preventDefault();
                                                                        moveSelection("down");
                                                                        break;
                                                                case 8:  // delete
                                                                        if(input.val() == ""){							
                                                                                var last = values_input.val().split(",");
                                                                                last = last[last.length - 2];
                                                                                selections_holder.children().not(org_li.prev()).removeClass("selected");
                                                                                if(org_li.prev().hasClass("selected")){
                                                                                        values_input.val(values_input.val().replace(","+last+",",","));
                                                                                        opts.selectionRemoved.call(this, org_li.prev());
                                                                                } else {
                                                                                        opts.selectionClick.call(this, org_li.prev());
                                                                                        org_li.prev().addClass("selected");		
                                                                                }
                                                                        }
                                                                        if(input.val().length == 1){
                                                                                results_holder.hide();
                                                                                 prev = "";
                                                                        }
                                                                        if($(":visible",results_holder).length > 0){
                                                                                if (timeout){ clearTimeout(timeout); }
                                                                                timeout = setTimeout(function(){ keyChange(); }, opts.keyDelay);
                                                                        }
                                                                        break;
                                                                case 9: case 188:  // tab or comma
                                                                        tab_press = true;
                                                                        var i_input = input.val().replace(/(,)/g, "");
                                                                        if(i_input != "" && values_input.val().search(","+i_input+",") < 0 && i_input.length >= opts.minChars){	
                                                                                e.preventDefault();
                                                                                var n_data = {};
                                                                                n_data[opts.selectedItemProp] = i_input;
                                                                                n_data[opts.selectedValuesProp] = i_input;																				
                                                                                var lis = $("li", selections_holder).length;
                                                                                add_selected_item(n_data, "00"+(lis+1));
                                                                                input.val("");
                                                                        }
                                                                case 13: // return
                                                                        tab_press = false;
                                                                        var active = $("li.active:first", results_holder);
                                                                        if(active.length > 0){
                                                                                active.click();
                                                                                results_holder.hide();
                                                                        }
                                                                        if(opts.neverSubmit || active.length > 0){
                                                                                e.preventDefault();
                                                                        }
                                                                        break;
                                                                default:
                                                                        if(opts.showResultList){
                                                                                if(opts.selectionLimit && $("li.as-selection-item", selections_holder).length >= opts.selectionLimit){
                                                                                        results_ul.html('<li class="as-message">'+opts.limitText+'</li>');
                                                                                        results_holder.show();
                                                                                } else {
                                                                                        if (timeout){ clearTimeout(timeout); }
                                                                                        timeout = setTimeout(function(){ keyChange(); }, opts.keyDelay);
                                                                                }
                                                                        }
                                                                        break;
                                                        }
                                                });

                                                function keyChange() {
                                                        // ignore if the following keys are pressed: [del] [shift] [capslock]
                                                        if( lastKeyPressCode == 46 || (lastKeyPressCode > 8 && lastKeyPressCode < 32) ){ return results_holder.hide(); }
                                                        var string = input.val().replace(/[\\]+|[\/]+/g,"");
                                                        if (string == prev) return;
                                                        prev = string;
                                                        if (string.length >= opts.minChars) {
                                                                selections_holder.addClass("loading");
                                                                if(d_type == "string"){
                                                                        var limit = "";
                                                                        if(opts.retrieveLimit){
                                                                                limit = "&limit="+encodeURIComponent(opts.retrieveLimit);
                                                                        }
                                                                        if(opts.beforeRetrieve){
                                                                                string = opts.beforeRetrieve.call(this, string);
                                                                        }
                                                                        $.getJSON(req_string+"?"+opts.queryParam+"="+encodeURIComponent(string)+limit+opts.extraParams, function(data){ 
                                                                                d_count = 0;
                                                                                var new_data = opts.retrieveComplete.call(this, data);
                                                                                for (k in new_data) if (new_data.hasOwnProperty(k)) d_count++;
                                                                                processData(new_data, string); 
                                                                        });
                                                                } else {
                                                                        if(opts.beforeRetrieve){
                                                                                string = opts.beforeRetrieve.call(this, string);
                                                                        }
                                                                        processData(org_data, string);
                                                                }
                                                        } else {
                                                                selections_holder.removeClass("loading");
                                                                results_holder.hide();
                                                        }
                                                }
                                                var num_count = 0;
                                                function processData(data, query){
                                                        if (!opts.matchCase){ query = query.toLowerCase(); }
                                                        var matchCount = 0;
                                                        results_holder.html(results_ul.html("")).hide();
                                                        for(var i=0;i<d_count;i++){				
                                                                var num = i;
                                                                num_count++;
                                                                var forward = false;
                                                                if(opts.searchObjProps == "value") {
                                                                        var str = data[num].value;
                                                                } else {	
                                                                        var str = "";
                                                                        var names = opts.searchObjProps.split(",");
                                                                        for(var y=0;y<names.length;y++){
                                                                                var name = $.trim(names[y]);
                                                                                str = str+data[num][name]+" ";
                                                                        }
                                                                }
                                                                if(str){
                                                                        if (!opts.matchCase){ str = str.toLowerCase(); }				
                                                                        if(str.search(query) != -1 && values_input.val().search(","+data[num][opts.selectedValuesProp]+",") == -1){
                                                                                forward = true;
                                                                        }	
                                                                }
                                                                if(forward){
                                                                        var formatted = $('<li class="as-result-item" id="as-result-item-'+num+'"></li>').click(function(){
                                                                                        var raw_data = $(this).data("data");
                                                                                        var number = raw_data.num;
                                                                                        if($("#as-selection-"+number, selections_holder).length <= 0 && !tab_press){
                                                                                                var data = raw_data.attributes;
                                                                                                input.val("").focus();
                                                                                                prev = "";
                                                                                                add_selected_item(data, number);
                                                                                                opts.resultClick.call(this, raw_data);
                                                                                                results_holder.hide();
                                                                                        }
                                                                                        tab_press = false;
                                                                                }).mousedown(function(){ input_focus = false; }).mouseover(function(){
                                                                                        $("li", results_ul).removeClass("active");
                                                                                        $(this).addClass("active");
                                                                                }).data("data",{attributes: data[num], num: num_count});
                                                                        var this_data = $.extend({},data[num]);
                                                                        if (!opts.matchCase){ 
                                                                                var regx = new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + query + ")(?![^<>]*>)(?![^&;]+;)", "gi");
                                                                        } else {
                                                                                var regx = new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + query + ")(?![^<>]*>)(?![^&;]+;)", "g");
                                                                        }

                                                                        if(opts.resultsHighlight){
                                                                                this_data[opts.selectedItemProp] = this_data[opts.selectedItemProp].replace(regx,"<em>$1</em>");
                                                                        }
                                                                        if(!opts.formatList){
                                                                                formatted = formatted.html(this_data[opts.selectedItemProp]);
                                                                        } else {
                                                                                formatted = opts.formatList.call(this, this_data, formatted);	
                                                                        }
                                                                        results_ul.append(formatted);
                                                                        delete this_data;
                                                                        matchCount++;
                                                                        if(opts.retrieveLimit && opts.retrieveLimit == matchCount ){ break; }
                                                                }
                                                        }
                                                        selections_holder.removeClass("loading");
                                                        if(matchCount <= 0){
                                                                results_ul.html('<li class="as-message">'+opts.emptyText+'</li>');
                                                        }
                                                        results_ul.css("width", selections_holder.outerWidth());
                                                        results_holder.show();
                                                        opts.resultsComplete.call(this);
                                                }

                                                function add_selected_item(data, num){
                                                        values_input.val(values_input.val()+data[opts.selectedValuesProp]+",");
                                                        var item = $('<li class="as-selection-item" id="as-selection-'+num+'"></li>').click(function(){
                                                                        opts.selectionClick.call(this, $(this));
                                                                        selections_holder.children().removeClass("selected");
                                                                        $(this).addClass("selected");
                                                                }).mousedown(function(){ input_focus = false; });
                                                        var close = $('<a class="as-close">&times;</a>').click(function(){
                                                                        values_input.val(values_input.val().replace(","+data[opts.selectedValuesProp]+",",","));
                                                                        opts.selectionRemoved.call(this, item);
                                                                        input_focus = true;
                                                                        input.focus();
                                                                        return false;
                                                                });
                                                        org_li.before(item.html(data[opts.selectedItemProp]).prepend(close));
                                                        opts.selectionAdded.call(this, org_li.prev());	
                                                }

                                                function moveSelection(direction){
                                                        if($(":visible",results_holder).length > 0){
                                                                var lis = $("li", results_holder);
                                                                if(direction == "down"){
                                                                        var start = lis.eq(0);
                                                                } else {
                                                                        var start = lis.filter(":last");
                                                                }					
                                                                var active = $("li.active:first", results_holder);
                                                                if(active.length > 0){
                                                                        if(direction == "down"){
                                                                        start = active.next();
                                                                        } else {
                                                                                start = active.prev();
                                                                        }	
                                                                }
                                                                lis.removeClass("active");
                                                                start.addClass("active");
                                                        }
                                                }

                                        });
                                }
                        };
})(jQuery);

var delay = (function(){
				  var timer = 0;
				  return function(callback, ms){
				    clearTimeout (timer);
				    timer = setTimeout(callback, ms);
				  };
				})();
				
(function ( $ ) {
 //tags
    $.fn.userSearch = function( options ) {
 
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            source: null,
            seperator: ",",
            fieldName: "users",
            val: ''
        }, options );
 
 		var output, buddies, username, userPicture;
 		output = '<div class="userSearch">';
 		output += '<ul class="inputTagBar" style="display:none;">';
 		output += '</ul>';
 		output += '<input type="text" placeholder="search users..." class="userTagSearch">';
 		output += '<ul class="inputTagSuggestions">';
 		output += '</ul>';
 		output += '<input type="hidden" value="'+settings.val+'" name="'+settings.fieldName+'">';
 		output += '<div>';
		
		//add output to the chosen element
 		this.html(output);
 		//search suggestions
 		$('.userTagSearch').keyup(function(key){
 			var shownUsers = [];
 			$('.inputTagBar li').each(function(){
 				shownUsers[shownUsers.length+1] = $(this).data('user');
 			});
 			if(shownUsers.length === 0)
 				$('.inputTagBar').hide();
 			else
 				$('.inputTagBar').show();
 			
 			//hide if no input
 			if($('.userTagSearch').val().length === 0)
 				$('.inputTagSuggestions').hide();
 				
 			output = '';
 			
 			//backspace =-> delete last tag
 			if(key.keyCode === 8){
 				$('.inputTagSuggestions').hide();
 			}else{
 				$('.inputTagSuggestions').show();
				$('.inputTagSuggestions').html('');
				
				
				//load suggestions from API
 				var suggestions = searchUserByString($(this).val(), '0,30');
				$.each(suggestions, function( index, value ) {
					  			if(value !== undefined && !in_array(index, shownUsers)){
					  			
					  			username = String(value);
					  			username = username.slice(0,-1);
					  			userPicture = User.showPicture(index);
					  			
					  			
					  			output += '<li onclick="addUserToInputTagBar('+index+')">';
					  			output += userPicture;
					  			output += ' ';
					  			output += username;
					  			output += '</li>';
					  			}
					  			
				});
				if(output.length === 0){
					output = 'no results...';
				}
				$('.inputTagSuggestions').html(output);
				
 				
 			}
 		});
 
        // Greenify the collection based on the settings variable.
        return this;
 
    };
 
}( jQuery ));

function removeUserFromInputTagBar(userid){
	//remove value from input
	var oldValue = $('.userSearch input[type=hidden]').val();
	$('.userSearch input[type=hidden]').val(oldValue.replace(userid+',',''));
	if($('.userSearch input[type=hidden]').val().length === 0){
		$('.inputTagBar').hide();
	}
	//remove tag
	$('.inputTagBar .userTag_'+userid).remove();
}

function addUserToInputTagBar(userid){
	//dumb way to find out which users are already loaded
	var shownUsers = [];
 	$('.inputTagBar li').each(function(){
 		shownUsers[shownUsers.length+1] = $(this).data('user');
 	});
	if(!in_array(userid, shownUsers)){
		//add value to input
		$('.userSearch input[type=hidden]').val($('.userSearch input[type=hidden]').val()+userid+',');
		$('.inputTagBar').show();
		username = useridToUsername(userid);
		//append tag to tagBar
		$('.inputTagBar').append('<li class="userTag_'+userid+'" data-user="'+userid+'">'+username+'<a onclick="removeUserFromInputTagBar(\''+userid+'\');">x</a></li>');
	}
			 
}


if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}