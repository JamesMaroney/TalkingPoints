<p class="status status-pending">
    <strong>Submissions for today's Talking Points are not yet open for moderation.</strong><br />
    Teachers are still able to make submissions. You may review the currently submitted points below.<br />
    The points will be open for moderation at <?= $config->timing->submission_cutoff ?>.
</p>
<script type="text/javascript">
    setTimeout(function(){ window.location.reload() }, <?= ($moderationOpenTime - $now) * 1000 ?>);
</script>