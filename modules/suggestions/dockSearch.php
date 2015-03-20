<?
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");

include("../../inc/classes/class_search.php");


?>

<div class="dockSeachResult">
    <h2>Your results for '<?php echo htmlentities($_POST['search']);?>'</h2>
    <?php
    $search = new search($_POST['search']);
    
    echo $search->parseSearchResults();
    
    ?>
</div>
<script>
    search.initResultHandlers('<?php echo htmlentities($_POST['search']);?>');
</script>