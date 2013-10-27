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
	<div id="uploadStepOne" class="uploadStep">
		<div>
		<h3>Choose Element</h3>
			<?php
			if(empty($_GET['element']) OR $_GET['element'] == "undefined"){
				echo showMiniFileBrowser("1", '', '', true, "element");
			}else{
				echo $_GET['element'];
			}
			?>
		</div>
		<footer>
			<a href="#" class="btn pull-Left">Back</a>
			<a href="#" onclick="$('#uploadStepOne').hide(); $('#uploadStepTwo').show(); initUploadify('#uploader_file', 'doit.php?action=test','moff', 'toff')" class="btn pull-right">Next</a>
		</footer>
	</div>
	<div id="uploadStepTwo" class="hidden uploadStep">
		<div>
			<h3>Add Files</h3>
		    <center style="margin-top:15px;">
		        <input id="uploader_file" name="feedFile" type="file" multiple="true" style="margin-top: 20px;">
		    	<div id="queue"></div>
		    </center>
		</div>
		<footer>
			<a href="#" class="btn pull-Left">Back</a>
			<a href="#" onclick="$('#uploadStepTwo').hide(); $('#uploadStepThree').show();" class="btn pull-right">Next</a>
		</footer>
	</div>
	<div id="uploadStepThree" class="hidden uploadStep">
		<div>
			<h3>Choose Privacy</h3>
			<?php
			showPrivacySettings();
			?>
		</div>
		<footer>
			<a href="#" class="btn pull-Left">Back</a>
			<a href="#" onclick="$('#uploadStepOne').hide(); $('#uploadStepTwo').show(); initUploadify('#uploader_file', 'doit.php?action=test','moff', 'toff')" class="btn pull-right">Next</a>
		</footer>
	</div>
</div>

<?php
}
?>