<?php
require_once('underscore.php');

class EmailSubscriptionFulfillment implements IFulfillSubscriptions {

    public function __construct(){

    }

    public function handlesType() { return "email"; }
    
    public function handle($subscription, $points){
        echo "Handling Subscription with the Email Fullfillment Handler.\n";
        mail("james.maroney@gmail.com","Today's Talking Points", $this->buildEmailBody($points));
    }

    private function buildEmailBody($points){
        return implode("\n", array_merge(array("Your Talking Points for the day are:", ""), $points, array("","Happy Talking!")));
    }
}