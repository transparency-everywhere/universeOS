<?
    session_start();
    include_once("../../inc/config.php");
    include_once("../../inc/functions.php");


	
$showFileBrowser = true;


if(!isset($_GET['initter'])){
    
?>

          <div class="leftNav">
              <ul>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=pupularity&reload=1'));return false"><img src="gfx/icons/filesystem/side_suggestions.png"> Suggestions</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?reload=1'));return false"><img src="gfx/icons/filesystem/side_allFiles.png"> All Files</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=document&reload=1'));return false"><img src="gfx/icons/filesystem/side_documents.png"> Documents</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=audio&reload=1'));return false"><img src="gfx/icons/filesystem/side_audio.png"> Audio Files</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=video&reload=1'));return false"><img src="gfx/icons/filesystem/side_video.png"> Video Files</a></li>
					<?
					if(proofLogin()){
					?>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=fav&reload=1'));return false"><img src="gfx/icons/filesystem/side_fav.png"> Fav</a></li>
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
			if(proofLogin()){
				$userData = getUserData();
				$folder = $userData['homefolder'];
				$showFileBrowser = true;
			}else{
				$showFileBrowser = false;
			}
			
		}
	
} else if(empty($_GET['folder'])  OR $_GET['folder']==0){
$folder = "1";
}}

$folderClass = new folder($folder);
$pathdata = $folderClass->getFolderData();

	if($pathdata['folder'] == 2){
		$pathdata['folder'] = "1";
	}
} ?>
<div class="frameRight fileBrowser_<?=$folder;?>">
    <div class="path">
            <?php
            echo 'universe/'.$folderClass->getPath(false);
            if(proofLogin() && !empty($folder)){ 
        		echo"<a href=\"#\" id=\"settingsButton\" onclick=\"$('.fileBrowserSettings$folder').slideToggle('slow'); return false\" title=\"more...\" class=\"btn btn-mini\"><i class=\"glyphicon glyphicon-cog\"></i></a>";
             }?>
    </div>
    <div class="underFrame" style="overflow: none;">
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
        		if(authorize($pathdata['privacy'], "edit", $pathdata['creator'])){ ?>
        		<li><a href="#" onclick="javascript: popper('doit.php?action=addElement&folder=<?=$folder;?>&reload=1');return false">Add Element</a></li>
                        <li><a href="#" onclick="javascript: filesystem.showCreateFolderForm('<?=$folder;?>');return false">Add Folder</a></li>
        		<li><a href="#" onclick="javascript: popper('doit.php?action=addInternLink&parentFolder=<?=$folder;?>&reload=1');return false">Add Shortcut</a></li>
				<? } ?>
        	</ul>
        	<? } 
			
			
				if($showFileBrowser){
                                    $fileSystem = new fileSystem();
                                    $fileSystem->showFileBrowser($folder, $folderQuery, $elementQuery);
				}

				if($fav){
                                        $favClass = new fav();
					echo'<table width="100%">';
					echo $favClass->show(getUser());
					echo'</table>';
				}
				
				
                ?>
        </div>
    </div>
    </div>