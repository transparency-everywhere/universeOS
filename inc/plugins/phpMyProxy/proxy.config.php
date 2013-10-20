<?php

/*
 phpMyProxy is a free php proxy script programmed by eProxies.info Team.
 Website: http://www.phpmyproxy.com
 Support: http://www.eproxies.info/support/
 Proxy Directory: http://www.eproxies.info/
*/

/*
# For proxy configuration, included document for configuration.
*/

// Default URL
$_url = 'http://';

// Language file name without .php in lang folder.
$_lang = 'english';

// Theme directory name in themes folder.
$_theme = 'decorative';

// Proxy Configurations
$_config = array(
	'url_var_name' => 'q', // Dynamic web address field name in URL-Form/Mini-Form. This doesn't accept empty string '' and only accept alphabet and numeric characters.
	'max_file_size' => 0, // Limit filesize users can download/browse. For unlimited filesize, use 0 (zero). This only accept positive integer value.
	'allow_hotlinking' => 0, // Allow or disallow hotlinking. To disallow, use 0 (zero), or vice versa, use 1.
	'nonreferer_hotlink' => 1, // Allow or disallow non-referer visits when hotlink disallowed.
	'upon_hotlink' => 1, // Set bahaviour for hotlink prevention, use 1 to display a message, use 2 to send 404 File Not Found header, use a string as URL to redirect to that URL.
	'compress_output' => 1, // Allow or disallow useing gzip compression to compress web pages. This will help you have bandwidth. Use 0 to disallow, use 1 to allow.
);

// Default Proxy Options
$_options = array(
	'include_form' => 1, // Include Mini-Form/URL-Form in proxified pages. The default value is 1.
	'remove_images' => 0, // Remove images from proxified pages. The default value is 0.
	'remove_title' => 1, // Remove page title from proxified pages. The default value is 1.
	'remove_scripts' => 1, // Remove client-script (javscript, vbscript, etc) from proxified pages. The default value is 1.
	'remove_meta' => 1, // Remove meta tags (keywords, description, refresh, etc) from proxified pages. The default value is 1.
	'remove_referers' => 0, // Remove website referer. The default value is 0.
	'accept_cookies' => 1, // Accept cookies sent to browser or not? 1 is recommended to use all functions of a website. The default value is 1.
);

// Proxy Options to hide from users and force Default Proxy Options above
$_frozen_options = array(
	'include_form' => 0, // Allow/Disallow user select 'Include Mini-Form/URL-Form' in proxifed pages and use the default value setup above.
	'remove_images' => 0, // Allow/Disallow user select 'Remove Images' in proxified pages and use the default value setup above.
	'remove_title' => 0, // Allow/Disallow user select 'Remove Title' in proxified pages and use the default value setup above.
	'remove_scripts' => 0, // Allow/Disallow user select 'Remove Scripts' in proxified pages and use the default value setup above.
	'remove_meta' => 0, // Allow/Disallow user select 'Remove Meta Tags' in proxified pages and use the default value setup above.
	'remove_referers' => 0, // Allow/Disallow user select 'Remove Referer' in proxified pages and use the default value setup above.
	'accept_cookies' => 0, // Allow/Disallow user select 'Accept Cookies' in proxified pages and use the default value setup above.
);

// Content-Type to be proxify. Do not change if you don't know what you are doing.
$_proxify = array(
	'text/html',
	'text/xml',
	'application/xml+xhtml',
	'application/xhtml+xml',
	'application/rss+xml',
	'application/atom+xml',
	'application/feed+xml',
	'text/css',
);

// Disallowed Hosts. Do not include 'wwww.' before hosts.
$_disallowed_hosts = array(
	'localhost',
);

// HotLink Domains. Do not include 'www.' before domains.
$_hotlink_domains = array(
	
);

//define('_DEVELOPMENT_MODE', true); // Change permission of curl_log.txt to 0777 if enable this mode

?>