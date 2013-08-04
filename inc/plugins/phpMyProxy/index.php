<?php

/*
 phpMyProxy is a free php proxy script programmed by eProxies.info Team.
 Website: http://www.phpmyproxy.com
 Support: http://www.eproxies.info/support/
 Proxy Directory: http://www.eproxies.info/
 
 Created: Thursday, December 13, 2007 09:49:28 PM
 Modified: Saturday, January 13, 2008
*/

error_reporting(E_ALL);
session_start();
//@set_time_limit(0);

define('_PROXY_VERSION', '1.0.3');
define('_CWD', dirname(__FILE__));
//define('_SAFE_MODE', (bool)@ini_get('safe_mode'));
//define('_OPEN_BASEDIR', (bool)@ini_get('open_basedir'));

if(get_magic_quotes_gpc()) {
	if(@ini_get('magic_quotes_sybase')) {
		$_GET = _strip_single_quotes($_GET);
		$_POST = _strip_single_quotes($_POST);
		$_COOKIE = _strip_single_quotes($_COOKIE);
	} else {
		$_GET = _stripslashes($_GET);
		$_POST = _stripslashes($_POST);
		$_COOKIE = _stripslashes($_COOKIE);
	}
}

// Require configurations
require './proxy.config.php';

// Get required language
$_langfile = './lang/' . $_lang . '.php';
if(!file_exists($_langfile)) {
	$_langfile = './lang/english.php';
}
include $_langfile;

// Get theme directory
$_theme_dir = './themes/' . $_theme;
if(!file_exists($_theme_dir)) {
	$_theme_dir = './themes/default';
}
define('_THEME_DIR', $_theme_dir);
unset($_langfile, $_theme_dir);


if(!function_exists('http_build_query')) { // PHP4 doesn't have this function, we have to create one like PHP5.
	function http_build_query($formdata, $prefix = '', $first = true) {
		static $data = array();
		static $size = false;
		static $count = 0;
		if(is_object($formdata)) {
			$formdata = unserialize(serialize($formdata));
		}
		if($size == false) $size = count($formdata);
		foreach($formdata as $key => $value) {
			$key = urlencode($key);
			if(is_object($value)) {
				$value = unserialize(serialize($value));
			}
			if($first) $count++;
			if(is_array($value)) {
				http_build_query($value, (($first and !is_numeric($key)) ? '' : $prefix) . $key . ($first ? '' : ']') . '[', false);
			} else {
				$data[] = (($first and !is_numeric($key)) ? '' : $prefix) . $key . ($first ? '' : ']') . '=' . urlencode($value);
			}
		}
	
		if($count == $size and $first) return ($data) ? implode('&', $data) : '';
	}
}


// Do something
$_SERVER['REQUEST_METHOD'] = strtoupper($_SERVER['REQUEST_METHOD']);
$_referer = (isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : false);

// Get available protocols
$_protocols = array('http', 'ftp');
$_curl_version = curl_version();
$_https = ((is_array($_curl_version) and in_array('https', $_curl_version['protocols'])) or (is_string($_curl_version) and strpos($_curl_version, 'OpenSSL/') !== false)) ? true : false;
if($_https) {
	$_protocols[] = 'https';
}
unset($_curl_version);

// Setup options and take relevant action
if(!isset($_SESSION['_options']) or !is_array($_SESSION['_options'])) {
	$_SESSION['_options'] = array();
}
if(isset($_POST[$_config['url_var_name']]) and !isset($_GET[$_config['url_var_name']])) {
	if(!isset($_POST['options']) or !is_array($_POST['options'])) {
		$_POST['options'] = array();
	}
	foreach($_options as $_option => $_value) {
		$_SESSION['_options'][$_option] = (!$_frozen_options[$_option] ? (isset($_POST['options'][$_option]) ? $_POST['options'][$_option] : 0) : $_value);
	}
	
	$_url = trim($_POST[$_config['url_var_name']]);
} elseif(isset($_GET[$_config['url_var_name']])) {
	foreach($_options as $_option => $_value) {
		$_SESSION['_options'][$_option] = ((isset($_SESSION['_options'][$_option]) and !$_frozen_options[$_option]) ? $_SESSION['_options'][$_option] : $_value);
	}

	$_url = _decode_url(trim($_GET[$_config['url_var_name']]));
	if((count($_GET) > 1 and !isset($_GET['_x_fin_x_'])) or (count($_GET) > 2 and isset($_GET['_x_fin_x_']))) {
		unset($_GET[$_config['url_var_name']], $_GET['_x_fin_x_']);
		$_url .= ($_GET) ? '?' . http_build_query($_GET) : '';
		_redirect(_rewrite_url($_url));
	}
} else {
	foreach($_options as $_option => $_value) {
		$_SESSION['_options'][$_option] = ((isset($_SESSION['_options'][$_option]) and !$_frozen_options[$_option]) ? $_SESSION['_options'][$_option] : $_value);
	}
	_report();
}

