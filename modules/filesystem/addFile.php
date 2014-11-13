<?
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");
if(proofLogin()){
$element = save($_GET[element]);
if($_POST[submit]) {

    if(empty($_FILES['uploadedfile']['tmp_name'])){
        $error = "please select a file";
    }
if(empty($error)){
    //set privacy
    $customShow = $_POST[privacyCustomSee];
    $customEdit = $_POST[privacyCustomEdit];
    
    $privacy = exploitPrivacy("$_POST[privacyPublic]", "$_POST[privacyHidden]", $customEdit, $customShow);
    $user = $_SESSION[userid];
    $element = save("$_POST[element]");
    $folder = save("$_POST[folder]");
    $file = $_FILES['uploadedfile'];
    $lang = $_POST['language'];
	if(empty($_POST['download'])){
		$download = false;
	}else{
		$download = true;
	}
    
    addFile($file, $element, $folder, $privacy, $user, $lang, $download);
    


}}else{

$elementsql = mysql_query("SELECT * FROM elements WHERE id='$_GET[element]'");
$elementdata = mysql_fetch_array($elementsql);
$title10 = substr("$elementdata[title]", 0, 10);
?>
<div class="jqPopUp border-radius transparency" id="addFile"> 
   <a class="jqClose" id="closeFile">X</a>
    <form action="modules/filesystem/addFile.php?element=<?=$_GET[element];?>" method="post" enctype="multipart/form-data" target="submitter"> 
	<header>
		Add File
	</header>
	<div class="jqContent">
	   <table>
	    <tr>
	     <td>
	      <table>
	       <tr height="50">
	        <td>&nbsp;<?=$message;?></td>
	       </tr>
	       <tr height="45">
	        <td>file</td>
	        <td><input type="file" name="uploadedfile" class="button"><br><i>(files with the same filename will be overwritten)</td>
	       </tr>
	       <tr height="15">
	        <td>&nbsp;</td>
	       </tr>
	       <tr height="45">
	        <td><input type="checkbox" name="download" value="1" checked></td>
	        <td>allow other users to download this file</td>
	       </tr>
	       <tr height="15">
	        <td>&nbsp;</td>
	       </tr>
	       <tr>
	        <td>element:</td>
	        <td><?=$elementdata[title];?><input type="hidden" name="element" value="<?=$_GET[element];?>"><input type="hidden" name="folder" value="<?=$elementdata[folder];?>"></td>
	       </tr>
	       <tr height="15">
	        <td>&nbsp;</td>
	       </tr>
	       <tr>
	        <td>language:</td>
	        <td>
	        	
	        	<?
	        	$guiClass = new gui();
	        	$guiClass->showLanguageDropdown();
	        	
	        	?>
	        	
	        </td>
	       </tr>
	       <tr height="15">
	        <td>&nbsp;</td>
	       </tr>
	       <tr>
	           <td colspan="2">
	               
	                            <?
	                            
                                        $privacyClass = new privacy($elementdata['privacy']);
	                                $privacyClass->showPrivacySettings();
	                            ?>
	           </td>
	       </tr>
	       
	       
	       
	       
	       
	       
	
	       
	      </table> 
	     </td>
	    </tr>
	   </table>
	</div>
	<footer>
	 	<span class="pull-left"><a class="btn" onclick="$('.jqClose').hide();">Back</a></span>
	 	<span class="pull-right"><input type="submit" value="add" name="submit" id="fileSubmit" class="btn btn-success" onsubmit="popper('api.php');"></span>
	</footer>
    </form>
</div>
<script>
    $("#fileSubmit").click(function () {
    $('#addFile').slideUp();
    });
    
    $("#closeFile").click(function () {
    $('#addFile').slideUp();
    });
</script>
<? }} ?>
