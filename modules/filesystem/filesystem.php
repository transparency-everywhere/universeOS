<?
if(empty($_SESSION[userid])) {
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");
}
//to see the standard view of the filesystem scroll down to the buttom

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


if($_GET[action] == edit_folder) {
if($_GET[fileid]) { 
$foldersql = mysql_query("SELECT * FROM folders WHERE id='$_GET[fileid]'");
$folderdata = mysql_fetch_array($foldersql);
if($_POST[submit]) {
mysql_query("UPDATE `folders` SET  `name`='$_POST[name]', `folder`='$_POST[folder]' WHERE  id='$_GET[fileid]'");
$message = "Datei wurde erfolgreich editiert!";
}
?>



<table>
 <tr valign="top" height="25">
   <td align="center">&nbsp;</td>
 </tr>
 <tr>
  <td>
   <table>
    <tr background="./gfx/navitop.gif" height="31"> 
     <td align="center">Verzeichnis bearbeiten</td>
    </tr>
    <tr>
     <td>
      <table>
       <tr>
        <td>&nbsp;<?=$message;?></td>
       </tr>
       <tr>
        <table>
         <tr>
          <td>Name:</td><form action="" method="post">
          <td><input type="text" name="name" value="<?=$folderdata[name];?>"></td>
         </tr>
         <tr>
          <td>Verzeichnis:</td>
          <td>

    <select name="folder">
<?
$selectsql = mysql_query("SELECT * FROM folders ORDER BY name ASC");
while($selectdata = mysql_fetch_array($selectsql)) { 

unset($chosen);
if($selectdata[id] == $folderdata[folder]) {
$chosen = "selected=\"yes\""; }
?>
      <option value="<?=$selectdata[id];?>" <?=$chosen;?>><?=$selectdata[name];?></option>
<? } ?>
    </select>


          </td>
         </tr>
         <tr>
          <td><input type="submit" value="Speichern" name="submit"></td></form>
         </tr>
         <tr>
          <td>&nbsp;</td>
         </tr>
      </table>   
     </td>
    </tr>
    <tr>
     <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>



<?

} else {
?>




<table>
 <tr valign="top" height="25">
   <td align="center">&nbsp;</td>
 </tr>
 <tr valign="top">
  <td valign="top">
   <table valign="top">
    <tr> 
     <td align="center">Datei bearbeiten</td>
    </tr>
    <tr>
     <td>
      <table>
       <tr height="50">
        <td>&nbsp;<?=$message;?></td>
       </tr>
       <tr>
        <td>
         <table> 
          <tr>
           <td>&nbsp;</td>
           <td>Name:</td>
           <td>Editieren</td>
           <td>L&ouml;schen</td>
          </tr>
          <tr>
           <td>&nbsp;</td>
          </tr>

<?
$whilesql = mysql_query("SELECT * FROM folders ORDER BY name ASC");
while($whiledata = mysql_fetch_array($whilesql)) {
 ?>
          <tr>
           <td>&nbsp;&nbsp;<img src="./gfx/folder.png" width="20"></td>
           <td><?=$whiledata[name];?></td>
           <td><a href="?action=edit_folder&fileid=<?=$whiledata[id];?>"><img src="./gfx/edit.png"></a></td>
           <td><a href="?action=delete&fileeid=<?=$whiledata[id];?>"><img src="./gfx/delete_2.png"></a></td>
          </tr>
<? } ?>
         </table>
        </td>
       </tr>
      </table>   
     </td>
    </tr>
    <tr>
     <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<?
} }
else if(!$_GET[action]) {
if($_GET[folder]) {
$folder = $_GET[folder];
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
if(!isset($_GET[reload])){
 } ?>