// Validate user input
if($_url == '') {
	_report(array('error' => 'empty_url'));
}
if(!in_array($_SERVER['REQUEST_METHOD'], array('HEAD', 'GET', 'POST'))) {
	_report(array('error' => 'unsupported_method'));
}

if(strpos($_url, ':') === false) {
	$_url = 'http://' . $_url;
}
$_url = preg_replace('#:/{3,}#i', '://', $_url);

$_url_com = @parse_url($_url);
$_url_com['scheme'] = strtolower($_url_com['scheme']);
if(empty($_url_com) or !isset($_url_com['host'])) {
	_report(array('error' => 'incorrect_url'));
}
if(preg_match('/^127\.|192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\./i', $_url_com['host']) or strpos($_url_com['host'], '.') === false) {
	_report(array('error' => 'denied_url'));
}
foreach($_disallowed_hosts as $_disallowed_host) {
	if($_url_com['host'] == $_disallowed_host or $_url_com['host'] == 'www.' . $_disallowed_host) {
		_report(array('error' => 'denied_url'));
	}
}
if(!in_array($_url_com['scheme'], $_protocols)) {
	_report(array('error' => 'unsupported_protocol'));
}


// Prevent HotLinking
if(!$_config['allow_hotlinking']) {
	$_hotlink = true;
	if($_referer) {
		$_hotlink_domains[] = $_SERVER['HTTP_HOST'];
		$_referer_com = @parse_url($_referer);
		foreach($_hotlink_domains as $_hotlink_domain) {
			if($_hotlink_domain == $_referer_com['host'] or $_hotlink_domain == 'www.' . $_referer_com['host'] or 'www.' . $_hotlink_domain == $_referer_com['host']) {
				$_hotlink = false;
				break;
			}
		}
	} elseif (!$_config['nonreferer_hotlink'] and !$_referer) {
		$_hotlink = false;
	}

	if($_hotlink) {
		switch($_config['upon_hotlink']) {
			case 1:
				_report(array('error' => 'hotlinking'));
			break;
			
			case 2:
				header('HTTP/1.0 404 Not Found');
				exit;
			break;
			
			default:
				_redirect($_config['upon_hotlink']);
		}
	}
} // End of hotlinking prevention

// Save username and password to the session
if(!isset($_SESSION['_authorization'])) {
	$_SESSION['_authorization'] = array();
}
if(isset($_POST[$_config['url_var_name']]) and (isset($_POST['username']) or isset($_POST['password']))) {
	$_SESSION['_authorization'][$_url_com['host']] = (isset($_POST['username']) ? $_POST['username'] : '') . ':' . (isset($_POST['password']) ? $_POST['password'] : '');
} else {
	$_SESSION['_authorization'][$_url_com['host']] = (isset($_SESSION['_authorization'][$_url_com['host']]) ? $_SESSION['_authorization'][$_url_com['host']] : '');
}

// Complete URL
$_url = $_url_com['scheme'] . '://';
$_base = array('scheme' => $_url_com['scheme']);
if(isset($_url_com['user']) or isset($_url_com['pass'])) {
	$_url .= $_url_com['user'];
	if(isset($_url_com['pass'])) {
		$_url .= ':' . $_url_com['pass'];
	}
	$_url .= '@';
}
$_url .= $_url_com['host'];
if(isset($_url_com['port'])) {
	$_url .= ':' . $_url_com['port'];
}
$_base['webroot'] = $_url;
if(isset($_url_com['path'])) {
	$_url .= $_url_com['path'];
}
$_base['path'] = isset($_url_com['path']) ? $_url_com['path'] : '/';
$_base['webpath'] = $_url;
if(isset($_url_com['query'])) {
	$_url_com['query'] = str_replace('&amp;', '&', $_url_com['query']);
	parse_str($_url_com['query'], $_query_pairs);
	$_url .= '?' . http_build_query($_query_pairs);
	unset($_query_pairs);
}
$_base['url'] = $_url;
//echo $_url;exit;


