<?php

class IntakeService implements IHandleIntake {

  public function __construct(){
    $this->channels = func_get_args();
  }

  public function getNewSubscriptionRequests(){
    echo "Checking for new subscription requests\n";
    $requests = array();
    foreach($this->channels as $channel) {
      $requests = array_merge($requests, $channel->getNewSubscriptionRequests());
    }
    return $requests;
  }
}