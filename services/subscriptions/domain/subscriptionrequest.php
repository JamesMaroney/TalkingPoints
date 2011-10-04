<?php

require_once("underscore.php");

class SubscriptionRequest {

  public $handle, $target, $distribution_type, $subscription_request_received, $type;

  public static function fromEmail($email){
    $body = str_replace(array("o","l","i"), array("0","1","1"), $email["body"]);
    preg_match_all("/([a-z0-9]{5}[0-9])/i", $body, $handles);
    if(count($handles) < 2 || empty($handles[1])) {
      echo "Invalid email body. No proper handle specified: ";
      var_dump($email);
      return false;
    }
    $handles = __::map($handles[1], function($h){ return strtolower($h); });


    preg_match("/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i", $email["overview"][0]->from, $targets);
    if(empty($targets)) {
      echo "Unable to extract target email address: ";
      var_dump($email);
      return false;
    }
    $target = $targets[0];

    $request = new SubscriptionRequest();
    $request->handles = $handles;
    $request->target = $target;
    $request->distribution_type = "email";
    $request->subscription_request_received = $email["date_pulled"];
    $request->generateId();
    
    return $request;
  }

  public function generateId(){
    $this->_id = $this->distribution_type . "_" . $this->target;
  }
}