<?php

require_once("couch.php");

class SubscriptionService {

  public static function Add($subscription){
    global $config;
    echo "adding subscription to system\n";
    var_dump($subscription);
    $couch = new couchClient($config->database->connectionString, $config->database->name);
    $response = $couch->updateDoc("updates", "subscription_request", $subscription, $subscription->_id);
    //todo: check response
    return $subscription;
  }
}