<?
define("SVC", dirname(__FILE__));
$SVC = SVC;

define("SUITE", dirname(dirname($SVC)));
$SUITE = SUITE;

set_include_path( implode(PATH_SEPARATOR, array("$SVC/domain", "$SVC/lib", "$SUITE/shared", "$SUITE/shared/lib")) );
spl_autoload_register();


/* =======================
   Configuration Bootstrapping
   ----------------------- */
$config = new ConfigManager(SUITE, SVC);



$intake = new IntakeService(
              new GmailIntakeService(
                $config->intake_handlers->Gmail->username,
                $config->intake_handlers->Gmail->password,
                $config->intake_handlers->Gmail->label )
          );

while( true ){

  foreach( $intake->getNewSubscriptionRequests() as $request){
    $subscription = $request->generateSubscription();

    if(!SubscriptionService::contains($subscription)){
      SubscriptionService::add($subscription);
    }
  }


  sleep(5);
}