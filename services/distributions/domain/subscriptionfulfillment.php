<?php
require_once('underscore.php');

class SubscriptionFulfillment {

    public function __construct(){
        $this->handlers = __::reduce(
            func_get_args(),
            function($memo, $fulfillmentHandler){
                $memo[$fulfillmentHandler->handlesType()] = $fulfillmentHandler;
                return $memo;
            },array());
    }

    public function handle($subscription, $points){
        if( empty($this->handlers[$subscription->distribution_type]) ){
            echo "Fulfillment handler for subscription type ${$this->handlers[$subscription->distribution_type]} was not found.\n";
            exit(1);
        }
        $this->handlers[$subscription->distribution_type]->handle($subscription, $points);
    }
}