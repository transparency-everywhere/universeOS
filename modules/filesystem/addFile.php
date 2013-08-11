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
    $lang = $_POST[lang];
    
    addFile($file, $element, $folder, $privacy, $user, $lang);
    


}}

$elementsql = mysql_query("SELECT * FROM elements WHERE id='$_GET[element]'");
$elementdata = mysql_fetch_array($elementsql);
$title10 = substr("$elementdata[title]", 0, 10);
?>
<div class="jqPopUp border-radius transparency" id="addFile"> 
   <a class="jqClose" id="closeFile">X</a>
    <form action="modules/filesystem/addFile.php?element=<?=$_GET[element];?>" method="post" enctype="multipart/form-data" target="submitter"> 
   <table>
    <tr>
     <td align="center">Add File</td>
    </tr>
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
        	
        	showLanguageDropdown();
        	
        	?>
        	
        </td>
       </tr>
       <tr height="15">
        <td>&nbsp;</td>
       </tr>
       <tr>
           <td colspan="2">
               
                            <?
                                    showPrivacySettings($elementdata[privacy]);
                            ?>
           </td>
       </tr>
       <tr>
           <td>&nbsp;</td>
       </tr>
       <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="add" name="submit" id="fileSubmit" class="btn btn-success" onsubmit="popper('google.com');"></form></td>

       </tr>
       <tr>
           <td>&nbsp;</td>
       </tr>
       <tr>
           <td>&nbsp;</td>
           <td><a class="btn btn-info" href="#" onclick="loader('loader', 'doit.php?action=createNewUFF&element=<?=$element;?>'); " target="submitter"><i class="icon-file"></i> Create New UFF</a></td>
       </tr>
      </table> 
     </td>
    </tr>
   </table>
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
<? } ?>
