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
