<?php

$_form = '
<style type="text/css">

</style>
<div style="width:100%;background-color:#CCFFFF;color:#000000;border-bottom:2px gray solid;text-align:center;">
<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">
<label for="x__p__address__x">' . $_lang['address'] . ': </label>
<input type="text" name="' . $_config['url_var_name'] . '" id="x__p__address__x" value="' . $_url . '" size="70" style="font-size:small" />
<input type="submit" name="submit" value="' . $_lang['go'] . '" />
[<a href="' . $_SERVER['SCRIPT_NAME'] . '">' . $_lang['main_page'] . '</a>]
<br />
';
foreach($_SESSION['_options'] as $_option => $_value) {
	if($_frozen_options[$_option]) continue;
	$_form .= '<label for="option_' . $_option . '"><input type="checkbox" name="options[' . $_option . ']" id="option_' . $_option . '" value="1"' . ($_value ? ' checked="checked"' : '') . ' /> ' . $_lang['slabel_' . $_option] . '</label> ';
}
$_form .= '
</form>
</div>
';

?>