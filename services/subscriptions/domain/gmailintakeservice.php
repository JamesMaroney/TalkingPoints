<?php

require_once("underscore.php");

class GmailIntakeService implements IHandleIntake {

  public function __construct($user, $pass, $label){
    $this->user = $user;
    $this->pass = $pass;
    $this->label = $label;
  }

  public function getNewSubscriptionRequests(){
    echo "Getting new Email subscription requests\n";
    $emails = $this->getEmails();
    if(empty($emails)) echo "no subscription emails found\n";
    var_dump($emails);
    return array();
  }

  protected function getEmails(){
    $mbox = imap_open("{imap.gmail.com:993/imap/ssl}{$this->label}", $this->user, $this->pass)
                or die('Cannot connect to Gmail: ' . imap_last_error());

    $emailIds = imap_search($mbox,'UNSEEN');
    if(empty($emailIds)) return array();

    $emails = __::map($emailIds, function($id) use($mbox) { echo "Getting details for email: $id\n"; return array( "overview" => imap_fetch_overview($mbox, $id, 0), "body" => imap_fetchbody($mbox, $id, 2)); });
    imap_close($mbox);
    return $emails;
  }
}