// Redirect if neccessary
if(isset($_POST[$_config['url_var_name']]) and !isset($_GET[$_config['url_var_name']])) {
	_redirect('?' . $_config['url_var_name'] . '=' . _encode_url($_url) . (isset($_url_com['fragment']) ? '#' . $_url_com['fragment'] : ''));
}

// Setup options
$_curl_options = array();
$_uploaded_files = array();
if($_SERVER['REQUEST_METHOD'] != 'GET') {
	$_curl_options[CURLOPT_CUSTOMREQUEST] = $_SERVER['REQUEST_METHOD'];
	if(!isset($_POST[$_config['url_var_name']]) and $_SERVER['REQUEST_METHOD'] == 'POST') {
		$_postfields = $_POST;
		if(@ini_get('file_uploads') and $_FILES) {
			foreach($_FILES as $_key => $_value) {
				if(is_array($_value['tmp_name'])) {
					foreach($_value['tmp_name'] as $_k => $_v) {
						$_new_name = dirname($_v) . '/' . $_value['name'][$_k];
						@unlink($_new_name);
						if(!@rename($_v, $_new_name)) {
							$_new_name = $_v;
						}
						$_postfields[$_key . '[' . $_k . ']'] = '@' . $_new_name;
					}
				} else {
					$_new_name = dirname($_value['tmp_name']) . '/' . $_value['name'];
					@unlink($_new_name);
					if(!@rename($_value['tmp_name'], $_new_name)) {
						$_new_name = $_value['tmp_name'];
					}
					$_postfields[$_key] = '@' . $_new_name;
				}
			}
		}
		$_curl_options[CURLOPT_POSTFIELDS] = $_postfields;
		unset($_postfields, $_new_name);
	}
}
if($_url_com['scheme'] == 'https') {
	$_curl_options[CURLOPT_SSL_VERIFYPEER] = false;
	$_curl_options[CURLOPT_SSL_VERIFYHOST] = false;
}

// Pass headers
$_passible_headers = array(
	'HTTP_ACCEPT' => 'Accept',
	'HTTP_ACCEPT_CHARSET' => 'Accept-Charset',
	'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language',
	'HTTP_USER_AGENT' => 'User-Agent',
	'HTTP_CACHE_CONTROL' => 'Cache-Control',
);
$_headers = array();
foreach($_passible_headers as $_header_key => $_header_name) {
	if(isset($_SERVER[$_header_key])) $_headers[] = $_header_name . ': ' . $_SERVER[$_header_key];
}

// Remove referers
if($_SESSION['_options']['remove_referers']) {
	$_headers[] = 'Referer: ';
} else {
	if($_referer) {
		$_referer_com = @parse_url($_referer);
		if($_url_com['host'] == $_referer_com['host'] or $_url_com['host'] == 'www.' . $_referer_com['host'] or 'www.' . $_url_com['host'] == $_referer_com['host']) {
			$_headers[] = 'Referer: ' . $_referer;
		} else if($_referer_com['host'] == $_SERVER['HTTP_HOST'] or $_referer_com['host'] == 'www.' . $_SERVER['HTTP_HOST'] or 'www.' . $_referer_com['host'] == $_SERVER['HTTP_HOST']) {
			if(isset($_referer_com['query'])) {
				$_referer_vars = array();
				parse_str($_referer_com['query'], $_referer_vars);
				$_internal_referer = _decode_url($_referer_vars[$_config['url_var_name']]);
				$_headers[] = 'Referer: ' . (($_internal_referer) ? $_internal_referer : $_url);
			} else {
				$_headers[] = 'Referer: ' . $_url;
			}
		} else {
			$_headers[] = 'Referer: ' . $_url;
		}
	}
}


