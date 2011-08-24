<?php

class SubscriptionRequest {

  public $handle, $target, $distribution_type, $subscription_request_received;

  public static function fromEmail($email){
    preg_match("/([a-z0-9]{6})/", $email["body"], $handles);
    if(empty($handles)) {
      echo "Invalid email body. No proper handle specified: {$email['body']}";
      return false;
    }
    
    $handle = $handles[0];

    $request = new SubscriptionRequest();
    $request->handle = $handle;
    $request->target = $email["overview"][0]->from;
    $request->distribution_type = "email";
    $request->subscription_request_received = $email["date_pulled"];
    return $request;
  }
}