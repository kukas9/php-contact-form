<?php
// global options
$config = array(
	'domains' => array(), // leave empty to ignore, fill if you want to enable contact only for these domains
	'redirects' => array(
		//'old-domain.com' => 'new-domain.com'
		// regular expression example
		// "/^domain\.([a-z]+)$/i" => 'other-domain.com'
	),

	'www_prefix' => true, // use www. prefix? (supporting auto-redirects)
	'template' => 'simple', // the only one template that actually works

	// reCAPTCHA (prevent spam bots)
	'recaptcha_enabled' => false, // enable recaptcha verification code
	'recaptcha_site_key' => '', // get your own public and private keys at http://recaptcha.net
	'recaptcha_secret_key' => '',
	'recaptcha_theme' => 'light', // light, dark

	// mail
	'mail_address' => 'admin@example.org',	// address where messages from form will be send
	'smtp_enabled' => false,				// set false to use php mail function (you can leave smpt_ options untouched)
	'smtp_host' => '', // mail host
	'smtp_port' => 25,					// 25 (default) / 465 (ssl, e.g. gmail)
	'smtp_auth' => true,				// need authorization?
	'smtp_user' => 'admin@example.org',
	'smtp_pass' => '',

	// other
	'gzip_output' => true, // gzip page content before sending it to the browser, uses less bandwidth usage but more cpu cycles
	'clear_form_content' => true, // when message has been sent successfully, clear user fields or keep it filled?
	'google_analytics_id' => '' // e.g.: UA-XXXXXXX-X
);
// specific site options
$config['example.org']['gzip_output'] = false;
$config['www.example.org']['gzip_output'] = false;
?>
