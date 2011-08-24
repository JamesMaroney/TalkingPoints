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


/* =======================
   Configuration Bootstrapping
   ----------------------- */
$config = new ConfigManager(SUITE, SVC);


/* ==========================
   Intake Channels Configuration
   -------------------------- */

$intake = new IntakeService(
              new ImapIntakeService(
                $config->intake_handlers->Imap->username,
                $config->intake_handlers->Imap->password,
                $config->intake_handlers->Imap->mailbox )
          );


/* ======================
   Main Run Loop
   ---------------------- */
while( true ){

  foreach( $intake->getNewSubscriptionRequests() as $request){
    if(!$request) continue;
    echo "processing request: "; var_dump($request);

    if(!SubscriptionService::contains($request)){
      SubscriptionService::add($request);
    }
  }

  sleep(300);
}