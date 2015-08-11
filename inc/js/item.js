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

var item = new function(){
    this.plusOne = function(type, itemId){
        
        api.query('api/item/score/scorePlus/',{type: type, item:itemId},function(data){
                $('span.scoreButton.'+type+'_'+itemId+' .counter').html(data);
        });
        
    };
    this.minusOne = function(type, itemId){
        api.query('api/item/score/scoreMinus/',{type: type, item:itemId},function(data){
            $('span.scoreButton.'+type+'_'+itemId+' .counter').html(data);
        });
    };
    this.showItemThumb = function(type, itemId){
        return api.query('api/item/getItemThumb/', { type : type, itemId: itemId});
    };
    
    this.getScore = function(type, itemId){
        //if type or itemId is array, handle as request for multiple items
        if(typeof type === 'object' || typeof itemId === 'object'){
            var requestType = type;
            var requests = [];
            $.each(itemId,function(index, value){
                //you can also enter a single type instead of multiple values
                if(typeof type === 'object'){
                    requestType = type[index];
                }
                requests.push({ type : requestType, itemId: value});
            });
            return api.query('api/item/getScore/', { request: requests});
        }else
            return api.query('api/item/getScore/', { request: [{type : type, itemId: itemId}]});
    };
    
    
    this.generateItemPreview = function(item_type, item_id){
        //var itemInfo = item.getInfo(item_type, item_id);
        if(item_type !== 'youtube'){
            item_type = item_type+'s';
        }
        var html = '<table class=\'itemPreview\'>';
                html += '<tr>';
                    html += '<td>';
                        html += handlers[item_type].getThumbnail(item_id);
                    html += '</td>';
                    html += '<td>';
                        html += '<table>';
                            html += '<tr><td><h3>'+handlers[item_type].getTitle(item_id)+'</h3></td></tr>';
                            html += '<tr><td>'+gui.shorten(handlers[item_type].getDescription(item_id),120)+'</td></tr>';
                        html += '</table>';
                    html += '</td>';
                html += '</tr>';
            html += '</table>';
            
            return html;
    };
    
    this.getInfo = function(item_type, item_id){
        var image;
        var title;
        var subtitle;
        
        switch(item_type){
            case 'youtube':
                var id = youtubeURLToVideoId(item_id);
                image = 'http://img.youtube.com/vi/'+id+'/1.jpg';
                title = getYoutubeTitle(id);
                subtitle = item_id;
                
                break;
        }
        
        return {'image':image, 'title':title, 'subtitle': subtitle};
    };
    
    this.showScoreButton = function(type, itemId){
        var score = this.getScore(type, itemId);
        if(typeof itemId === 'object'){
            var result = [];
            $.each(score, function(index, value){
                var output = '<span class="scoreButton '+type+'_'+itemId+'">';
                    if(proofLogin())
                        output += '<a class="btn btn-xs" href="#" onclick="item.minusOne(\''+type+'\', \''+itemId+'\');">' + filesystem.generateIcon('dislike', 'gray') + '</a>';
                    output += '<a class="btn btn-xs counter" href="#">'+score[index]+'</a>';
                    if(proofLogin())
                        output += '<a class="btn btn-xs" href="#" onclick="item.plusOne(\''+type+'\', \''+itemId+'\');">' + filesystem.generateIcon('like', 'gray') + '</a>';
                output += '</span>';
                result.push(output);
            });
            return result;
        }else{
            


            var output = '<span class="scoreButton '+type+'_'+itemId+'">';
                    if(proofLogin())
                        output += '<a class="btn btn-xs" href="#" onclick="item.minusOne(\''+type+'\', \''+itemId+'\');">' + filesystem.generateIcon('dislike', 'gray') + '</a>';
                    output += '<a class="btn btn-xs counter" href="#">'+score+'</a>';
                    if(proofLogin())
                        output += '<a class="btn btn-xs" href="#" onclick="item.plusOne(\''+type+'\', \''+itemId+'\');">' + filesystem.generateIcon('like', 'gray') + '</a>';
                output += '</span>';
            return output;
        
        }
    };
    
    this.getOptions = function(type, itemId){
        //if type or itemId is array, handle as request for multiple items
        if(typeof type === 'object' || typeof itemId === 'object'){
            var requestType = type;
            var requests = [];
            $.each(itemId,function(index, value){
                //you can also enter a single type instead of multiple values
                if(typeof type === 'object'){
                    requestType = type[index];
                }
                requests.push({ type : requestType, itemId: value});
            });
            return api.query('api/item/getOptions/', { request: requests});
        }else
            return api.query('api/item/getOptions/', { request: [{type : type, itemId: itemId}]});
        
    };
    this.showRightClickMenu = function(type, itemId){
        var options = this.getOptions(type, itemId);
	var list = '';
        var href = '';
        var onclick = '';
        var target = '';
			$.each(options,function(index,option){
                            
                            
				if(option['title']){
					
						onclick = '';
                                }
				if(option['href']){
					href = 'href="'+option['href']+'"';
					
				}
				if(option['onclick']){
					if(href == 'href="#"'){
						onclick = 'onclick="'+option['onclick']+'"';
					}
				}
				if(option['target']){
					if(href != 'href="#"'){
						target = 'target="'+option['target']+'"';
                                        }
					
				}
				list += "<li><a "+href+" "+onclick+" "+target+">"+option['title']+'</a></li>';  
					
				});
					


			        var html = "<a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"itemSettingsButton\">" + filesystem.generateIcon('settings', 'grey') + "</a>\n\
                                <div class=\"itemSettingsWindow\">\n\
                                            <ul>";
                                    html += list;
                                    
                                    
                                    html += "</ul>\n\
                                        </div>";
                        
				return html;
    };
    
    
    this.buildList = function(options){
	var list = '';
        var href = '';
        var onclick = '';
        var target = '';
	$.each(options,function(index,option){
		if(option['title']){
			
				onclick = '';
                }
		if(option['href']){
			href = 'href="'+option['href']+'"';
			
		}
		if(option['onclick']){
			if(href == 'href="#"'){
				onclick = 'onclick="'+option['onclick']+'"';
			}
		}
		if(option['target']){
			if(href != 'href="#"'){
				target = 'target="'+option['target']+'"';
                        }
			
		}
                if(typeof option['title'] !== 'undefined')
                    list += "<li><a "+href+" "+onclick+" "+target+">"+option['title']+'</a></li>';
	});
        return list;
    };
    this.showItemSettings = function(type, itemId){
        
        var options = this.getOptions(type, itemId);
        if(options === null)
            return '';
        
        
        if(typeof itemId === 'object'){
            var result = [];
            $.each(options, function(index, value){
                
                var list = item.buildList(value);
                var href = '';
                var onclick = '';
                var target = '';	
                var html = "<a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"itemSettingsButton\">" + filesystem.generateIcon('settings', 'grey') + "</a>\n\
                  <div class=\"itemSettingsWindow\">\n\
                              <ul>";
                      html += list;


                      html += "</ul>\n\
                          </div>";
                result.push(html);
            });
            return result;
        }else{
            var list = this.buildList(options[0]);
            var href = '';
            var onclick = '';
            var target = '';	
            if(!empty(list)){
                var html = "<a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"itemSettingsButton\">" + filesystem.generateIcon('settings', 'grey') + "</a>\n\
                  <div class=\"itemSettingsWindow\">\n\
                              <ul>";
                      html += list;


                      html += "</ul>\n\
                          </div>";

                  return html;
            }else{
                return '';
            }
        
        }
        
        
        
	var list = this.buildList(options);
        var href = '';
        var onclick = '';
        var target = '';	
                              var html = "<a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"itemSettingsButton\">" + filesystem.generateIcon('settings', 'grey') + "</a>\n\
                                <div class=\"itemSettingsWindow\">\n\
                                            <ul>";
                                    html += list;
                                    
                                    
                                    html += "</ul>\n\
                                        </div>";
                        
				return html;
    };
    this.initSettingsToggle = function(){
       
        $(document).on("click", ".itemSettingsButton", function(e){
                e.preventDefault();
                var $settingsWindow = $(this).next('.itemSettingsWindow');
                
                //hide all itemSettingsWindows
                $('.itemSettingsWindow').hide();
                
                $settingsWindow.show();
                
                
                
                //proof if settingswindow is in viewport
                var inViewPort = {'right':e.clientX+170<$(window).width(), 'bottom':e.clientY+$settingsWindow.height()<$(window).height()};
                
                var top, left;
                top = e.clientY;
                left = e.clientX;
                
                
                if(!inViewPort.right||!inViewPort.bottom){
                    if(!inViewPort.right){
                        left = e.clientX-170;
                    }
//                    if(!inViewPort.bottom){
//                        top = e.clientY-$settingsWindow.height();
//                    }
                }
                
                
                $settingsWindow.css({'top':top, 'left':left});
        });
    };
    this.generateInfo = function(image, title, action){
        var output = '<div class="itemPreview">';
                    output += '<div class="previewImage"><img src=\''+image+'\'/></div>';
                    output += '<div class="caption">'+title+'</div>';
                    output += '<div class="actionArea">'+action+'</div>';
        output += '</div>';
        
        return output;
    };
    
    this.initRightClick = function(){
        $('.rightClick').bind('contextmenu', function(e){
            e.preventDefault();
            var $element = $(this);
            var settingsHTML = item.showItemSettings($element.attr('data-type'),$element.attr('data-itemId'));
            
            //var $settingsWindow = ?;
                $('#loader .itemSettingsWindow').remove()
                $('#loader').append(settingsHTML);
                
                //hide all itemSettingsWindows
                $('.itemSettingsWindow').hide();
                var $settingsWindow = $('#loader .itemSettingsWindow');
                
                $settingsWindow.show();
                
                
                
                //proof if settingswindow is in viewport
                var inViewPort = {'right':e.clientX+170<$(window).width(), 'bottom':e.clientY+$settingsWindow.height()<$(window).height()};
                
                var top, left;
                top = e.clientY;
                left = e.clientX;
                
                
                if(!inViewPort.right||!inViewPort.bottom){
                    if(!inViewPort.right){
                        left = e.clientX-170;
                    }
//                    if(!inViewPort.bottom){
//                        top = e.clientY-$settingsWindow.height();
//                    }
                }
                
                
                $settingsWindow.css({'top':top, 'left':left});
            
        });
    };
};
        
