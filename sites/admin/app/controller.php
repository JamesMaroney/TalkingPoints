<?php
session_start();
/* ====================
   Path Bootstrapping
   -------------------- */
define("APP", dirname(__FILE__));
$APP = APP;

define("SUITE", dirname(dirname(dirname(APP))));
$SUITE = SUITE;

set_include_path( implode(PATH_SEPARATOR, array(APP,"$APP/lib","$SUITE/shared","$SUITE/shared/lib")) );
spl_autoload_register();

/* =======================
   Shared infrastructure
   ----------------------- */
require_once("underscore.php");

/* =======================
   Configuration Bootstrapping
   ----------------------- */
$config = new ConfigManager(SUITE, APP);

/* ==========================
   Register valid input parameters
   -------------------------- */
$valid_actions = array('login', 'points', 'logout');
$valid_request_types = array('get', 'post');

/* =================================
   Front controller implementation
   --------------------------------- */
$action=empty($_GET['action'])? 'login' : $_GET['action'];
$requestType = strtolower(empty($_REQUEST['type']) ? ($_SERVER["REQUEST_METHOD"] == "POST" ? "post" : "get") : $_REQUEST['type']);

if( !(in_array($action, $valid_actions) && in_array($requestType, $valid_request_types) ) ){
    throw new Exception('Invalid Request');
}

function render_header($showNav = true){
    global $config, $action;
    require(dirname(__FILE__).'/header.php');
}
function render_footer(){
    global $config;
    require(dirname(__FILE__).'/footer.php');
}

if(empty($_SESSION['user']) && $action != 'login'){
    header("Location: $SITE"); exit(0);
}

require("actions/$action/$action.$requestType.php");