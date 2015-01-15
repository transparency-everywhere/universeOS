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
        
        
        $.ajax({
              url: 'api/item/score/scorePlus/',
              type: "POST",
              data: {type: type, item:itemId},
              async: false,
              success: function(data){
                  
                $('span.scoreButton.'+type+'_'+itemId+' .counter').html(data);
              }
        });
        
    };
    this.minusOne = function(type, itemId){
        $.ajax({
              url: 'api/item/score/scoreMinus/',
              type: "POST",
              data: {type: type, item:itemId},
              async: false,
              success: function(data) {
                  
                $('span.scoreButton.'+type+'_'+itemId+' .counter').html(data);
              }
        });
    };
    this.showItemThumb = function(type, itemId){
        return api.query('api/item/getItemThumb/', { type : type, itemId: itemId});
    };
    
    this.getScore = function(type, itemId){
        return api.query('api/item/getScore/', { type : type, itemId: itemId});
    };
    
    this.showScoreButton = function(type, itemId){
        
        var score = this.getScore(type, itemId);
        
        
        var output = '<span class="scoreButton '+type+'_'+itemId+'">';
                output += '<a class="btn btn-xs" href="#" onclick="item.minusOne(\''+type+'\', \''+itemId+'\');"><i class="icon icon-dislike"></i></a>';
                output += '<a class="btn btn-xs btn-success counter" href="#">'+score+'</a>';
                output += '<a class="btn btn-xs" href="#" onclick="item.plusOne(\''+type+'\', \''+itemId+'\');"><i class="icon icon-like"></i></a>';
            output += '</span>';
        return output;
    };
    
    this.getOptions = function(type, itemId){
        return api.query('api/item/getOptions/', { type : type, itemId: itemId});
    };
    this.showItemSettings = function(type, itemId){
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
                                
				list += "<li><a "+href+" "+onclick+" "+target+">"+option['title']+'a</a></li>';  
					
				});
					
			        var html = "<a href=\"#\" onclick=\"$(this).next('.itemSettingsWindow').slideToggle(); $('.itemSettingsWindow').this(this).hide();\" class=\"btn btn-xs itemSettingsButton\"><i class=\"icon icon-gear\"></i></a>\n\
                                        <div class=\"itemSettingsWindow\">\n\
                                            <ul>";
                                    html += list;
                                    
                                    
                                    html += "</ul>\n\
                                        </div>";
                        
				return html;
    };
    
};
        
