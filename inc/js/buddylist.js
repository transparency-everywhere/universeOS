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
        

var buddylist = new function(){
    this.getBuddies = function(){
                                   var res;
			  		$.ajax({
				      url:"api.php?action=getBuddylist",
				      async: false,  
					  type: "POST",
				      success:function(data) {
				         res = $.parseJSON(data); 
				      }
				   });
				   return res;
			  	};
    this.addBuddy = function(userid){
        var res;
	$.ajax({
            url:"api/buddies/add/",
            async: false,
            data: {buddy:userid},
            type: "POST",
            success:function(data) {
	      if(data == '1'){
                  gui.alert('The request has been send.', 'Request Send');
                  $('.friendButton_'+userid).html('request sent');
                  $('.friendButton_'+userid).addClass("disabled");
		  $('#buddySuggestions').load('doit.php?action=showBuddySuggestList');
              }else{
                  gui.alert('There was an error.', 'Error');
                  
              }
            }
	});
	return res;
    };
    this.removeBuddy = function(userid){
        var res;
	$.ajax({
            url:"api/buddies/remove/",
            async: false,
            data: {buddy:userid},
            type: "POST",
            success:function(data) {
	      if(data == '1'){
                  jsAlert('The buddy was removed');
              }else{
                  gui.alert('There was an error.', 'Error');
              }
            }
	});
	return res;
    };
    
    this.init = function(){
        var output="";
        output += "<div id=\"buddyListFrame\">";
        output += '<table width="100%" cellspacing="0">';

        var buddies = this.getBuddies();
        $.each(buddies, function(index, value){
            var username = useridToUsername(value);
            output += "                <tr class=\"height60 greyHover\">";
            output += "	                 <td style=\"padding:0 10px; width: 43px;\">"+User.showPicture(value, undefined, 40)+"<\/td>";
            output += "	                 <td><a href=\"#\" onclick=\"openChatDialoge('"+username+"');\">"+username+"<\/a><br><a href=\"#\" onclick=\"openChatDialoge('<?=$username;?>');\" class=\"realname\"><?=useridToRealname($buddy);?>&nbsp;<\/a><\/td>";
            output += "	                 <td align=\"right\" style=\"padding: 0 10px;\">";
            output += "						    <a href=\"#\" onclick=\"showProfile('"+value+"'); return false\" title=\"open Profile\"><i class=\"glyphicon glyphicon-user\" style=\"font-size:12px\"><\/i><\/a>";
            output += "						    <a href=\"#\" onclick=\"openChatDialoge('"+username+"'); return false\" title=\"write Message\"><i class=\"glyphicon glyphicon-envelope\" style=\"font-size:12px\"><\/i><\/a>";
            output += "						    <a href=\"#\" onclick=\"openChatDialoge('"+username+"'); return false\" title=\"write Message\"><i class=\"glyphicon glyphicon-cog\" style=\"font-size:12px\"><\/i><\/a>";
            output += "			<\/td>";
            output += "                <\/tr>";
        });


            output += "</table>";
            output += "</div>";
	
        this.applicationVar = new application('buddylist');
	this.applicationVar.create('Buddylist', 'html', output,{width: 2, height:  5, top: 0, left: 9});
	
    };
};