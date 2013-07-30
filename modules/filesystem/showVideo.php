<?php
include("../../inc/config.php");

$elementSQL = mysql_query("SELECT * FROM elements WHERE id='$_GET[element]'");
$elementData = mysql_fetch_array($elementSQL);
$title10 = substr("$elementData[title]", 0, 10);

            if($elementData[type] == "document") {
                $info1 = "author";
                $info2 = "title";
                $info3 = "year";
                $link = "showdocument.php";
            }

?>
<div id="showElement">
    <div class="path"><?=htmlentities($elementData[title]);?></div>
    <div>
        <p>author: <?=$elementData[creator];?></p>
        <p>title: <?=$elementData[name];?></p>
        <p>year: <?=$elementData[year];?></p>
        <p>license: <?=$elementData[license];?></p>
        <p>
        <?
        $fileListSQL = mysql_query("SELECT * FROM files WHERE folder='$_GET[element]'");
        while($fileListData = mysql_fetch_array($fileListSQL)) {
            $link = "$link?id=$fileListData[id]";
            $title10 = substr("$fileListData[title]", 0, 10);
            ?>
            <a href="#" onclick="createNewTab('reader_tabView','<?=$title10;?>','','./modules/reader/<?=$link;?>',true);return false"><?=$fileListData[title];?></a><br>
        <?
            
        } ?>
            <a href="./modules/filesystem/addFile.php?element=<?=$_GET[element];?>">add File</a><br>
        </p>
    </div>
</div>
