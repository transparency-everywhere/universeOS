<?php include 'header.php'; ?>
			<h1 class="title"><?php echo $_lang['surf_web']; ?></h1>
			<div class="entry">
				<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
					<strong><?php echo $_lang['address']; ?>:</strong> <input type="text" id="__address_box__" name="<?php echo $_config['url_var_name']; ?>" size="60" value="<?php echo $_data['url']; ?>" class="text"/>
					<input type="submit" name="submit" value="<?php echo $_lang['submit']; ?>" class="button">
					<input type="reset" name="reset" value="<?php echo $_lang['reset']; ?>" class="button">
					<?php if(isset($_data['error']) and $_data['error']) { ?>
					<?php if($_data['error'] == 'authorization_required') { ?>
					<strong><p><?php echo $_lang['authorization_required']; ?>:</p></strong>
					<blockquote>
						<?php echo $_lang['enter_username_password']; ?><br />
						<?php echo $_lang['username']; ?>: <input type="text" name="username" id="username" size="30" value="<?php echo $_data['username']; ?>" class="text" />
						<br />
						<?php echo $_lang['password']; ?>: <input type="password" name="password" id="password" size="30" value="<?php echo $_data['password']; ?>" class="text" />
					</blockquote>
					<?php } else { ?>
					<strong><p><?php echo $_lang['error']; ?></p></strong>
					<blockquote>
						<?php echo $_lang[$_data['error']]; ?>
					</blockquote>
					<?php } ?>
					<?php } ?>
					<strong><p><?php echo $_lang['options']; ?>:</p></strong>
					<blockquote>
						<?php
						foreach($_SESSION['_options'] as $_option => $_value) {
							if($_frozen_options[$_option]) continue;
						?>
						<label for="option_<?php echo $_option; ?>"><input type="checkbox" name="options[<?php echo $_option; ?>]" id="option_<?php echo $_option; ?>" value="1"<?php if($_value) { ?> checked="checked"<?php } ?> /> <?php echo $_lang['label_' . $_option]; ?></label><br />
						<?php
						}
						?>
					</blockquote>
				</form>
			</div>
			<script language="javascript" type="text/javascript">
			function __focus() {
				document.getElementById('__address_box__').focus();
			}
			__focus();
			</script>
<?php include 'footer.php'; ?>
