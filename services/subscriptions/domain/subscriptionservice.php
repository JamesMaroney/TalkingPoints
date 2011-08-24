<?php

require_once("couch.php");

class SubscriptionService {

  public static function Contains($subscription){
    echo "checking for existing subscription\n";
    return false;
  }

  public static function Add($subscription){
    global $config;
    echo "adding subscription to system\n";
    var_dump($subscription);
    $couch = new couchClient($config->database->connectionString, $config->database->name);
    $couch->storeDoc($subscription);
    return $subscription;
  }
}