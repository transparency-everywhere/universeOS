<?
if(empty($_SESSION[userid])) {
session_start();
}

include_once("../../inc/config.php");
include_once("../../inc/functions.php");
if(!isset($_GET[reload])){
    ?>
<div id="filesystem" class="fenster" style="display: none;">
    <header class="titel">
        <p>Filesystem&nbsp;</p>
        <p class="windowMenu">
        	<a href="javascript:hideApplication('filesystem');" style="color: #FFF;"><img src="./gfx/icons/close.png" width="16"></a>
        	<a href="#" onclick="moduleFullscreen('filesystem');" class="fullScreenIcon"><img src="./gfx/icons/fullScreen.png" width="16"></a>
        </p>
    </header>
    <div class="inhalt autoFlow">
<?
}
?>
 
        
    <?
if(!$_GET['action']) {
if($_GET['folder']) {
$folder = $_GET['folder'];
} else {
$folder = "1";
}
$pathsql = mysql_query("SELECT id, path FROM folders WHERE id='$folder'");
$pathdata = mysql_fetch_array($pathsql);
?> 
      <div id="fileBrowser_tabView">
      <div class="dhtmlgoodies_aTab">
          <div>
            <?
            include("fileBrowser.php");
             ?>
          </div>

      </div>
      <script type="text/javascript">
initTabs('fileBrowser_tabView',Array('Universe'),0,"","",Array(false,true));
      </script>
      <footer class="footer">
          
      </footer>
      </div>
      </div>
</div>
</div>
<?
} 
if(!isset($_GET['reload'])){
 } ?>