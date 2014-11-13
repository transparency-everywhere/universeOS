<?
if(empty($_SESSION['userid'])) {
session_start();
}

include_once(universeBasePath.'/'."inc/config.php");
include_once(universeBasePath.'/'."inc/functions.php");
if(!isset($_GET['reload'])){
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
if(!isset($_GET['action'])) {
    if(!isset($_GET['folder'])) {
        $folder = "1";
    }
    ?> 
      <div id="fileBrowser_tabView">
      <div class="dhtmlgoodies_aTab">
          <div>
            <?php
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
?>