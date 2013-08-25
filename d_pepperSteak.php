<?
session_start();
include("inc/config.php");
include("inc/functions.php");
if(proofLogin()){
$type = "$_GET[type]";
$item = "$_GET[item]";
           if($type == "folder"){
               $typeTable = "folders";
           }else if($type == "element"){
               $typeTable = "elements";
           }else if($type == "file"){
               $typeTable = "files";
           }
    $typeSQL = mysql_query("SELECT * FROM $typeTable WHERE id='$item'");
    $typeData = mysql_fetch_array($typeSQL);
    echo($_GET[folder]);

?>
<div class="jqPopUp border-radius box-shadow" id="peppersteak">
    <table width="100%" height="100%" align="center" cellspacing="0">
        <tr>
            <td width="50%" height="50%" class="button" style="color: #000000">&nbsp;info&nbsp;</td>
            <td width="50%" height="33%" class="button">&nbsp;<a href="doit.php?action=addFav&type=<?=$type?>&item=<?=$item;?>" target="submitter" style="color: #000000">fav</a>&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" height="50%" class="button"><a href="#" onclick="javascript: popper('doit.php?action=addElement&folder=<?=$typeData[id];?>&reload=1')">add element</a>&nbsp;</td>
            <td width="50%" height="50%" class="button"><a href="#" onclick="javascript: popper('doit.php?action=addFolder&folder=<?=$typeData[id];?>&reload=1')">add folder</a></td>
        </tr> 
    </table>
</div>
<script>
    $("a").click(function () {
    $('#peppersteak').slideUp();
    });
</script>
<? }
?>