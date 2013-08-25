<?php
      session_start();
  include("../../inc/config.php");
  include("../../inc/functions.php");
    if($_POST[submit]){
    	
		
		
    }?>
<!DOCTYPE html> 
<html xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>New JavaScript SDK & OAuth 2.0 based FBConnect Tutorial | Thinkdiff.net</title>
        <!--
            @author: Mahmud Ahsan (http://mahmud.thinkdiff.net)
        -->
    </head>
    <body>
        <div id="fb-root"></div>
        <script type="text/javascript">
            
        </script>

        <h3>New JavaScript SDK & OAuth 2.0 based FBConnect Tutorial | Thinkdiff.net</h3>
        <button id="fb-auth">Login</button>
        <div id="loader" style="display:none">
            <img src="ajax-loader.gif" alt="loading" />
        </div>
        <br />
        <div id="user-info"></div>
        <br />
        <div id="debug"></div>
        
        <div id="other" style="display:none">
            <a href="#" onclick="showStream(); return false;">Publish Wall Post</a> |
            <a href="#" onclick="share(); return false;">Share With Your Friends</a> |
            <a href="#" onclick="graphStreamPublish(); return false;">Publish Stream Using Graph API</a> |
            <a href="#" onclick="fqlQuery(); return false;">FQL Query Example</a>
            
            <br />
            <textarea id="status" cols="50" rows="5">Write your status here and click 'Status Set Using Legacy Api Call'</textarea>
            <br />
            <a href="#" onclick="setStatus(); return false;">Status Set Using Legacy Api Call</a>
        </div>
    </body>
</html>