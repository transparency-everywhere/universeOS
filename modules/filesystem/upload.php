<?php
//this file will always be loaded with 
//ajax so include  is used not include_once
include("../../inc/functions.php");
include("../../inc/config.php");


if(isset($_POST[submit])){
	
}else{
$folder = $_GET[folder];
?>
<div id="upload">
	<h2>Upload File</h2>
	<div id="uploadStepOne">
	<h3>Choose Element</h3>
	<div>
		<?php
		if(empty($_GET['element']) OR $_GET['element'] == "undefined"){
			echo showMiniFileBrowser("1", '', '', true, "element");
		}
		?>
	</div>
	<footer>
	back
	next
	</footer>
	</div>
	<div id="uploadStepTwo" class="hidden">
	<h3>Add Files</h3>
	
		
                        	<center style="margin-top:15px;">
                                <input id="file_upload_<?php echo $time;?>" name="feedFile" type="file" multiple="true" style="margin-top: 20px;">
                        		<div id="queue"></div>
                        	</center>
	<footer>
	back next
	</footer>
	</div>
	<div id="uploadStepThree" class="hidden">
	<h3>Choose Privacy</h3>
	<footer>
	back finish
	</footer>
	</div>
	<div>
		<script type="text/javascript">
                                <?php $timestamp = time();?>
                                $(function() {
                                        $('#file_upload_<?php echo $time;?>').uploadify({
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