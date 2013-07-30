<?php
if(isset($_POST[submit])){
    $i = 1;
    while($i < $_POST[value]){
        
$to = $_POST[receiver];
$subject =" $_POST[title]$i";
$message = md5($_POST[text]);
$from = "$_POST[sender]$@i$_POST[sender2]$i.$_POST[sender3]";
$headers = "From:" . $_POST[sender];
mail($to,$subject,$message,$headers);
//mail function


        $i++;
    }
    echo "$i messages sended ;)"; 
}
?>
<form action="" method="post">
<table>
    <tr>
        <td>From</td>
        <td><input type="text" name="sender" placeholder="ernest">@<input type="text" name="sender2" placeholder="gmail">.<input type="text" name="sender3" placeholder=".com">
    </tr>
    <tr>
        <td>To</td>
        <td><input type="text" name="receiver" placeholder="george@whitehouse.gov"></td>
    </tr>
    <tr>
        <td>Title</td>
        <td><input type="text" name="title" placeholder="Important Mail"></td>
    </tr>
    <tr>
        <td>Text</td>
        <td><textarea name="text" placeholder="message"></textarea></td>
    </tr>
    <tr>
        <td>Copies</td>
        <td><input type="text" name="value"></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="submit" name="submit" value="send :)">
        </td>
    </tr>
</table>
</form>