// Get content
$_r = array('headers' => array());
$_p = curl_init($_url);
if($_SESSION['_options']['accept_cookies']) {
	$_cookie_file = _CWD . '/cookies/' . session_id() . '.txt';
	if(!file_exists($_cookie_file)) {
		$_cookie = @fopen($_cookie_file, 'wb');
		@fclose($_cookie);
	}
	curl_setopt($_p, CURLOPT_COOKIEJAR, $_cookie_file);
	curl_setopt($_p, CURLOPT_COOKIEFILE, $_cookie_file);
}
curl_setopt($_p, CURLOPT_REFERER, false);
curl_setopt($_p, CURLOPT_FAILONERROR, true);
curl_setopt($_p, CURLOPT_FORBID_REUSE, false);
curl_setopt($_p, CURLOPT_FRESH_CONNECT, false);
curl_setopt($_p, CURLOPT_TIMEOUT, 60);
curl_setopt($_p, CURLOPT_MAXREDIRS, 10);
curl_setopt($_p, CURLOPT_FILETIME, true);
curl_setopt($_p, CURLOPT_RETURNTRANSFER, true);
curl_setopt($_p, CURLOPT_HTTPHEADER, $_headers);
curl_setopt($_p, CURLOPT_HEADERFUNCTION, '_headerfunction');
if(defined('CURLOPT_AUTOREFERER')) curl_setopt($_p, CURLOPT_AUTOREFERER, false);

if($_SESSION['_authorization'][$_url_com['host']]) curl_setopt($_p, CURLOPT_USERPWD, $_SESSION['_authorization'][$_url_com['host']]);
foreach($_curl_options as $_option => $_value) {
	curl_setopt($_p, $_option, $_value);
}

if(defined('_DEVELOPMENT_MODE')) {
	$_curl_log = fopen('./curl_log.txt', 'ab');
	fwrite($_curl_log, "[ " . date('Y-m-d H:i:s') . " ]\r\n");
	flock($_curl_log, LOCK_EX);
	curl_setopt($_p, CURLOPT_VERBOSE, true);
	curl_setopt($_p, CURLOPT_STDERR, $_curl_log);
}

$_r['content'] = curl_exec($_p);
$_r['info'] = curl_getinfo($_p);
$_r['errno'] = curl_errno($_p);
$_r['error'] = curl_error($_p);
curl_close($_p);

if(defined('_DEVELOPMENT_MODE')) {
	fwrite($_curl_log, "=> Error Code: {$_r['errno']}\r\n=> Error Message: {$_r['error']}\r\n\r\n\r\n");
	flock($_curl_log, LOCK_UN);
	fclose($_curl_log);
}

// Delete uploaded files if did
foreach($_uploaded_files as $_uploaded_file) {
	@unlink($_uploaded_file);
}
unset($_p, $_uploaded_files, $_uploaded_file, $_curl_options);

// Handle errors or redirection returned by the distination
if($_r['errno'] == 28 or $_r['errno'] == 6) {
	_report(array('error' => 'server_not_found'));
}
//if($_r['info']['url'] != $_url) {
//	_redirect('?' . $_config['url_var_name'] . '=' . _encode_url($_r['info']['url']));
//}
if($_r['info']['http_code'] == 401) { // Authorization Required
	sscanf($_r['headers']['www-authenticate'][0], 'Basic realm=%s', $_realm);
	$_realm = array();
	preg_match('#basic realm=(?:\'|\")(.*?)(?:\'|\")#si', $_r['headers']['www-authenticate'][0], $_realm);
	$_realm = $_realm ? $_realm[1] : '';
	$_lang['enter_username_password'] = sprintf($_lang['enter_username_password'], trim($_realm, '"\''), $_base['webroot']);
	unset($_realm);
	_report(array('error' => 'authorization_required', 'username' => '', 'password' => ''));
}
if($_r['info']['http_code'] == 404) { // File not found
	_report(array('error' => 'error_404'));
}
if($_config['max_file_size'] > 0 and $_r['info']['download_content_length'] > $_config['max_file_size']) {
	$_lang['file_too_large'] = sprintf($_lang['file_too_large'], _number_format($_config['max_file_size'] / 1048576), _number_format($_r['info']['download_content_length'] / 1048576));
	_report(array('error' => 'file_too_large'));
}

