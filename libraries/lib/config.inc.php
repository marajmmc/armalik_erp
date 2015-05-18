<?php

// SITE MODES
//------------------------------------------------------------------------------

define("_SITE_MODE", "debug"); // debug, production 
// SITE CONSTANTS
//------------------------------------------------------------------------------

define("_PANEL_NAME", "Control Panel");

define("_SITE_NAME", "Your Site Name");

define("_SITE_ADDRESS", "domain.com");

define("_SITE_LANGUAGE", "en");

define("_ADMIN_EMAIL", "admin@domain.com");

define("_CSS_STYLE", "blue"); // blue, green

define("_DB_PREFIX", "ait_");

define("_PHP_AP_VERSION", "1.0.5");

define("_SUPPORT_EMAIL", "support <support@domain.com>");

define("_CUSTOMER_EMAIL", "support <support@domain.com>");

define("_SITE_DIRECTORY", ""); // relatively to root (public html directory)

define("_SITE_UP_DIRECTORY", ""); // relatively to root (public html directory)
//------------------------------------------------------------------------------

if (_SITE_MODE == "debug") {

    ini_set('error_reporting', E_ALL);

    ini_set('display_errors', '1');

    ini_set('display_startup_errors', '1');

    error_reporting(E_ALL);
}

//------------------------------------------------------------------------------

class Config {

    var $host = '';
    var $user = '';
    var $password = '';
    var $database = '';

    function Config() {
$this->host = "127.0.0.1";
        $this->user = 'root';
        $this->password = 'vertrigo';
        $this->database = 'armalikg_armalik_db_live';
//        $this->database = 'test';
    }

}

?>