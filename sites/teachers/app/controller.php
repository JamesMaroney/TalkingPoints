<?php
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


/* ===============================
   Front-controller implementation
   -------------------------------- */
$task = empty($_REQUEST["task"]) ? "index" : $_REQUEST["task"];
$requestType = empty($_REQUEST['type']) ? ($_SERVER["REQUEST_METHOD"] == "POST" ? "commands" : "queries") : $_REQUEST['type'];



require("$APP/$requestType/$task.php");