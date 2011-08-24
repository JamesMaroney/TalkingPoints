<?

class SubscriptionService {

  public static function Contains($subscription){
    echo "checking for existing subscription\n";
    return false;
  }

  public static function Add($subscription){
    echo "adding subscription to system\n";
    return $subscription;
  }
}