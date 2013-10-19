<?
if(isset($_GET[reload])) {
    session_start();
    include_once("../../inc/config.php");
    include_once("../../inc/functions.php");
}


	
$showFileBrowser = true;


if(!isset($_GET[initter])){
    
?>

          <div class="leftNav">
              <ul>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?special=pupularity&reload=1');return false"><img src="gfx/icons/filesystem/side_suggestions.png"> Suggestions</a></li>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?reload=1');return false"><img src="gfx/icons/filesystem/side_allFiles.png"> All Files</a></li>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?special=document&reload=1');return false"><img src="gfx/icons/filesystem/side_documents.png"> Documents</a></li>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?special=audio&reload=1');return false"><img src="gfx/icons/filesystem/side_audio.png"> Audio Files</a></li>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?special=video&reload=1');return false"><img src="gfx/icons/filesystem/side_video.png"> Video Files</a></li>
					<?
					if(proofLogin()){
					?>
                  <li><a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?special=fav&reload=1');return false"><img src="gfx/icons/filesystem/side_fav.png"> Fav</a></li>
 				  <? } ?>
                  <!-- <li><i class="icon-warning-sign"></i> deleted</li> -->
              </ul>
          </div>
<?
if(isset($_GET['special'])){
	
	//special querys, like show all music, show popular files etc.
    if($_GET['special'] == "pupularity"){
        $folderQuery = "ORDER BY votes DESC LIMIT 0, 10";
        $elementQuery = "ORDER BY votes DESC LIMIT 0, 10";
    }
    else if($_GET['special'] == "audio"){
        $folderQuery = NULL;
        $elementQuery = " WHERE type LIKE '%audio%'";
    }
    else if($_GET['special'] == "video"){
        $folderQuery = NULL;
        $elementQuery = "WHERE type LIKE '%video%'";
    }
    else if($_GET['special'] == "document"){
        $folderQuery = NULL;
        $elementQuery = "WHERE type LIKE '%document%'";
    }
	else if($_GET['special'] == "fav"){
		$showFileBrowser = false;
		$fav = true;
	}
    
}else{
if(isset($_GET['folder'])){
	$folder = $_GET['folder']; 
			//userFolder
		if($folder == "2"){
			$userData = getUserData();
			$folder = $userData['homefolder'];
		}
	
} else if(empty($_GET['folder'])  OR $_GET['folder']==0){
$folder = "1";
}}


$pathsql = mysql_query("SELECT id, folder, path, privacy, creator FROM folders WHERE id='".mysql_real_escape_string($folder)."'");
$pathdata = mysql_fetch_array($pathsql);
	if($pathdata['folder'] == 2){
		$pathdata['folder'] = "1";
	}
} ?>
<div class="border-box frameRight fileBrowser_<?=$folder;?>">
    <div class="underFrame" style="overflow: none;">
        <div class="grayBar" style="top: 0px; left:0px; right: 0px; height: 20px; overflow: none;">
            	<? if($folder !== "1") {?>
            	<a href="#" onclick="addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=<?=$pathdata[folder];?>&reload=1');return false" title="parent folder" class="btn btn-mini" style="margin-right:3px;"><i class="icon-arrow-up"></i></a>
                <? }
                if(proofLogin() && !empty($folder)){ 
            		echo"<a href=\"#\" onclick=\"$('.fileBrowserSettings$folder').slideToggle('slow'); return false\" title=\"more...\" class=\"btn btn-mini\"><i class=\"icon-cog\"></i></a>";
                 }?>
        </div>
        <div class="fileBrowser">
        	<?
        	if(!empty($folder)){
        	?>
        	<ul class="fileBrowserSettings fileBrowserSettings<?=$folder;?>">
        		<?
        		if(proofLogin()){
        			?>
        			<li><a href="#" onclick="javascript: popper('doit.php?action=addFav&type=folder&item=<?=$folder;?>&reload=1');return false">Fav</a></li>
        		<?
        		}
        		if(authorize("$pathdata[privacy]", "edit", $pathdata[creator])){ ?>
        		<li><a href="#" onclick="javascript: popper('doit.php?action=addElement&folder=<?=$folder;?>&reload=1');return false">Add Element</a></li>
        		<li><a href="#" onclick="javascript: popper('doit.php?action=addFolder&folder=<?=$folder;?>&reload=1');return false">Add Folder</a></li>
        		<li><a href="#" onclick="javascript: popper('doit.php?action=addInternLink&parentFolder=<?=$folder;?>&reload=1');return false">Add Shortcut</a></li>
				<? } ?>
        	</ul>
        	<? } 
			
			
				if($showFileBrowser){
                	showFileBrowser($folder, "$folderQuery", "$elementQuery");
				}

				if($fav){
					echo'<table width="100%">';
					echo showFav($_SESSION[userid]);
					echo'</table>';
				}
				
				
                ?>
        </div>
    </div>
    </div>