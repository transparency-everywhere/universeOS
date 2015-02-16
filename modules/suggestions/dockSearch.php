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
		$('.resultList a:link').click(function(){
			$('.dockSeachResult').hide('slow');
		});
                
                $('.resultList .icon-gear').click(function(){
                        $(this).parent().next('li').slideToggle();
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