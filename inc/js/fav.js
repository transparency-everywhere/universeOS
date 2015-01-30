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
        var result="";
	$.ajax({
            url:"api/fav/select/",
            async: false,  
            type: "POST",
            data: {user : user},
            success:function(data) {
               result = JSON.parse(data);
            }
	});
	return result;
    };
    this.show = function(user){
        var html = '<table width="100%">';		  			
        var favs = this.select(user);
        var i = 0;
        $.each(favs, function(key, value){
            if(!empty(value['data']['name'])){
                value['data']['title'] = value['data']['name'];
            }
            if(value['type'] === 'folder'){
                var link = "openFolder(" + value['data']['id'] + "); return false;";
                
            }
            if(value['type'] === 'element'){
                var link = "openElement(" + value['data']['id'] + "); return false;";
                
            }
            html += "<tr class=\"strippedRow\" onmouseup=\"showMenu('folder" + value['data']['favId'] + "')\">";
            html += "<td onmouseup=\"showMenu(" + value['data']['id'] + ")\" width=\"35\">&nbsp;<img src=\"./" + value['data']['iconsrc'] + "\" height=\"20\"></td>";
            html += "<td onmouseup=\"showMenu(" + value['data']['id'] + ")\"><a href=\"#\" onclick=\"" + link + "\">" + value['data']['title'] + "</a></td>";
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
                                
                                    


        }
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
