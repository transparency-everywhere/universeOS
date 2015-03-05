
//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You
//        may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com
        

var reader = new function(){
    this.applicationVar;
    this.tabs;
    
    this.uffChecksums = []; //var to store checksums for reload
    
    this.init = function(){
        this.applicationVar = new application('reader');
        this.applicationVar.create('Reader', 'url', 'modules/reader/index.php',{width: 5, height:  4, top: 0, left: 4, hidden: true});
        
        
	this.tabs = new tabs('#readerFrame');
        this.tabs.init();
	this.tabs.addTab('Home', '',gui.loadPage('modules/reader/fav.php'));
    };
    this.show = function(){
        reader.applicationVar.show();
    };
    this.openFile = function(file_id){
        var fileData = filesystem.getFileData(file_id);
        
        var header = "<header class=\"white-gradient\">";
        header += filesystem.generateIcon(fileData['type'], 'grey');
        header += "<span class=\"title\">" + fileData['title'] + "</span>";
        header += item.showScoreButton('file', file_id);
        header += filesystem.generateIcon('download', 'grey');
        header += item.showItemSettings('file', file_id);
        header += filesystem.generateIcon('plus', 'grey');
        header += filesystem.generateIcon('minus', 'grey');
        header += "</header>";
        
        var output = '';
        
        switch(fileData['type']){
            //cases: text, uff, image, pdf?, audio?, video?
            case 'image', 'image/jpeg', 'image/png', 'image/tiff', 'image/gif':
                var title = fileData['title'];
                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";
                        //this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js
                        //dirty solution!!!
                        output += "<div class=\"imageReader\">";
                        output += "";
                        output += "";
                        output += "";
                        output += "aaaaaaaaaasd";
                        output += "";
                        output += "";
                        output += "";
                        output += "";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
            
            case 'UFF':
                if(privacy.authorize(fileData['privacy'], "edit", fileData['owner'])){
                    var readOnly = "false";
                }else{
                    var readOnly = "true";
                }

                var title = fileData['title'];

                output += '<div class="openFile">';
                    output += header;
                    output += "<div class=\"fileWindow\" id=\"fileWindowId\">";


                        //this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js
                        //dirty solution!!!
                        output += "<div class=\"uffViewerNav\">";
                                output += "<div style=\"margin: 10px;\">";
                                        output += "<ul>";
                                            output += '<li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon icon-user"></i>&nbsp;<strong>Active Users</strong></li>';
                                        output += "</ul>";
                                output += "</div>";
                        output += "</div>";
                        //document frame
                        output += "<div class=\"uffViewerMain\">";
                                output += "<textarea class=\"uffViewer_"+file_id+" WYSIWYGeditor\" id=\"editor1\">";
                                output += "</textarea>";
                        output += "</div>";
                    output += '</div>';
                output += '</div>';
            break;
        }
        
        reader.tabs.addTab(title, 'html', output, function(){
            //onclose
            delete reader.uffChecksums[file_id];
        });
        
        
        switch(fileData['type']){
            case'UFF':
                
                
                
                api.query('api/files/read/', { file_id : file_id}, function(uffContent){
                    
                    initUffReader(file_id, uffContent, "false");
                    
                    
                    //store hash
                    reader.uffChecksums[file_id] = hash.MD5(uffContent);
                });
                
                break;
        }
        
        
        reader.applicationVar.show();
        
        
        return output;
        
    };
    this.openLink = function(){
        
    };
    
};