<?php

require_once("underscore.php");

class ImapIntakeService implements IHandleIntake {

  protected $user, $pass, $mailbox;

  public function __construct($user, $pass, $mailbox){
    $this->user = $user;
    $this->pass = $pass;
    $this->mailbox = $mailbox;
  }

  public function getNewSubscriptionRequests(){
    echo "Checking for new Email subscription requests\n";
    return __::map(
              $this->getEmails(),
              function($e) { return SubscriptionRequest::fromEmail($e); });
  }


  protected function getEmails(){
    $mbox = imap_open("{imap.gmail.com:993/imap/ssl}{$this->mailbox}", $this->user, $this->pass)
                or die('Cannot connect to Gmail: ' . imap_last_error());

    $emailIds = imap_search($mbox,'UNSEEN');
    if(empty($emailIds)) return array();

    $emails = __::map($emailIds, function($id) use($mbox) {
                  return array(
                    "overview" => imap_fetch_overview($mbox, $id, 0),
                    "body" => strip_tags(imap_fetchbody($mbox, $id, 2)),
                    "date_pulled" => date("m-d-Y")
                  );
                });
                
    imap_close($mbox);
    return $emails;
  }
}