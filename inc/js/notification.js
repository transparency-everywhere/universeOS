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
      $('#notifications li').on('click',function(){
         $(this).hide(); 
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
