<?php
require('common.php');
require(BASE . 'config.php');

// enable gzip compression if supported by the browser
if($config['gzip_output'] && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) && function_exists('ob_gzhandler'))
	ob_start('ob_gzhandler');

// redirect if user wants to use www. prefix
$host = $_SERVER['SERVER_NAME'];
$prefix = substr($host, 0, 3);
if($config['www_prefix']) {
	if($prefix != 'www') {
		$redirect = 'www.' . $host;
	}
	else
		$host = substr($host, 4, strlen($host));
}
else if($prefix == 'www') {
	$redirect = substr($host, 4, strlen($host));
}

if(isset($redirect)) {
	header('Location: http://' . $redirect);
	return;
}

if(!empty($config['redirects'])) {
	foreach($config['redirects'] as $k => $v) {
		if(preg_match('/' . $k . '/', $host, $matches)) {
			header('Location: http://' . $v);
			return;
		}
	}
}

// domain verification
if($host == $_SERVER['SERVER_ADDR'] ||
	(count($config['domains']) > 0 && !in_array($host, $config['domains'])))
		die('Invalid domain name.'); // ignore domains that not belong to us (everyone can redirect their domain names to our server IP, we must block it)

if(isset($config[$host]))
	$config = array_merge($config, $config[$host]);
else if(isset($config['www.' . $host]))
	$config = array_merge($config, $config['www.' . $host]);

require(INCLUDES . 'functions.php');
// language
if(!isset($_GET['lang']))
{
	$language = $_SESSION['lang'];
	if(!isset($language))
	{
		// detect user language
		$languages = get_browser_languages();
		if(!sizeof($languages))
			$language = 'en';
		else
		{
			foreach($languages as $id => $tmp)
			{
				$tmp_file = 'includes/locale/' . $tmp;
				if(@file_exists($tmp_file))
				{
					$language = $tmp;
					break;
				}
			}
		}

		$_SESSION['lang'] = $language;
	}
}
else
{
	$language = $_GET['lang'];
	$lang_size = strlen($language);
	if(!$lang_size || $lang_size > 4 || !preg_match("/[a-z]/", $language)) // validate language
		$language = 'en';
	else // hidden redirect
	{
		$_SESSION['lang'] = $language;
		header('Location: /');
	}
}

$file = LOCALE . $language . '/index.php';
if(!file_exists($file))
	$file = LOCALE . 'en/index.php';
require($file);

$status = '';
// form has been submitted
if(isset($_POST['submit']))
{
	// get posted data into local variables
	$email_from = trim(stripslashes($_POST['email_from']));
	$email_to = $config['mail_address'];
	$subject = $host . ' - contact';
	$message = htmlspecialchars(stripslashes(trim($_POST['message'])));

	$error = false;
	if(!strlen($email_from) || !strlen($message))
	{
		echo error_message($lang['error_no_input']);
		$error = true;
	}

	// check message length - max size = 2000
	if(!$error && strlen($message) > 2000)
	{
		echo error_message($lang['error_message_long']);
		$error = true;
	}

	// validate email
	if(!$error && !mail_valid($email_from))
	{
		echo error_message($lang['error_email']);
		$error = true;
	}

	if($config['recaptcha_enabled'])
	{
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
		{
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$config['recaptcha_secret_key'].'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if(!$responseData->success)
			{
				echo error_message($lang['error_recaptcha']);
				$error = true;
			}
		}
		else
		{
			echo error_message($lang['error_recaptcha']);
			$error = true;
		}
	}

	if(!$error)
	{
		// prepare email body text
		$body = "";
		$body .= $lang['message_sent_from'] . "<b>http://$host</b>";
		$body .= "<br/><br/>";
		$body .= "--------------------<br/>" . nl2br($message);
		$body .= "<br/>--------------------<br/><br/>";
		$body .= "IP: " . $_SERVER['REMOTE_ADDR'];
		$body .= "\n<br/>";

		//$body = preg_replace("/[\]/i", '', $body);
		if(_mail($email_from, $subject, $body))
		{
			$status = success_message($lang['mail_success']);
			if($config['clear_form_content'])
				$_POST['message'] = $_POST['email_from'] = '';
		}
		else
			$status = error_message(sprintf($lang['mail_error'], $mailer->ErrorInfo));
	}
}

// template
$template_path = 'templates/' . $config['template'] . '/';
if(!file_exists($template_path . 'template.php'))
	$template_path = 'templates/simple/';
require(BASE . $template_path . 'template.php');

if(function_exists('xdebug_time_index'))
	echo '<!-- Generated in: ' . xdebug_time_index() . ' -->';
?>
