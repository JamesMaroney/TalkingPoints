<?php
define("WWW", dirname(__FILE__));
$WWW = WWW;

define("SITE", dirname($_SERVER['SCRIPT_NAME']));
$SITE = SITE;

require("../app/controller.php");