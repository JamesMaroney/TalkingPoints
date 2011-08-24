<?php

$status = new stdClass();
$status->acceptingSubmissions = true;

if(strtotime($config->timing->submission_cutoff) < time()){
  $status->acceptingSubmissions = false;
}

echo json_encode($status);