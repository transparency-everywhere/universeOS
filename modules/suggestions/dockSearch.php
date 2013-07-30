<?
session_start();
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
					if(hasRight("showAdminPanel")){
				    ?>
				<script>
				$("#loader").load("admin.php");
				</script>
				        <?
					}
				}else if($_POST[search] == "/test"){
				    
				    jsAlert(array_values(buddyListArray("1"))."asdasd");
				    
				}else{
					

$q = save($_POST['search']);

$k = 5;//limit

//    real search start?>
<div class="dockSeachResult fancy">
    <h2>Your results</h2>
<?



//users
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		$userSuggestSQL = mysql_query("SELECT userid, username FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT $k");
		while ($suggestData = mysql_fetch_array($userSuggestSQL)) {
			echo"<li class=\"strippedRow\">".showUserPicture($suggestData[userid], "14")."<a href=\"#\" onclick=\"createNewTab('reader_tabView','$suggestData[username]','','./profile.php?user=$suggestData[userid]',true);return false\" style=\"margin-bottom:-10px;\">$suggestData[username]</a></li>";
			}
		echo"</ul>";
		
		echo"<header>Users</header>";
		
		echo"</div>";
		
		
//files
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
	//folders
		$folderSuggestSQL = mysql_query("SELECT id, name, privacy FROM folders WHERE name LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($folderSuggestSQL)) {
			
    
   			if(authorize($suggestData[privacy], "show")){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"./gfx/icons/filesystem/folder.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";
			//title
			echo"<a href=\"#\" onclick=\"openFolder('$suggestData[id]');\">$suggestData[name]</a>";
			
			echo"</li>";
		}  }
		
	//elements
		$elementSuggestSQL = mysql_query("SELECT id, title, privacy FROM elements WHERE title LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($elementSuggestSQL)) {
			
    
   			if(authorize($suggestData[privacy], "show")){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"./gfx/icons/filesystem/element.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";

			//title
			echo"<a href=\"#\" onclick=\"openElement('$suggestData[id]');\">$suggestData[title]</a>";
			
			echo"</li>";
		}  }
		
	//files
		$fileSuggestSQL = mysql_query("SELECT id, title, privacy, type FROM files WHERE title LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($fileSuggestSQL)) {
			
    
			
            $image = getFileIcon($suggestData[type]);
	
   			if(authorize($suggestData[privacy], "show")){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/fileIcons/$image\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";

			//title
			echo"<a href=\"#\" onclick=\"openElement('$suggestData[id]');\">$suggestData[title]</a>";
			
			echo"</li>";
		}  }
		
		
		
		echo"</ul>";
		
		echo"<header>Files</header>";
		
		echo"</div>";
		
	
		
//groups
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
		$groupSuggestSQL = mysql_query("SELECT id, title FROM groups WHERE title LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($groupSuggestSQL)) {
			
    
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/group.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";
			//title
			echo"<a href=\"#\" onclick=\"createNewTab('reader_tabView','$suggestData[title]','','./group.php?id=$suggestData[id]',true);return false\">$suggestData[title]</a>";
			
			echo"</li>";
		}
		
		
		
		echo"</ul>";
		
		echo"<header>Groups</header>";
		
		echo"</div>";		
		
//web
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
	//wiki
        $url = "http://en.wikipedia.org/w/api.php?action=opensearch&limit=2&namespace=0&format=xml&search=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "universeOS");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        //Result in DomDocument Laden
        $dom = new DOMDocument;
        $dom->loadXML($result);
        //Array der XML Datei auslesen.
        $xml = simplexml_import_dom($dom);
        foreach ($xml->Section->Item as $item) {
			
    
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/wikipedia.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";
			//title
			echo"<a href=\"#\" onclick=\"openFile('wikipedia', '".urlencode($item->Text)."', '".urlencode(substr("$item->Text", 0, 10))."');\">$item->Text</a>";
			
			echo"</li>";
			
		}

	
	//youtube
		$url2 = "http://gdata.youtube.com/feeds/api/videos?max-results=5&restriction=DE&q=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "$url2");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_USERAGENT, "universeOS");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch2);
        curl_close($ch2);
        
        //Result in DomDocument Laden
        $dom2 = new DOMDocument;
        $dom2->loadXML($result2);
        //Array der XML Datei auslesen.
        $xml2 = simplexml_import_dom($dom2);
        foreach ($xml2->entry as $item2) {
        	
			
			

            $youhref = $item2->id;
            $vId = youTubeURLs($youhref);
			
			
    
			echo"<li class=\"strippedRow tooltipper\" data-popType=\"youTube\" data-typeId=\"$vId\" >";
			
			//icon
			echo"<img src=\"gfx/icons/youTube.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";
			//title
			echo"<a href=\"#\" onclick=\"openFile('youTube', '$vId', '".urlencode(substr("$item2->title", 0, 10))."');\">".substr("$item2->title", 0, 40)."</a>";
			
			echo"</li>";
			
		}
		
	//spotify
        $url3 = "http://ws.spotify.com/search/1/track?q=$qEncoded"; 
        //umgehen des HTTP Verbots
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, "$url3");
        curl_setopt($ch3, CURLOPT_HEADER, 0);
        curl_setopt($ch3, CURLOPT_USERAGENT, "universeOS");
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        $result3 = curl_exec($ch3);
        curl_close($ch3);
        
        //Result in DomDocument Laden
        $dom3 = new DOMDocument;
        $dom3->loadXML($result3);
        //Array der XML Datei auslesen.
        $xml3 = simplexml_import_dom($dom3);
 
 		$i = 0;
        foreach ($xml3->track as $item3) {
        
        
        
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/spotify.png\" height=\"16\" style=\"margin-bottom: -8px; margin-left:5px; margin-right:5px;\">";
			//title
			echo"<a href=\"$item3[href]\">".substr("$item3->name", 0, 40)."</a>";
			
			echo"</li>";
		
			//set limit
			if (++$i == $k) break;
		}
		
		
		echo"</ul>";
		
		echo"<header>Web</header>";
		
		echo"</div>";
		
		
		
?>











</div>
<script>
				$('.resultList a:link').click(function(){
					$('.dockSeachResult').hide('slow');
				});
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