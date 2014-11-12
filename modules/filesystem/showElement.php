<?php
session_start();
include_once("../../inc/config.php");
include_once("../../inc/functions.php");

$element = save($_GET[element]);
$elementSQL = mysql_query("SELECT * FROM elements WHERE id='$element'");
$elementData = mysql_fetch_array($elementSQL);
 $elementAuthorSql = mysql_query("SELECT userid, username FROM user WHERE userid='$elementData[author]'");
 $elementAuthorData = mysql_fetch_array($elementAuthorSql);
$title10 = substr("$elementData[title]", 0, 10);

            $link = "./modules/reader/showfile.php?type=$elementData[type]";

            
if($elementData['type'] == "image"){
  if($elementData['title'] == "profile pictures"){
  $title = "Userpictures";
  $bar = false;
  if($elementData['author'] == getUser()){
  	$changeUserPicture = true;
  }
  
  }else{
  $title = $elementData['title'];
  $bar = true;
  }
  
  
  $documentSQL = mysql_query("SELECT id, title, owner, privacy FROM files WHERE folder='$elementData[id]'");
  $fileNumbers = mysql_num_rows($documentSQL);
    ?>
        <center>
                <h2><?=$title;?></h2>
                <h3>by <i><a href="#" onclick="showProfile('<?=$elementAuthorData[userid];?>')"><?=$elementAuthorData[username];?></a></i></h3>
        </center>
		<? if($fileNumbers > 0){ ?>
        <div id="showImages" class="dockMenuElement" style="margin-top: 10px; margin-right: 0px; margin-left: 0px; overflow: scroll; height: 120px; padding-left: 5px;">

            <table wdth="100%">
                <tr>
        <?
        
        while($documentData = mysql_fetch_array($documentSQL)){
        $documentFolderSQL = mysql_query("SELECT id, path, privacy FROM folders WHERE id='$elementData[folder]'");
        $documentFolderData = mysql_fetch_array($documentFolderSQL);
        $folderPath = urldecode($documentFolderData['path']);
        if($elementData['title'] == "profile pictures"){
        $folderPath = "/userPictures";
        $folderPath = "upload$folderPath/thumb/300";
        }else{
        $folderPath = "upload$folderPath/thumbs/";
        }
        if(authorize($documentData['privacy'], "show", $documentData['owner'])){
            $fileClass = new file($documentData['id']);
            ?>
            <td onclick="openFile('image', '<?=$documentData['id'];?>', '<?=$elementData['title'];?>');" oncontextmenu="showMenu('image<?=$documentData['id'];?>'); return false;"><img src="<?=$fileClass->getFullFilePath();?>" height="100px"></td>   
                
        <? 
        $contextMenu = new contextMenu("image", $documentData['id'], $elementData['title'] , $documentData['owner']);
        $contextMenu->showRightClick();
        }} ?>
        </tr>
        </table>
        </div>
        <? } ?>
        <div>

        </div>
	    <center style="margin-top: 20px; margin-bottom: 20px;">
	    	<?
	    	if(proofLogin() && $bar){
	    	?>
	        <a href="#" onclick="openUploadTab('<?=$_GET[element];?>');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;upload File</a>&nbsp;
	        <a href="#" onclick="popper('./doit.php?action=addLink&element=<?=$_GET[element];?>')" class="btn btn-info"><i class="icon-globe icon-white"></i>&nbsp;add Link</a>
			<? }
			
			if($changeUserPicture){ ?>
				<a href="#" onclick="standardModules.showSettings();" class="btn btn-info">&nbsp;change Userpicture&nbsp;</a>
			<? }
			?>
	    </center>
        <hr>
            <div>
                <?
                $classComments = new comments();
                $classComments->showComments(element, $elementData[id]);
                ?>
            </div>
<?    
}else{
?>
<div id="showElement">
        <h2 style="margin-left: 5%; margin-bottom:0px; margin-top:5%;">
            <?=htmlspecialchars($elementData[title]);?>&nbsp;<i class="icon-info-sign" onclick="$('.elementInfo<?=$elementData[id];?>').slideDown();"></i>
        </h2>
        <div class="elementInfo<?=$elementData[id];?> hidden">
    <table width="100%" class="fileBox" cellspacing="0">
        <tr bgcolor="#FFFFFF" height="35px">
            <td width="110px">Element-Type:</td>
            <td><?=$elementData['type'];?></td>
        </tr>
        <tr bgcolor="#e5f2ff" height="35px">
            <td width="110px">Author:</td>
            <td><?=$elementData['creator'];?></td>
        </tr>
        <tr bgcolor="#FFFFFF" height="35px">
            <td>Title:</td>
            <td><?=$elementData['name'];?></td>
        </tr>
        <tr bgcolor="#e5f2ff" height="35px">
            <td>Year:</td>
            <td><?=$elementData['year'];?></td>
        </tr>
        <tr bgcolor="#FFFFFF" height="35px">
            <td>Original Title:</td>
            <td><?=$elementData['originalTitle'];?></td>
        </tr>
        <tr bgcolor="#e5f2ff" height="35px">
            <td>Language:</td>
            <td>
            	<?php echo $elementData['language'];?>
            </td>
        </tr>
        <tr bgcolor="#FFFFFF" height="35px">
            <td>License:</td>
            <td><?=$elementData['license'];?></td>
        </tr>
    </table>
    <div style="display:none; float:left; width:40%; margin-top: 3%; background: #c9c9c9; height: 250px;">
        			
  	</div>
  	</div>

    <div style=" clear: left;margin-left: 5%;">
        <a target="_blank" href="http://www.amazon.de/gp/search?ie=UTF8&camp=1638&creative=6742&index=aps&keywords=<?=htmlentities($elementData[title]);?>%20<?=$elementData[creator];?>&linkCode=ur2&tag=universeos-21">find on amazon</a><img src="http://www.assoc-amazon.de/e/ir?t=universeos-21&l=ur2&o=3" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
    </div>
    <h3 style="margin-left:5%; margin-bottom:-10px;"><i class="icon-file"></i>&nbsp;Files</h3>
        <table cellspacing="0" class="filetable" style="width: 90%; margin-left: 5%; border: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9;">
            <tr class="grayBar" height="35">
                <td></td>
                <td>Title</td>
                <td></td>
                <td ></td>
                <td ></td>
            </tr>
            <?
            
            $classElement = new element($elementData['id']);
            $classElement->showFileList();
            ?>
                
        </table>
    <center style="margin-top: 20px; margin-bottom: 20px;">
    	<?
    	if(proofLogin()){
    	?>
    	<a class="btn btn-info" href="#" onclick="loader('loader', 'doit.php?action=createNewUFF&element=<?=$element;?>'); " target="submitter"><i class="icon-file icon-white"></i> Create Document</a>
        <a href="#" onclick="openUploadTab('<?=$_GET['element'];?>');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;Upload File</a>
        &nbsp;<a href="#" onclick="popper('./doit.php?action=addLink&element=<?=$_GET['element'];?>')" class="btn btn-info"><i class="icon-globe icon-white"></i>&nbsp;Add Link</a>
		<?}?>
    </center>
    <hr>
    <div>
        <?
        $classComments = new comments();
        $classComments->showComments('element', $elementData['id']);
        ?>
    </div>
</div>
<? } ?>
