<?php
$now = time();
$moderationOpenTime = strtotime($config->timing->submission_cutoff);
$moderationCloseTime = strtotime($config->timing->distribution);
$moderationStatus = $moderationOpenTime > $now ? "pending" : ( $moderationCloseTime > $now ? "open" : "closed" );

require( "moderation_$moderationStatus.php");
?>

<ul id="talkingPointsListing">
    <?php foreach( $points as $pointsDoc ){ include('point.php'); } ?>
</u>