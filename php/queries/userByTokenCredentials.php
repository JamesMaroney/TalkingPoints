<?php
$username = preg_replace('/[^a-zA-Z0-9._+\-]/i',"",$_REQUEST['username']);
$token = $_REQUEST['token'];

$checkToken = md5("k232jaslkadf90".$username);

if($checkToken != $token){
  echo "{}"; exit();
}

require("php/couch.php");

$couch = new couchClient($config['db_host'], $config['db_database']);
$results = $couch->key($username)->getView("views","teacherByUsername");

if(empty($results->rows)){
  echo "{}"; exit();
}

$user = $results->rows[0]->value;

unset($user->password);
$user->token = md5("k232jaslkadf90".$user->username);
echo json_encode($user);