// Try to detect Content-Type
if(!isset($_r['headers']['content-type']) and function_exists('mime_content_type')) {
	$_r['headers']['content-type'] = array(mime_content_type($_url));
}
if(isset($_r['headers']['content-type'])) {
	$_content_type = explode(';', $_r['headers']['content-type'][0]);
	$_content_type = array_map('trim', $_content_type);
} else {
	$_content_type = array('text/html');
}

// Use compress output or not?
if($_config['compress_output'] and (in_array($_content_type[0], $_proxify) and $_content_type[0] != 'text/css') and !(bool)@ini_get('zlib.output_compression') and extension_loaded('zlib')) ob_start('ob_gzhandler');

// Out content don't need to be proxified
if(!in_array($_content_type[0], $_proxify)) {
	_out(false, $_content_type[0]);
}

// Try to find <base> tag out and re-setup $_base
if(preg_match('#(<base[^>]*\bhref\s*=\s*[\(\'\"]+)(.*?)([\'\"\)]+[^>]*>)#si', $_r['content'], $match)) {
	$_base_com = @parse_url($match[2]);
	if($_base_com) {
		$_base_url = $_base_com['scheme'] . '://';
		$_base_com['scheme'] = strtolower($_base_com['scheme']);
		$_base = array('scheme' => $_base_com['scheme']);
		if(isset($_base_com['user']) or isset($_base_com['pass'])) {
			$_base_url .= $_base_com['user'];
			if(isset($_base_com['pass'])) {
				$_base_url .= ':' . $_base_com['pass'];
			}
			$_base_url .= '@';
		}
		$_base_url .= $_base_com['host'];
		if(isset($_base_com['port'])) {
			$_base_url .= ':' . $_base_com['port'];
		}
		$_base['webroot'] = $_base_url;
		if(isset($_base_com['path'])) {
			$_base_url .= $_base_com['path'];
		}
		$_base['path'] = isset($_base_com['path']) ? $_base_com['path'] : '/';
		$_base['webpath'] = $_base_url;
		if(isset($_base_com['query'])) {
			$_base_com['query'] = str_replace('&amp;', '&', $_base_com['query']);
			parse_str($_base_com['query'], $_query_pairs);
			$_base_url .= '?' . http_build_query($_query_pairs);
			unset($_query_pairs);
		}
		$_base['url'] = $_base_url;
		unset($_base_url);
	}
	unset($_base_com);
	
	$_r['content'] = str_replace($match[0], '', $_r['content']);
}

// Remove Images
if($_SESSION['_options']['remove_images']) {
	$_r['content'] = preg_replace('#<img[^>]*>#si', '', $_r['content']);
	$_r['content'] = preg_replace('#(\Wbackground(?:\-image|)?\s*:\s*?.*?)url\s*\(.*?\)(\s)*(;)?#Ssi', '\\1none\\2\\3', $_r['content']);
}

// Out if it's CSS file
if($_content_type[0] == 'text/css') {
	$_r['content'] = _rewrite_css($_r['content']);
	_out();
}

// Handle Options
if($_SESSION['_options']['remove_title']) { // Remove Page Title
	$_r['content'] = preg_replace('#<title\s*>[^<]*</\s*title\s*>#si', '<title></title>', $_r['content']);
}
$_scripts = array();
if($_SESSION['_options']['remove_scripts']) { // Remove scripts
	$_r['content'] = preg_replace('#<script[^>]*>.*?</\s*script\s*>#si', '', $_r['content']);
	$_r['content'] = preg_replace('#\Won[a-z]+\s*=\s*(?:\"[^\"]*\"|\'[^\']*\'|[^\s\>]*)#si', '', $_r['content']);
	$_r['content'] = preg_replace('#<noscript[^>]*>(.*?)</\s*noscript\s*>#si', '\\1', $_r['content']);
} else {
	// Handle scripts #1
	_rewrite_tags(array('script' => array('src')));
	preg_match_all('#<script[^>]*>.*?</\s*script\s*>#si', $_r['content'], $_scripts);
	$_scripts = $_scripts[0];
	foreach($_scripts as $_key => $_script) {
		$_scripts['@-@-@-script-' . $_key . '-@-@-@'] = $_script;
		unset($_scripts[$_key]);
	}
	$_r['content'] = str_replace($_scripts, array_keys($_scripts), $_r['content']);
}
if($_SESSION['_options']['remove_meta']) { // Remove meta tags
	$_r['content'] = preg_replace('#<meta[^>]*\bname\s*=\s*(?:\"[^\"]*\"|\'[^\']*\'|[^\s\>]*)[^>]*>#si', '', $_r['content']);
}

