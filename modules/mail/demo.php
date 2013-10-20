<?
##################################################################
# IMAP Inbox Notifier 
# by Nikos Tsaganos
# http://www.backslash.gr - nikos@backslash.gr 
# 
# DISCLAIMER:
# This is a demo on how to use some imap php functions. 
# It is NOT recommended to store your passwords in an 
# unencoded php file on a shared server.
##################################################################

// general password protection
// leave blank if you don't want to protect this page
$pageauth['username'] = 'nic';


// configure your imap mailboxes
$mailboxes = array(
	array(
		'label' 	=> 'transpev',
		'enable'	=> true,
		'mailbox' 	=> '{transparency-everywhere.com:993/imap/ssl/novalidate-cert}INBOX',
		'username' 	=> 'nic@transparency-everywhere.com',
		'password' 	=> 'w1ttg3nst31n'
	)
);

if ($pageauth['username'] && $pageauth['password']) {
	if(1 == 2){
		header("WWW-Authenticate: Basic realm=Protected area" );
		header("HTTP/1.0 401 Unauthorized");
		echo "Protected area";
		exit;
	}
}


// a function to decode MIME message header extensions and get the text
function decode_imap_text($str){
    $result = '';
    $decode_header = imap_mime_header_decode($str);
    foreach ($decode_header AS $obj) {
        $result .= htmlspecialchars(rtrim($obj->text, "\t"));
	}
    return $result;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mailbox checker with PHP and IMAP - Backslash</title>
<link href="css/demo.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
<div id="main">

	<h1>Mailbox checker with PHP and IMAP</h1>
	
	<p>For more demos like this visit <a href="http://www.backslash.gr/">Backslash.gr</a>.</p>	
	
	<div id="mailboxes">
	<? if (!count($mailboxes)) { ?>
		<p>Please configure at least one IMAP mailbox.</p>
	<? } else { 

		foreach ($mailboxes as $current_mailbox) {
			?>
			<div class="mailbox">
			<h2><?=$current_mailbox['label']?></h2>
			<?
			if (!$current_mailbox['enable']) {
			?>
				<p>This mailbox is disabled.</p>
			<?
			} else {
				
				// Open an IMAP stream to our mailbox
				$stream = @imap_open($current_mailbox['mailbox'], $current_mailbox['username'], $current_mailbox['password']);
				
				if (!$stream) { 
				?>
					<p>Could not connect to: <?=$current_mailbox['label']?>. Error: <?=imap_last_error()?></p>
				<?
				} else {
					// Get our messages from the last week
					// Instead of searching for this week's message you could search for all the messages in your inbox using: $emails = imap_search($stream,'ALL');
					$emails = imap_search($stream, 'SINCE '. date('d-M-Y',strtotime("-1 week")));
					
					if (!count($emails)){
					?>
						<p>No e-mails found.</p>
					<?
					} else {

						// If we've got some email IDs, sort them from new to old and show them
						rsort($emails);
						
						foreach($emails as $email_id){
						
							// Fetch the email's overview and show subject, from and date. 
							$overview = imap_fetch_overview($stream,$email_id,0);						
							?>
							<div class="email_item clearfix <?=$overview[0]->seen?'read':'unread'?>"> <? // add a different class for seperating read and unread e-mails ?>
								<span class="subject" title="<?=decode_imap_text($overview[0]->subject)?>"><?=decode_imap_text($overview[0]->subject)?></span>
								<span class="from" title="<?=decode_imap_text($overview[0]->from)?>"><?=decode_imap_text($overview[0]->from)?></span>
								<span class="date"><?=$overview[0]->date?></span>
							</div>
							<?
						} 
					} 
					imap_close($stream); 
				}
				
			} 
			?>
			</div>
			<?
		} // end foreach 
	} ?>
	</div>
	
</div><!-- #main -->

<div id="footer">
	<p>Copyright &copy; 2011 <a href="http://www.backslash.gr">Backslash</a> &bull; Created by <a href="http://www.backslash.gr">Nikos Tsaganos</a></p>
</div>
</div><!-- #wrapper -->
</body>
</html>