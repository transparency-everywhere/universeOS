<?
include("../../inc/config.php");
include("../../inc/functions.php");
$qEncoded = urlencode($_POST['search']);
if(strlen("$_POST[search]") > 2){
    
//    syntax code
    
if($_POST[search] == "/bug"){
    ?>
<script>
$("#loader").load("doit.php?action=reportBug");
</script>
        <?
}else if($_POST[search] == "/admin"){
    ?>
<script>
$("#loader").load("admin.php");
</script>
        <?
}else if($_POST[search] == "/test"){
    
    jsAlert(array_values(buddyListArray("1"))."asdasd");
    
}else{
    
    
    
//    real search start
    
    
    ?>
<div class="suggest fancy">
    <h2 class="" style="margin-top: 5px; margin-bottom: 15px; margin-right: 15px; font-size: 15pt; float:right">Your results</h2>
<?
$q = save($_POST['search']);
$k = "10";

$suggestSQL = mysql_query("SELECT userid, username FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT $k");
$suggestCheck = mysql_num_rows($suggestSQL);?>
<div class="suggestionBox" style="width: 94%;">
    <header>User</header>
    <?
while ($suggestData = mysql_fetch_assoc($suggestSQL)) {
        if($i%2 == 0) {
        $bgcolor="white";
        
    } else {    
        $bgcolor="blue";
    }
?>
    <div class="height60 suggestion <?=$bgcolor;?>bg">
        <div style="margin-bottom: -5px; margin-left:5px; margin-right:5px;"><?=showUserPicture($suggestData[userid], "30");?></div>
        <a href="#" onclick="createNewTab('reader_tabView','<?=$suggestData[username];?>','','./profile.php?user=<?=$suggestData[userid];?>',true);return false" style="margin-bottom:-10px;"><?=$suggestData[username];?></a>
    </div>
<?
$i++;
$k--;
}?>
</div>
<div class="suggestionBox" class="" style="width: 94%;">
    <header>User</header>
<?
$suggestSQL = mysql_query("SELECT * FROM folders WHERE name LIKE '%$q%' LIMIT $k");
$suggestCheck = mysql_num_rows($suggestSQL);
while ($suggestData = mysql_fetch_assoc($suggestSQL)) {
    
    if(authorize($suggestData[privacy], "show", $suggestData[creator])){
    if($i%2 == 0) {
        $bgcolor="white"; 
    } else {    
        $bgcolor="blue";
    }
    ?>
    <div class="height60 suggestion <?=$bgcolor;?>bg">
        <img src="./gfx/icons/filesystem/folder.png" height="32" style="margin-bottom: -8px; margin-left:5px; margin-right:5px;">
        <a href="#" onclick="openFolder('<?=$suggestData[id];?>');"><?=$suggestData[name];?></a>
    </div>
<?
$i++;
$k--;
}}
    
echo"</div>";
$suggestSQL = mysql_query("SELECT * FROM groups WHERE title LIKE '%$q%' LIMIT $k");
?>
<div class="suggestionBox" class="" style="width: 94%;">
    <header>Groups</header>
<?
while ($suggestData = mysql_fetch_assoc($suggestSQL)) {
    if(authorize($suggestData[privacy], "show", $suggestData[creator])){
    if($i%2 == 0) {
        $bgcolor="white"; 
    } else {    
        $bgcolor="blue";
    }
?>
    <div class="height60 suggestion <?=$bgcolor;?>bg">
        <img src="" height="32" style="margin-bottom: -5px; margin-left:5px; margin-right:5px;">
        <a href="#" onclick="createNewTab('reader_tabView','<?=$suggestData[title];?>','','./group.php?id=<?=$suggestData[id];?>',true);return false"><?=$suggestData[title];?></a>
    </div>
<?
$i++;
$k--;
}}
?>
</div>
<div class="suggestionBox" class="" style="width: 94%;">
    <header>Web</header>
        <? 
        
        //start wiki//
        $url = "http://en.wikipedia.org/w/api.php?action=opensearch&limit=2&namespace=0&format=xml&search=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Miau");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        //Result in DomDocument Laden
        $dom = new DOMDocument;
        $dom->loadXML($result);
        //Array der XML Datei auslesen.
        $xml = simplexml_import_dom($dom);
        foreach ($xml->Section->Item as $item) {
        if($i%2 == 0) {
            $bgcolor="white"; 
        } else {    
            $bgcolor="blue";
        }?>
        <div class="height60 suggestion <?=$bgcolor;?>bg">
            <img src="./gfx/icons/wikipedia.png" height="32" style="margin-bottom: -5px; margin-left:5px; margin-right:5px;">
            <a href="#" onclick="openFile('wikipedia', '<?=$item->Text;?>', '<?=urlencode(substr("$item->Text", 0, 10));?>');"><?=$item->Text;?></a>
        </div>
        <?$i++;
        }
        //end wiki//
        
        //start youtube//
        $url2 = "http://gdata.youtube.com/feeds/api/videos?max-results=5&restriction=DE&q=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "$url2");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_USERAGENT, "Miau");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch2);
        curl_close($ch2);
        
        //Result in DomDocument Laden
        $dom2 = new DOMDocument;
        $dom2->loadXML($result2);
        //Array der XML Datei auslesen.
        $xml2 = simplexml_import_dom($dom2);
        foreach ($xml2->entry as $item2) {
            if($i%2 == 0) {
                $bgcolor="white"; 
            } else {    
                $bgcolor="blue";
            }

            $youhref = $item2->id;
            $vId = youTubeURLs($youhref);
            ?>
            <div class="height60 suggestion tooltipper <?=$bgcolor;?>bg" data-popType="youTube" data-typeId="<?=$vId;?>" data-text="">
                <img src="./gfx/icons/youTube.png" height="32" style="margin-bottom: -5px; margin-left:5px; margin-right:5px;" onmouseover="mousePop('youTube', '<?=$vId;?>', '');" onmouseout="$('.mousePop').hide();">
                <a href="#" onclick="openFile('youTube', '<?=$vId;?>', '<?=urlencode(substr("$item2->title", 0, 10));?>');"><?=substr("$item2->title", 0, 40);?></a>
            </div>
        <?$i++; }
        //stop youTube//
        
        //start spotify//
        $url3 = "http://ws.spotify.com/search/1/track?q=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, "$url3");
        curl_setopt($ch3, CURLOPT_HEADER, 0);
        curl_setopt($ch3, CURLOPT_USERAGENT, "Miau");
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        $result3 = curl_exec($ch3);
        curl_close($ch3);
        
        //Result in DomDocument Laden
        $dom3 = new DOMDocument;
        $dom3->loadXML($result3);
        //Array der XML Datei auslesen.
        $xml3 = simplexml_import_dom($dom3);
 
        foreach ($xml3->track as $item3) { 
            while($ig < "2"){
            $ig++;
        if($i%2 == 0) {
            $bgcolor="white"; 
        } else {    
            $bgcolor="blue";
        }
        $name = $item3->artist->name;?>
        <div class="height60 suggestion <?=$bgcolor;?>bg">
            <img src="./gfx/icons/spotify.png" height="32" style="margin-bottom: -5px; margin-left:5px; margin-right:5px;">
            &nbsp;<a href="<?=$item3[href];?>" target="submitter"><?=substr("$name", 0, 15);?> - <?=$item3->name;?></a>
        </div>
        <?$i++; }}?>
</div>
</div>
<script>

                //initialize mousePop(tooltip)
                $('.tooltipper').mouseenter(function(){
                    
                    var type = $(this).attr("data-popType");
                    var id = $(this).attr("data-typeId");
                    var text = $(this).attr("data-text");
                    mousePop(type, id, text);
                }).mouseleave(function(){
                    $('.mousePop').hide();
                });
</script>
<? }} ?>