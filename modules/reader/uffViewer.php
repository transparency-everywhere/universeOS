<?

session_start();
$includepath = "../../inc";
$includepath = "$path$includepath";
include("$includepath/config.php");
include_once("$includepath/functions.php");
$id = $_GET[id];

$fileData = mysql_query("SELECT * FROM files WHERE id='$id'");
$fileData = mysql_fetch_array($fileData);

if(authorize($fileData[privacy], "edit", $fileData[owner])){
    $readOnly = "false";
}else{
    $readOnly = "true";
}

$title = $fileData[title];
$activeUsers = explode(";", $fileData[var1]);
?>
    <head>
        <style>
            .uffViewerMain{
                position: absolute;
                top: 61px;
                right: 150px;
                bottom: 0px;
                left: 0px;
                overflow: auto;
            }
            
            .uffViewerMain ol{
                list-style-type:decimal;
            }
            
            .uffViewerMain li{
                height: auto;
            }

            .uffViewerNav{
                position: absolute;
                top: 61px;
                right: 0px;
                bottom: 0px;
                width: 150px;
                background: #FFFFFF;
                border-left: 1px solid #c9c9c9;
            }
            
            .uffDocumentSettings{
                position: absolute;
                margin-top: 40px;
                background: #E5E5E5;
                height: 24px;
                padding: 3px;
                border-bottom: 1px solid #c9c9c9;
                border-right: 1px solid #c9c9c9;
            }
            
            .cke_editor_1{
                position: absolute;
                top: 0px;
                right: 150px;
                bottom: 0px;
            }
        </style>
        <script src="inc/plugins/ckeditor/ckeditor.js"></script>
        <script>
//		window.onload = function() {
//                    alert('heyheyhey');
//			// Listen to the double click event.
//			if ( window.addEventListener )
//				document.body.addEventListener( 'dblclick', onDoubleClick, false );
//			else if ( window.attachEvent )
//				document.body.attachEvent( 'ondblclick', onDoubleClick );
//
//		};
//
//		function onDoubleClick( ev ) {
//			// Get the element which fired the event. This is not necessarily the
//			// element to which the event has been attached.
//			var element = ev.target || ev.srcElement;
//
//			// Find out the div that holds this element.
//			var name;
//
//			do {
//				element = element.parentNode;
//			}
//			while ( element && ( name = element.nodeName.toLowerCase() ) &&
//				( name != 'div' || element.className.indexOf( 'editable' ) == -1 ) && name != 'body' );
//
//			if ( name == 'div' && element.className.indexOf( 'editable' ) != -1 )
//				replaceDiv( element );
//		}
//
//		var editor;
//
//		function replaceDiv( div ) {
//			if ( editor )
//				editor.destroy();
//
//			editor = CKEDITOR.replace( div );
//		}
        </script>
    </head>
    
<!--this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js-->    
<iframe src="modules/reader/UFF/javascript.php?fileId=<?=$id;?>&readOnly=<?=$readOnly;?>" style="display:none;"></iframe>


<table class="gray-gradient" width="100%" style="position: absolute;">
    <tr height="40">
        <td width="40%">&nbsp;&nbsp;<?=$title;?></td>
        <td width="10%"></td>
        <td width="40%" align="right"><?=$bar;?>&nbsp;</td>
        <td width="10%"></td>
    </tr>
</table>
<div class="uffViewerNav">
    <div style="margin: 10px;">
        <ul>
            <li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon-user"></i>&nbsp;<strong>Active Users</strong></li>
            <?
            
            //show active users
            foreach($activeUsers AS &$activeUser){
                if(!empty($activeUser)){
                echo"<li onclick=\"openProfile($activeUser);\" style=\"cursor: pointer;\">";
                showUserPicture($activeUser, "11");
                echo "&nbsp;";
                echo useridToUsername($activeUser);
                echo"</li>";
                }
            }
            ?>
        </ul>
    </div>
</div>
<div class="uffViewerMain">
    <textarea class="uffViewer_<?=$id;?> WYSIWYGeditor" id="editor1" onkeyup="alert('ey');">


        das problem hier ist, dass durch dieses scheiß tabscript, keine javascript ausgefuehrt wird.

        <br>moegliche loesung:<br><?=$id;?>alle scripts werden in einem iframe mit .parent ausgefueht.(onload, onkeyup, insert[b](...))<br>(reload)andere loesung $_SESSION[openUffFiles] wird gesetzt und in der reload.php wird der reload für alle $_SESSION ausgeführt. if(checkSumNew != checkSumOld){ loadUFF() }
    <?PHP

    //writeUffFile($id, "komm schooooon, was");
    //echo showUffFile($id);
    //
    //if(empty($_GET[action])){
    //    
    //}else if($_GET[action] == "addFile"){
    //    
    //}else if($_GET[action] == "editFile"){
    //    
    //}else if($_GET[action] == "viewFile"){
    //    
    //}
    ?>
    </textarea>
</div>