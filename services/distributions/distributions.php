<?php
/* ====================
   Path Bootstrapping
   -------------------- */
define("SVC", dirname(__FILE__));
$SVC = SVC;

define("SUITE", dirname(dirname($SVC)));
$SUITE = SUITE;

set_include_path( implode(PATH_SEPARATOR, array("$SVC/domain", "$SVC/lib", "$SUITE/shared", "$SUITE/shared/lib")) );
spl_autoload_register();

require_once('underscore.php');
require_once('couch.php');

echo "Starting Distribution\n";

/* =======================
   Configuration Bootstrapping
   ----------------------- */
$config = new ConfigManager(SUITE, SVC);

/* =======================
   Get the currently configured Subscriptions
   ----------------------- */
$couch_subscriptions = new couchClient($config->databases->subscriptions->connectionString,
                                       $config->databases->subscriptions->name);
$subscription_results = $couch_subscriptions->getView('views','subscriptions');
if( $subscription_results->total_rows == 0 || empty($subscription_results->rows)){
    echo "No Subscriptions Found. Exiting\n";
    exit(0);
}
$subscriptions = __::pluck($subscription_results->rows, "value");


/* =======================
   Get the currently defined Talking Points
   ----------------------- */
$couch_talkingPoints = new couchClient($config->databases->talkingpoints->connectionString,
                                       $config->databases->talkingpoints->name);
$points_results = $couch_talkingPoints->key(date("m-d-Y"))->getView('views','pointsByDate');
if( $points_results->total_rows == 0 || empty($points_results->rows)){
    echo "No Talking Points Found. Exiting\n";
    exit(0);
}
$points = __::reduce(
            $points_results->rows,
            function($memo, $row){
                $memo[$row->value->handle] = $row->value->points;
                return $memo;
            },array() );

/* =======================
   Configure SubscriptionFulfilment
   ----------------------- */
$fulfillment = new SubscriptionFulfillment(new EmailSubscriptionFulfillment());

/* ========================
   Main Distribution processing
   ------------------------ */

__::each($subscriptions, function($subscription) use($points, $fulfillment){
    if(!$subscription) continue;

    $pointsForSubscriber = __::reduce(
        $subscription->handles,
        function($memo, $handle) use ($points){
            if(empty($points[$handle])) return $memo;
            return array_merge($memo, $points[$handle]);
        }, array());

    $fulfillment->handle($subscription, $pointsForSubscriber);
});