<?php
//this file will always be loaded with 
//ajax so include  is used not include_once
session_start();
include("../../inc/functions.php");
include("../../inc/config.php");


if(isset($_POST[submit])){
	
}else{
$folder = $_GET[folder];
?>
<style>
	.tempFilelist:empty{
		display:none;
	}

	.tempFilelist{
		margin: 5px;
		border: 1px solid #c9c9c9;
		max-width:500px;
		padding-bottom: 3px;
	}
	
	.tempFilelist li{
		font-size: 20px;
		line-height: 36px;
		height: 28px;
		margin: 0px 6px 0px 7px;
	}
	
	.tempFilelist li img{
		margin-bottom: -1px;
	}
	
	.tempFilelist li i{
		margin-top: 14px;
		float: right;
	}
        
        .uploadStep#uploadStepOne{
            display:block;
        }
</style>
<form action="doit.php?action=submitUploader" method="post" target="submitter">
<div id="upload">
	<div id="uploadStepOne" class="uploadStep step">
		<div>
		<h1>Upload File333333333333</h1>
		<h1>Upload File333333333333</h1>
			<?php
			if(empty($_GET['element']) OR $_GET['element'] == "undefined"){
                                $fileSystem = new fileSystem();
				echo '<h3>Choose Element</h3>';
				echo $fileSystem->showMiniFileBrowser("1", '', '', true, "element");
			}else{
				$element = new element($_GET['element']);
				$elementData = $element->getData();
				
				echo '<h3>You will add the files to this element:<br>';
				echo '<img src="gfx/icons/filesystem/element.png">&nbsp;';
				echo $element->getName();
				echo '</h3>';
				echo '<input type="hidden" name="typeId" value="'.$_GET['element'].'" class="choosenTypeId">';
				echo '<p>';
				echo 'Please klick next to continue.';
				echo '</p>';
			}
			?>
		</div>
		<footer>
			<a href="#" onclick="$('#uploadStepOne').hide(); $('#uploadStepTwo').show(); initUploadify('#uploader_file', 'doit.php?action=manageUpload&type=uploadTemp', $('.choosenTypeId').val(), '<?=$timeStamp;?>', '<?=$salt;?>'); privacy.init();" class="btn btn-xs pull-right">Next</a>
		</footer>
	</div>
	<div id="uploadStepTwo" class="uploadStep step">
		<div>
			<h3>Choose Settings</h3>
			<p>Please choose a language:</p>
			<?php
                        $guiClass = new gui();
			$guiClass->showLanguageDropdown();
			?>
			<h3>Privacy</h3>
			<p>Please justify the privacy of the files you want to upload</p>
			<?php
                        $privacyClass = new privacy($elementData['privacy']);
			$privacyClass->showPrivacySettings();
			?>
		</div>
		<footer>
			<a href="#" onclick="$('#uploadStepTwo').hide(); $('#uploadStepOne').show();" class="btn btn-xs pull-Left">Back</a>
			<a href="#" onclick="$('#uploadStepTwo').hide(); $('#uploadStepThree').show();" class="btn btn-xs pull-right">Next</a>
		</footer>
	</div>
	<div id="uploadStepThree" class="uploadStep step">
		<div>
			<h3>Add Files</h3>
			<p>Please add the Files you want to upload.</p>
		    <div style="margin-top:15px;">
		    	
		    	<ul class="tempFilelist"></ul>
		    	
		        <input id="uploader_file" name="feedFile" type="file" multiple="true" style="margin-top: 20px;">
		    	<div id="queue"></div>
		    </div>
		</div>
		<footer>
			<a href="#" onclick="$('#uploadStepThree').hide(); $('#uploadStepTwo').show();" class="btn btn-xs pull-left">Back</a>
			<input type="submit" value="Add Files to the Filesystem" class="btn btn-xs btn-success pull-right">
		</footer>
	</div>
</div>
</form>

<?php
}
?>