// Rewrite URLs
_rewrite_tags(array(
	'a' => array('href'),
	'img' => array('src', 'longdesc'),
	'image' => array('src', 'longdesc'),
	'body' => array('background'),
	'base' => array('href'),
	'frame' => array('src', 'longdesc'),
	'iframe' => array('src', 'longdesc'),
	'head' => array('profile'),
	'layer' => array('src'),
	'input' => array('src', 'usemap'),
	'form' => array('action'),
	'area' => array('href'),
	'link' => array('href', 'src', 'urn'),
	'meta' => array('content'),
	'param' => array('value'),
	'applet' => array('codebase', 'code', 'object', 'archive'),
	'object' => array('usermap', 'codebase', 'classid', 'archive', 'data'),
	'select' => array('src'),
	'hr' => array('src'),
	'table' => array('background'),
	'tr' => array('background'),
	'th' => array('background'),
	'td' => array('background'),
	'bgsound' => array('src'),
	'blockquote' => array('cite'),
	'del' => array('cite'),
	'embed' => array('src'),
	'fig' => array('src', 'imagemap'),
	'ilayer' => array('src'),
	'ins' => array('cite'),
	'note' => array('src'),
	'overlay' => array('src', 'imagemap'),
	'q' => array('cite'),
	'ul' => array('src')
));

// Handle scripts #2
if(!$_SESSION['_options']['remove_scripts']) {
	$_r['content'] = str_replace(array_keys($_scripts), $_scripts, $_r['content']);
}
unset($_scripts);

// Rewrite Inline CSS
$_r['content'] = preg_replace('#(<[a-z]+\s*[^>]*url\s*\([\'\"]?)(.*?)([\'\"]?\)[^>]*>)#Sesi', 'stripslashes("\\1") . _rewrite_url("\\2") . stripslashes("\\3")', $_r['content']);

// Rewrite CSS
if(preg_match_all('#<style[^>]*>.*?</\s*style\s*>#Ssi', $_r['content'], $_matches)) {
	$_newinx = count($_matches);
	$_matches[$_newinx] = array();
	foreach($_matches[0] as $_key => $_match) {
		$_matches[$_newinx][] = _rewrite_css($_match);
	}
	$_r['content'] = str_replace($_matches[0], $_matches[$_newinx], $_r['content']);
	unset($_matches, $_newinx, $_key, $_match);
}

// Include mini form
if($_SESSION['_options']['include_form'] and (!isset($_GET['_x_fin_x_']) or $_GET['_x_fin_x_'] != '1')) {
	include _THEME_DIR . '/mini_form.php';
	$_r['content'] = preg_replace('#(<body(?:\s*[a-z]+\s*=\s*(?:\"[^\"]*\"|\'[^\']*\'|[^\s\>]*))*.*?>)#si', '\\1' . $_form, $_r['content'], 1);
	//end of if
}

// Out proxified content
_out();



// Functions
function _redirect($_url) {
	header('Location: ' . $_url);
	exit;
}

function _stripslashes($input) {
	return (is_array($input) ? array_map('_stripslashes', $input) : (is_string($input) ? stripslashes($input) : $input));
}

function _strip_single_quotes($input) {
	return (is_array($input) ? array_map('_strip_single_quotes', $input) : (is_string($input) ? str_replace('\'\'', '\'', $input) : $input));
}

function _number_format($_number, $_num_dec_places = 2) {
	global $_config, $_lang;
	
	return number_format($_number, $_num_dec_places, $_lang['decimal_separator'], $_lang['thousands_separator']);
}

function _report($_data = array()) {
	global $_url, $_lang, $_config, $_options, $_frozen_options;
	
	if(!isset($_data['url'])) $_data['url'] = $_url;
	$_data['url'] = htmlentities($_data['url'], ENT_QUOTES, 'UTF-8');
	require _THEME_DIR . '/main_form.php';
	exit;
}

