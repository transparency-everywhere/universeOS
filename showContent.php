<?php
include("inc/config.php");
$contentSQL = mysql_query("SELECT title, content FROM staticContents WHERE id='".mysql_real_escape_string($_GET[content])."'");
$contentData = mysql_fetch_array($contentSQL);
?>
<div style="position: absolute; top: 21px; bottom: 0px; width: 100%; overflow: auto;" class="gray-gradient">
       
        <div class="grayBar" style="top: 0px; left:0px; right: 0px; height: 20px; overflow: none;">
            <center><?=$contentData[title];?></center>
        </div>
       <div style="margin-left: 20%; width: 60%; font-size: 13pt;" id="registerBox">
           <?=$contentData[content];?>
       </div>
</div>