<?php
if(isset($_POST[submit])){
	
}else{
$folder = $_GET[folder];
?>
<div id="upload">
	<h3>Upload</h3>
	<h3>Parent:</h3>
	<div>
		<?
		showMiniFileBrowser($folder=NULL, $element=NULL, $level, $showGrid=true)
		?>
	</div>
	<h3>Upload File</h3>
	<div>
		
                        	<center style="margin-top:15px;">
                        		Add file to <a href="#" onclick="openElement('<?=$global_userData[myFiles];?>', 'myFiles'); return false;"><img src="http://universeos.org/gfx/icons/filesystem/element.png" height="12" style="margin-top: -1px;"> myFiles</a>
                                <input id="file_upload" name="feedFile" type="file" multiple="true" style="margin-top: 20px;">
                        		<div id="queue"></div>
                        	</center>
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

<?php
}
?>