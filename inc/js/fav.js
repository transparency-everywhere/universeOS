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
        

var fav = new function(){
    this.select = function(user){
	return api.query("api/fav/select/", {user : user});
    };
    this.getFavArray = function(user){
        var favs = this.select(user);
        var favArray = [];
        $.each(favs, function(key, value){
            var timestamp = '';
            if(typeof value['data']['name'] !== 'undefined' && value['data']['name'] !== ""){
                value['data']['title'] = value['data']['name'];
            }
            if(value['data']['timestamp'] > 10000000){
                timestamp = value['data']['timestamp'];
            }
            favArray.push({type:value['type'], itemId: value['data']['id'], title:value['data']['title'], timestamp: timestamp});
        });
        return favArray;
    };
    this.getFavHistory = function(){
        var favArray = this.getFavArray(User.userid); //get favorites
        favArray.reverse(); //latest favorites first
        favArray.slice(0,9); //just the 10 latest favorites
        return favArray;
    };
    this.show = function(user){
        var html = '<table width="100%">';		  			
        var favs = this.select(user);
        var i = 0;
        $.each(favs, function(key, value){
            if(typeof value['data']['name'] !== 'undefined' && value['data']['name'] !== ""){
                value['data']['title'] = value['data']['name'];
            }
            if(value['type'] === 'folder'){
                var link = "openFolder(" + value['data']['id'] + "); return false;";
            }
            if(value['type'] === 'element'){
                var link = "openElement(" + value['data']['id'] + "); return false;";
            }
            if(value['type'] === 'link'){
                var link = "reader.openLink(" + value['data']['id'] + "); return false;";
            }
            if(value['type'] === 'file'){
                var link = "reader.openFile(" + value['data']['id'] + "); return false;";
            }
            html += "<tr class=\"strippedRow\">";
            html += "<td width=\"35\">&nbsp;" + filesystem.generateIcon(value['type']) + "</td>";
            html += "<td><a href=\"#\" onclick=\"" + link + "\">" + value['data']['title'] + "</a></td>";
            if(user === User.userid){
                html += "<td align=\"right\"><a class=\"btn btn-mini\" onclick=\"fav.remove('" + value['type'] + "', '" + value['data']['id'] + "')\"><i class=\"icon icon-minus\"></i></a></td>";
            }
            html += "</tr>";  
            i++;
        });
        if(i === 0){
            html += "<tr style=\"display:table-row; background: none; padding-top: 0px;\">";
            html += "<td colspan=\"2\" style=\"padding: 5px; padding-top: 0px;\">";
            html += "You don't have any favorites so far. Add folders, elements, files, playlists or other items to your favorites and they will appear here.";
            html += "</td>";
            html += "</tr>";
        }
        html += '</table>';
    
        return html;
    };
    this.add = function(type,typeid){

        api.query('api/fav/add/', {type : type, typeid:typeid},function(data){
            if(data == '1'){
                gui.alert('The favorite has been added.', 'Add Favorite');
                updateDashbox('fav');
            }else{
                 gui.alert(data, 'Add Favorite');
            }
        });
    };
    
    this.remove = function(type, typeId){
        api.query('api/fav/remove/', { type: type, typeId: typeId }, function(){
            gui.alert('Your favorite has been removed.', 'Remove Favorite');
            updateDashbox('fav');
        });
    }
};
