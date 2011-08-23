<?php

$status = new stdClass();
$status->acceptingSubmissions = true;

if(strtotime($config['cutoff_submissions_at']) < time()){
  $status->acceptingSubmissions = false;
}

echo json_encode($status);