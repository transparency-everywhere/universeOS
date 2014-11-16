<?
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");
$qEncoded = urlencode($_POST['search']);
if(strlen("$_POST[search]") > 2){
    
//    syntax code
				if($_POST['search'] == "/bug"){
				    ?>
				<script>
				$("#loader").load("doit.php?action=reportBug");
				</script>
				        <?
				}else if($_POST['search'] == "/admin"){
					if(hasRight("showAdminPanel")){
				    ?>
				<script>
				$("#loader").load("admin.php");
				</script>
				        <?
					}
				}else if($_POST['search'] == "/test"){
				    $buddylistClass = new buddylist();
				    jsAlert(array_values($buddylistClass->buddyListArray("1"))."asdasd");
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
			echo"<li class=\"strippedRow\">".showUserPicture($suggestData['userid'], "14")."<a href=\"#\" onclick=\"showProfile('".$suggestData['userid']."');\" style=\"margin-bottom:-10px;\">".$suggestData['username']."</a></li>";
			}
		echo"</ul>";
		
		echo"<header>Users</header>";
		
		echo"</div>";
		
		
//files
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
	//folders
		$folderSuggestSQL = mysql_query("SELECT id, name, privacy, creator FROM folders WHERE name LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($folderSuggestSQL)) {
			
    
   			if(authorize($suggestData['privacy'], "show", $suggestData['creator'])){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"./gfx/icons/filesystem/folder.png\" height=\"16\" style=\"\">";
			//title
			echo"<a href=\"#\" onclick=\"openFolder('$suggestData[id]');\">$suggestData[name]</a>";
			
			echo"</li>";
		}  }
		
	//elements
		$elementSuggestSQL = mysql_query("SELECT id, title, privacy, author FROM elements WHERE title LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($elementSuggestSQL)) {
			
    
   			if(authorize($suggestData['privacy'], "show", $suggestData['author'])){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"./gfx/icons/filesystem/element.png\" height=\"16\" style=\"\">";

			//title
			echo"<a href=\"#\" onclick=\"openElement('$suggestData[id]');\">$suggestData[title]</a>";
			
			echo"</li>";
		}  }
		
	//files
		$fileSuggestSQL = mysql_query("SELECT id, title, privacy, type, owner FROM files WHERE title LIKE '%$q%' LIMIT $k");
		while ($suggestData = mysql_fetch_array($fileSuggestSQL)) {
			
    
            $classFiles = new files();
            $image = $classFiles->getFileIcon($suggestData['type']);
	
   			if(authorize($suggestData['privacy'], "show", $suggestData['owner'])){
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/fileIcons/$image\" height=\"16\" style=\"\">";

			//title
			echo"<a href=\"#\" onclick=\"openFile('$suggestData[type]', '$suggestData[id]', '$suggestData[title]');\">$suggestData[title]</a>";
			
			echo"</li>";
		}  }
		
		
		
		echo"</ul>";
		
		echo"<header>Files</header>";
		
		echo"</div>";
		
	
		
//groups
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
		$groupSuggestSQL = mysql_query("SELECT id, title FROM groups WHERE title LIKE '%$q%' AND public='1' LIMIT $k");
		while ($suggestData = mysql_fetch_array($groupSuggestSQL)) {
			
    
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/group.png\" height=\"16\" style=\"\">";
			//title
			echo"<a href=\"#\" onclick=\"showApplication('reader'); reader.tabs.addTab('".$suggestData['title']."', '',gui.loadPage('./group.php?id=".$suggestData['id']."'));return false\">$suggestData[title]</a>";
			
			echo"</li>";
		}
		
		
		
		echo"</ul>";
		
		echo"<header>Groups</header>";
		
		echo"</div>";		
		
//web
		echo"<div class=\"listContainer\">";

		echo"<ul class=\"list resultList\">";
		
		
	//wiki
        $xml = xml::curler("http://en.wikipedia.org/w/api.php?action=opensearch&limit=2&namespace=0&format=xml&search=$qEncoded");
        foreach ($xml->Section->Item as $item) {
			
    
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/fileIcons/wikipedia.png\" height=\"16\" style=\"\">";
			//title
			echo"<a href=\"#\" onclick=\"openFile('wikipedia', '".urlencode($item->Text)."', '".urlencode(substr("$item->Text", 0, 10))."');\">$item->Text</a>";
			
			echo"</li>";
			
		}

	
	//youtube
        $xml2 = xml::curler("http://gdata.youtube.com/feeds/api/videos?max-results=5&restriction=DE&q=$qEncoded");
        foreach ($xml2->entry as $item2) {
            $youtubeClass = new youtube($item2->id);
            $vId = $youtubeClass->getId();
			echo"<li class=\"strippedRow tooltipper\" data-popType=\"youTube\" data-typeId=\"$vId\" >";
			//icon
			echo"<img src=\"gfx/icons/fileIcons/youTube.png\" height=\"16\" style=\"\">";
			//title
			echo"<a href=\"#\" onclick=\"openFile('youTube', '', '".urlencode(substr("$item2->title", 0, 10))."', '$vId');\">".substr("$item2->title", 0, 40)."</a>";
			echo"</li>";
			
		}
		
	//spotify
        $xml3 = xml::curler("http://ws.spotify.com/search/1/track?q=$qEncoded");
 
 		$i = 0;
        foreach ($xml3->track as $item3) {
        
        
        
			echo"<li class=\"strippedRow\">";
			
			//icon
			echo"<img src=\"gfx/icons/fileIcons/spotify.png\" height=\"16\" style=\"\">";
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

<div class="listContainer">
	<ul class="list resultList">
		<li class="strippedRow"><a target="_blank" href="http://www.amazon.de/gp/search?ie=UTF8&camp=1638&creative=6742&index=aps&keywords=<?=htmlentities($qEncoded);?>&linkCode=ur2&tag=universeos-21">Find on Amazon</a></li>
	</ul>
	<header>Advertising</header>
</div>












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