function _headerfunction(&$curl, $header) {
	global $_r;

	if($_value = explode(':', $header, 2) and count($_value) > 1) {
		$_value = array_map('trim', $_value);
		if($_value[0] == '' or $_value[1] == '') {
			unset($_r['headers'][$_key]);
			continue;
		}

		$_value[0] = strtolower($_value[0]);
		switch($_value[0]) {
			case 'location':
				_redirect(_rewrite_url($_value[1]));
			break;

			case 'uri':
			case 'content-location':
				$_value[1] = _rewrite_url($_value[1]);
			break;
			
			case 'p3p':
				if(preg_match('#policyref\s*=\s*(\"[^\"]*\"|\'[^\']*\'|[^\s]*)#si', $_value[1], $_matches)) {
					$_value[1] = str_replace($_matches[1], '"' . _rewrite_url(trim($_matches[1], '"\'')) . '"', $_value[1]);
				}
			break;
			
			case 'refresh':
				if(preg_match('#(\s*\d+\s*;\s*url\s*=\s*)([^\"\'\s]*)#si', $_value[1], $_matches)) {
					$_value[1] = str_replace($_matches[0], $_matches[1] . _rewrite_url($_matches[2]), $_value[1]);
				}
			break;
		}
		
		if(isset($_r['headers'][$_value[0]])) {
			$_r['headers'][$_value[0]][] = $_value[1];
		} else {
			$_r['headers'][$_value[0]] = array($_value[1]);
		}
	}

	return strlen($header);
}

function _out($_proxify = true, $_content_type = '') {
	global $_r, $_url;

	$_nopass_headers = array('cookie', 'set-cookie', 'connection', 'keep-alive', 'transfer-encoding');
	$_r['headers']['content-length'] = array(strlen($_r['content']));
	if(!$_proxify and !isset($_r['headers']['content-disposition'])) $_r['headers']['content-disposition'] = array(($_content_type == 'application/octet_stream' ? 'attachment' : 'inline') . '; filename="' . basename($_url) . '"');
	foreach($_r['headers'] as $_key => $_value) {
		if(in_array($_key, $_nopass_headers)) continue;
		$_key = ucwords($_key);
		foreach($_value as $_k => $_v) {
			header("$_key: $_v");
		}
	}

	session_write_close();
	echo $_r['content'];
	exit;
}

function _rewrite_css($_content) {
	return preg_replace('#((?:import\s*url|import|url)\s*[\(\'\"]+)(.*?)([\'\"\)]+)#Sesi', 'stripslashes("\\1") . _rewrite_url("\\2") . stripslashes("\\3")', $_content);
}

function _rewrite_tags($_tags) {
	global $_url, $_r, $_config;

	if(preg_match_all('#<(' . implode('|', array_keys($_tags)) . ')((?:\s*[a-z\-]+\s*=\s*(?:\"[^\"]*\"|\'[^\']*\'|[^\s\>]*)|\s*[a-z\-]+)+)+.*?>#si', $_r['content'], $_matches)) {
		$_newinx = count($_matches);
		$_matches[$_newinx] = array();
		foreach($_matches[0] as $_key => $_match) {
			$_tag = strtolower($_matches[1][$_key]);
			if(!isset($_tags[$_tag])) {
				unset($_matches[0][$_key], $_matches[1][$_key], $_matches[2][$_key]);
				continue;
			}
			$get = false;
			if($_tag == 'form') {
				$get = !preg_match('#method\s*=\s*(?:\"post\"|\'post\'|post)#si', $_matches[2][$_key]);
			}
			$_action = '';
			$_pairs = array();
			if(preg_match_all('#\s*(' . implode('|', $_tags[$_tag]) . ')\s*=\s*(\"[^\"]*\"|\'[^\']*\'|[^\s\>]*)#si', $_matches[2][$_key], $_ms)) {
				foreach($_ms[2] as $_k => $_m) {
					$_wrapper = '';
					if($_m{0} == '"' and $_m{strlen($_m) - 1} == '"') {
						$_wrapper = '"';
						$_m = trim($_m, '"');
					} else if($_m{0} == '\'' and $_m{strlen($_m) - 1} == '\'') {
						$_wrapper = '\'';
						$_m = trim($_m, '\'');
					}
					
					$_attribute = strtolower($_ms[1][$_k]);
					//Process <meta http-equiv="refresh" content="00;URL=http://somewhere.com">
					if($_tag == 'meta') {
						if($_attribute == 'content') {
							if($_refresh = preg_match('#(\s*\d+\s*;\s*url\s*=\s*)([^\"\'\s]*)#si', $_m, $_mh)) {
								$_m = $_mh[1] . _rewrite_url($_mh[2]);
							}
						}
					} elseif($_tag == 'form' and $get and $_attribute == 'action') {
						$_action = _rewrite_url($_m, false);
						$_m = '';
					} elseif(($_tag == 'frame' or $_tag == 'iframe') and $_attribute == 'src') {
						if($_m == '') {
							$_m = _rewrite_url($_url) . '&_x_fin_x_=1';
						} else {
							$_m = _rewrite_url($_m) . '&_x_fin_x_=1';
						}
					} else {
						$_m = _rewrite_url($_m);
					}
				}
				$_pairs[] = ' ' . $_ms[1][$_k] . '=' . $_wrapper . $_m . $_wrapper;
			}
			$_matches[$_newinx][$_key] = str_replace($_ms[0], $_pairs, $_matches[0][$_key]) . ($get ? '<input type="hidden" name="' . $_config['url_var_name'] . '" value="' . $_action . '" />' : '');
		}
		$_r['content'] = str_replace($_matches[0], $_matches[$_newinx], $_r['content']);
	}
	//end of if
}

