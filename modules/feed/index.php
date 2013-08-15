<?php
if(!isset($_SESSION[userid])){
    session_start();
    include("../../inc/config.php");
    include("../../inc/functions.php");
}
?>  
<div class="fenster" id="feed" style="display: none;">
    <header class="titel">
        <p>Feed&nbsp;</p><p class="windowMenu"><a href="javascript:hideApplication('feed');"><img src="./gfx/icons/close.png" width="16"></a></p>
    </header>
    <div class="inhalt autoFlow">
        <div class="windowHeader" id="feedheader">
            <form id="feedInputForm" method="post" action=" doit.php?action=createFeed" target="submitter">
            <div style="margin: 15px;">
                        <textarea style="" id="feedInput" name="feedInput"></textarea>
                        <div style="display: none; width: 86%; margin-left: 7%; margin-bottom: 0px;" id="feedInputBar">
                                <div class="btn-toolbar" style="float: left;">
                                    <div class="btn-group">
                                        <a class="btn btn-mini" href="#" onclick="$('#feedInput').focus(); $('#addFeedFile').hide('slow'); $('#addFeedPrivacy').slideToggle(500);" title="privacy"><i class="icon-eye-open"></i></a>
                                        <a class="btn btn-mini" href="#" title="Add file to your library" onclick="$('#feedInput').focus();$('#addFeedPrivacy').hide('slow'); $('#addFeedFile').slideToggle(500);"><i class="icon-file"></i></a>
                                    </div>
                                </div>
                                <input type="submit" style="float:right; margin-top: 10px; margin-right:-13px;" value="submit" class="btn btn-success btn-mini">
                        </div>
                    </div>
                    <div id="addFeedPrivacy" class="coolGradient">
                        <?=showPrivacySettings("h//f");?>
                    </div>
                    </form>
                    <div id="addFeedFile" class="coolGradient">
                        <form>
                        	<center style="margin-top:15px;">
                        		Add file to <a href="#" onclick="openElement('<?=$global_userData[myFiles];?>', 'myFiles'); return false;"><img src="http://universeos.org/gfx/icons/filesystem/element.png" height="12" style="margin-top: -1px;"> myFiles</a>
                                <input id="file_upload" name="feedFile" type="file" multiple="true" style="margin-top: 20px;">
                        		<div id="queue"></div>
                        	</center>
                        </form>
                        <script type="text/javascript">
                                <?php $timestamp = time();?>
                                $(function() {
                                        $('#file_upload').uploadify({
                                                'formData'     : {
                                                        'timestamp' : '<?php echo $timestamp;?>',
                                                        'token'     : '<?php echo md5('ichWeissEsNicht' . $timestamp*2);?>'
                                                },
                                                'swf'      : 'inc/plugins/uploadify/uploadify.swf',
                                                'uploader' : 'doit.php?action=feedUpload',
                                                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                                                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                                                }
                                        });
                                });
                        </script>
                    </div>
                    </div>

        <div id="feedFrame">
            <div class="addFeed">
            </div>
            <div class="feedMain">
                <?
                showFeedNew("friends", "$_SESSION[userid]");
                echo "<div onclick=\"feedLoadMore('.feedMain' ,'friends', 'NULL', '1'); feedLoadMore('friends','1'); $(this).hide();\">...load more</div>";
                ?>
            </div>
            
        </div>
        <script>
            $('#feedInput').html(function(){
                 $(this).focus(function(){
                    $( "#feedheader" ).animate({ height: "80px" }, 500 );
                    $( "#feedFrame" ).animate({ top: "81px" }, 500 );
                    $('#feedInputBar').slideDown(500);
                    $( "#addFeedPrivacy" ).animate({ top: "81px" }, 500 );
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
                        'height' : '60'
                        }
                    
                    var framea = {
                        'top' : '60'
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
            </script>
    </div>
</div>