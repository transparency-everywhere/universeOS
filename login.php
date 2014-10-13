<?PHP
include("inc/config.php");
include("inc/functions.php");
session_start();
if(isset($_POST['submit']) && !empty($_POST['username'])) {
    if(user::login($_POST['username'], $_POST['password'])){
    ?>
	<script type="text/javascript">
            parent.window.location.href = "index.php";
	</script>
    <?
        die();
    }else {
    ?>
      	<script>
            parent.jsAlert('', '<?=addslashes("The username and password doesn't match");?>');
      	</script>
    <?
    }
  }
  ?>