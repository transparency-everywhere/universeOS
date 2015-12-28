<?php
require_once("../../inc/config.php");
require_once("../../inc/functions.php");
$type = save($_GET['type']);
$itemId = save($_GET['itemid']);





    $db = new db();
    $data = $db->select('comments', array('type', $type, '&&', 'typeid', $itemId));
    if($type === 'comment')
        $itemId = $data['typeid'];
    $personalEvents = new personalEvents();
    $personalEvents->clean('comment',  $data['type'], $itemId);

$classComments = new comments();
$classComments->showComments($type, $itemId);
?>
