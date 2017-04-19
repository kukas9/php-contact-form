<?php
session_start();
define('START_TIME', microtime(true));

// paths
define('BASE', dirname(__FILE__) . '/');
define('INCLUDES', BASE . 'includes/');
define('LOCALE', INCLUDES . 'locale/');
define('TEMPLATES', BASE . 'templates/');
?>