function _rewrite_url($_url, $_proxify = true) {
	global $_base, $_url_com, $_config, $_protocols;

	$_url = trim($_url);
	if($_url == '') return '';
	switch(strtolower($_url{0})) {
		case '#':
			return $_url;
		break;

		case '?':
			$_url = $_base['webpath'] . $_url;
		break;
		
		case '/':
			if(substr($_url, 1, 1) == '/') {
				$_url = $_base['scheme'] . '://' . substr($_url, 2);
				break;
			}
			$_url = $_base['webroot'] . $_url;
		break;

		default:
			// Try to detect an another protocol
			$_inx = strpos($_url, '://');
			if($_inx !== false and $_inx > 0) {
				$_prefix = substr($_url, 0, $_inx);
				$_is_protocol = (preg_replace('#\W#i', '', $_prefix) == $_prefix);
				if($_is_protocol) {
					// If it's a supported protocol, break and proxify it, if not leave it intact.
					if(in_array($_prefix, $_protocols)) {
						break;
					} else {
						return $_url;
					}
				}
			}
			$_url = $_base['webroot'] . _realpath($_base['path'], $_url);
	}

	$_url = ($_proxify ? ($_SERVER['SCRIPT_NAME'] . '?' . $_config['url_var_name'] . '=') : '') . _encode_url($_url, $_proxify) . ((isset($_url_com['fragment']) and $_proxify) ? ('#' . $_url_com['fragment']) : '');

	return $_url;
}


function _realpath($_cwd, $_cd) {
	$_cwd = trim($_cwd, '/');
	$_cwd_dirs = ($_cd and $_cd{0} == '/') ? array() : (($_cwd and $_cwd != '/') ? explode('/', $_cwd) : array());
	$_cd_dirs = ($_cd ? explode('/', $_cd) : array());
	$_last_dir = array_pop($_cwd_dirs);
	if($_last_dir and strpos($_last_dir, '.') === false) {
		$_cwd_dirs[] = $_last_dir;
	}
	$_cd_len = count($_cd_dirs);
	$_i = 0;
	foreach($_cd_dirs as $_cd_dir) {
		$_i++;
		if($_cd_dir == '.' or $_cd_dir == '') {
			continue;
		} else if($_cd_dir == '..') {
			array_pop($_cwd_dirs);
		} else {
			$_cwd_dirs[] = (strpos($_cd_dir, '.') !== false and $_i == $_cd_len) ? $_cd_dir : rawurlencode(rawurldecode($_cd_dir));
		}
	}

	return '/' . implode('/', $_cwd_dirs);
}


function _encode_url($_url, $_url_encode = true) {
	return ($_url_encode ? rawurlencode(base64_encode($_url)) : base64_encode($_url));
}


function _decode_url($_encoded_url) {
	return base64_decode($_encoded_url);
}

?>