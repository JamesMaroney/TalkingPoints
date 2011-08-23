<?php
$task = empty($_REQUEST["task"]) ? "index" : $_REQUEST["task"];
$requestType = empty($_REQUEST['type']) ? ($_SERVER["REQUEST_METHOD"] == "POST" ? "commands" : "queries") : $_REQUEST['type'];

require("php/config.php");
require("php/".$requestType . "/" . $task . ".php");