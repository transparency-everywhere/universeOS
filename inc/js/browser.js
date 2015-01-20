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
          
        
