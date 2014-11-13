<div id="invisiblereader">
<div class="fenster" id="reader" style="display: none;">
    <header class="titel">
        <p>Reader&nbsp;</p>
        <p class="windowMenu">
        	<a href="javascript:hideApplication('reader');"><img src="./gfx/icons/close.png" width="16"></a>
        	<a href="#" onclick="moduleFullscreen('reader');" class="fullScreenIcon"><img src="./gfx/icons/fullScreen.png" width="16"></a>
        </p>
    </header>
    <div class="inhalt" id="readerMain">
        <div id="reader_tabView">
  
        <div class="dhtmlgoodies_aTab" style="">
            <?
            if($login){
                include("fav.php");
            }else{
                //load offline Startpage
                include("offline.php");
            }
            ?>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
initTabs('reader_tabView',Array('Home'),0,"","",Array(false));
</script>
</div>