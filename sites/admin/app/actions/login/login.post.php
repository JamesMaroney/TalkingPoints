<?php
$username = preg_replace('/[^a-zA-Z0-9._+\-]/i',"",$_REQUEST['username']);
$password = md5($_REQUEST['password']);

function login_failed(){
    global $SITE;
    $_SESSION['login_flash'] = "Login failed";
    header("Location: $SITE"); exit(0);
}

require_once("couch.php");

$couch = new couchClient($config->database->connectionString, $config->database->name);
$results = $couch->key($username)->getView("views","adminByUsername");

if(empty($results->rows)){
  login_failed();
}

$user = $results->rows[0]->value;

if($user->password != $password){
  login_failed();
}

$_SESSION['user'] = $user;
header("Location: ?action=points"); exit(0);
