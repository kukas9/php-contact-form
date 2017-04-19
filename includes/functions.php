<?php
function mail_valid($email) {
	return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
}

function get_browser_languages()
{
	$ret = array();
	if(!isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"]))
		return $ret;

	$languages = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	// $languages = 'pl,en-us;q=0.7,en;q=0.3 ';
	// need to remove spaces from strings to avoid error
	$languages = str_replace( ' ', '', $languages );
	$languages = explode( ",", $languages );
	//$languages = explode( ",", $test);// this is for testing purposes only

	foreach ( $languages as $language_list )
	{
		// pull out the language, place languages into array of full and primary
		// string structure:
		//$temp_array = array();
		// slice out the part before ; on first step, the part before - on second, place into array
		//$temp_array[0] = substr( $language_list, 0, strcspn( $language_list, ';' ) );//full language
		//$temp_array[1] = substr( $language_list, 0, 2 );// cut out primary language
		//place this array into main $user_languages language array
		//$user_languages[] = $temp_array;

		//echo $temp_array[0] . ', ' . $temp_array[1] . '<br/>';
		$ret[] .= substr( $language_list, 0, 2 );
	}

	return $ret;
}

function error_message($desc)
{
	return '<p style="font-family: verdana, tahoma, sans-serif;
	font-size: 11px;
	font-weight: bold;
	line-height: 16px;
	color: #ff0000;">' . $desc . '</p>';
}

function success_message($desc)
{
	return '<p style="font-family: verdana, tahoma, sans-serif;
	font-size: 11px;
	font-weight: bold;
	line-height: 16px;
	color: green;">' . $desc . '</p>';
}

function template_header()
{
	global $host, $lang, $language, $config;
	$ret = '<meta http-equiv="content-type" content="text/html; charset=' . $lang['encoding'] . '" />
	<meta http-equiv="content-language" content="' . $language . '" />
	<title>' . $host . '</title>';
	
	if($config['recaptcha_enabled'])
		$ret .= "<script src='https://www.google.com/recaptcha/api.js'></script>";
	
	return $ret;
}

function template_ga_code()
{
	global $config;
	if(!isset($config['google_analytics_id'][0]))
		return '';

	return '
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push([\'_setAccount\', \'' . $config['google_analytics_id'] . '\']);
	_gaq.push([\'_setDomainName\', \'none\']);
	_gaq.push([\'_setAllowLinker\', true]);
	_gaq.push([\'_trackPageview\']);

	(function() {
	var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
	ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
	var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>';
}

function _mail($from, $subject, $body, $html = true)
{
	global $mailer, $config;
	if(!$mailer)
	{
		require(INCLUDES . 'phpmailer/class.phpmailer.php');
		$mailer = new PHPMailer();
	}

	if($config['smtp_enabled'])
	{
		$mailer->IsSMTP();
		$mailer->Host = $config['smtp_host'];
		$mailer->Port = (int)$config['smtp_port'];
		$mailer->SMTPAuth = $config['smtp_auth'];
		$mailer->Username = $config['smtp_user'];
		$mailer->Password = $config['smtp_pass'];
	}
	else
		$mailer->IsMail();

	$mailer->IsHTML($html);
	$mailer->From = $from;
	$mailer->Subject = $subject;
	$mailer->AddAddress($config['mail_address']);
	$mailer->Body = $body;

	return $mailer->Send();
}
?>
