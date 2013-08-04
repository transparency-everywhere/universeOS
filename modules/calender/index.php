<?php
if(!isset($_SESSION[userid])){
    session_start();
    include("../../inc/config.php");
    include("../../inc/functions.php");
}
$startTime = $_GET[startTime];
$firstDayOfMonth = firstDayOfMonth($startTime);

?>  
<div class="fenster" id="feed" style="display: none;">
    <header class="titel">
        <p>Feed&nbsp;</p><p class="windowMenu"><a href="javascript:hideApplication('feed');"><img src="./gfx/icons/close.png" width="16"></a></p>
    </header>
    <div class="inhalt autoFlow">
    	<div>
    		<ul>
    			<li>Monday</li>
    			<li>Tuesday</li>
    			<li>Wednesday</li>
    			<li>Thursday</li>
    			<li>Friday</li>
    			<li>Saturday</li>
    			<li>Sunday</li>
    		</ul>
    		<ul>
    			<?
    			//generate a 5x7 Matrix
    			while($i<35){
    				
					echo"<li></li>";
					
					$i++;
    			}
    			?>
    		</ul>
    	</div>
    </div>
</div>