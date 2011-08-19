<?php
$task = empty($_REQUEST["task"]) ? "index" : $_REQUEST["task"];
$requestType = $_SERVER["REQUEST_METHOD"] == "POST" ? "commands" : "queries";

require($requestType . "/" . $task . ".php");