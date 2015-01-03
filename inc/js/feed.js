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
        

var feed = new function(){
    this.init = function(){
        
        var html = this.generateHeader();
        html += "        <div id=\"feedFrame\">";
        html += "            <div class=\"addFeed\">";
        html += "            <\/div>";
        html += "            <div class=\"feedMain\">";
        html += "            <\/div>";
        html += "            ";
        html += "        <\/div>";
        
        this.applicationVar = new application('feed');
	this.applicationVar.create('Feed', 'html', html,{width: 2, height:  5, top: 0, left: 0});
	
        
        var feedClass = new Feed('global', '.feedMain');
        
        privacy.load('#addFeedPrivacy', '"h//f"', true);
        
        $('#feedInput').html(function(){
                 $(this).focus(function(){
                    $( "#feedheader" ).animate({ height: "100px" }, 500 );
                    $( "#feedFrame" ).animate({ top: "100px" }, 500 );
                    $('#feedInputBar').slideDown(500);
                    $( "#addFeedPrivacy" ).animate({ top: "100px" }, 500 );
                    privacy.init();
                });
                    $(this).focusout(function(){
                        
//                        if($('#feedInput').val().length == 0){
//                            $( "#feedheader" ).animate({ height: "61px" }, 500 );
//                            $( "#feedFrame" ).animate({ top: "61px" }, 500 );
//                            $('#feedInputBar').slideUp(500);
//                            $( "#addFeedPrivacy" ).animate({ top: "61px" }, 500 );
//                            $('#addFeedPrivacy').slideUp(500);
//
//                        }
                    });
            });
            
            $('#feedInputForm').submit(function(){
                $(this).submit();
                $('#feedInput').val('');
            });
            
            
            //everything here could probaly be deleted
            $("#showGroups").click(function () {
                $("#selectGroups").toggle("slow");
            });
            $("#hideGroups").click(function () {
                $("#selectGroups").toggle("slow");
            });
            $("#selectGroups").click(function () {
                //maybe toggle
            });
            $("#enlargeSubmitArea").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $("#beforeToggle").css(cssOb);
                    var header = {
                        'height' : '120'
                        }
                    
                    var frame = {
                        'top' : '120'
                        }   
                    $("#feedheader").css(header);
                    $("#feedFrame").css(frame);
            $('#submitToggle').show();
            $('#submitText').show();
            });
            $("#minimizeSubmitArea").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    var headera = {
                        'height' : '110px'
                        }
                    
                    var framea = {
                        'top' : '110px'
                        }   
                    $("#feedheader").css(headera);
                    $("#feedFrame").css(framea);    
                    $("#submitToggle").css(cssOb);
                    $(".submitArea").css(cssOb);
            $('#beforeToggle').show();
            });
            $("#feedAddText").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitText').show();
            });
            $("#feedAddFile").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitFile').show();
            });
            $("#feedAddVote").click(function () {
                    var cssOb = {
                        'display' : 'none'
                        }
                    $(".submitArea").css(cssOb);
            $('#submitVote').show();
            });
    };
    this.generateHeader = function(){
        var output="";
        output += "<div class=\"windowHeader\" id=\"feedheader\">";
        output += "            <img src=\"img\/write_bright.png\" style=\"height:19px; position: absolute; top: 20px;right: 220px;\">";
        output += "            <form id=\"feedInputForm\" method=\"post\" action=\" doit.php?action=createFeed\" target=\"submitter\">";
        output += "            <div style=\"margin: 15px;\">";
        output += "                        <textarea id=\"feedInput\" name=\"feedInput\" onclick=\"$(this).val('');\">What's Up?<\/textarea>";
        output += "                        <div style=\"\" id=\"feedInputBar\">";
        output += "                                <div class=\"btn-toolbar\" style=\"float: left;\">";
        output += "                                        <a class=\"privacyButton\" href=\"#\" onclick=\"$('#feedInput').focus(); $('#addFeedFile').hide('slow'); $('#addFeedPrivacy').slideToggle(500);\" title=\"privacy\"> Privacy <\/a>";
        output += "                                <\/div>";
        output += "                                <input type=\"submit\" style=\"float:right;\" value=\"submit\" class=\"btn\">";
        output += "                        <\/div>";
        output += "                    <\/div>";
        output += "                    <div id=\"addFeedPrivacy\" class=\"coolGradient\">";
        output += "                    <\/div>";
        output += "                    <\/form>";
        output += "                    <\/div>";
        
        
        return output;

    };
    
};
			  