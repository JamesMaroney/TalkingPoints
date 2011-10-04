<p class="status status-open">
    <strong>Talking Points submissions are now open for moderation.</strong><br />
    You may review the points below and submit modifications until <?= $config->timing->distribution ?> today, at which point the submissions will be distributed to parents.
</p>
<form id="savePoints" style="display: none;">
    <label for="password">Changes have been made. Please verify your password to persist the changes:</label>
    <input type="password" name="password" id="password" />
    <input type="submit" value="save" />
</form>
<p id="successMessage" style="display:none">Your changes were saved successfully.</p>
<script type="text/javascript">
    setTimeout(function(){ window.location.reload() }, <?= ($moderationCloseTime - $now) * 1000 ?>);
    function enableItemEditing(){
        var li = $(this);
        li.attr('contentEditable', true);
    }

    function detectChanges() {
        if( getValues() != initialValues ){
            successMessage.hide();
            savePointsForm.show();
        } else {
            savePointsForm.hide();
        }
    }
    function scheduleDetectChanges(){
        clearTimeout(detectChangesSchedule);
        setTimeout(detectChanges, 500);
    }
    var talkingPoints, initialValues, savePointsForm, detectChangesSchedule, successMessage;
    function getValues(){
        var values = [];
        talkingPoints.each(function(){
            values.push(this.innerHTML);
        })
        return values.join('|');
    }
    function handleSaveResponse(data, status){
        if(data.success){
            initialValues = getValues();
            savePointsForm.removeClass('error').hide().find('label').html('Changes have been made. Please verify your password to persist the changes:');
            successMessage.show();
            setTimeout(function(){
                successMessage.hide();
            }, 3000);
        } else {
            savePointsForm.addClass('error').find('label').html(data.message);
        }
    }
    $(function(){
        talkingPoints = $('#talkingPointsListing li').each(enableItemEditing);
        initialValues = getValues();
        successMessage = $('#successMessage');
        var passField = $("#password",savePointsForm);
        savePointsForm = $('#savePoints').submit(function(){
            var payload = {}, pass = passField.val();
            passField.val("");
            talkingPoints.each(function(){
                var li = $(this),
                    statement = li.text(),
                    docId = li.data('docid');
                if(!statement) return;
                if(!payload[docId]) payload[docId] = [];
                payload[docId].push(statement);
            })
            $.post("<?=$SITE ?>/?action=points", {password: pass, points: payload}, handleSaveResponse, 'json');
            return false;
        })

        $('#talkingPointsListing')
            .keyup(scheduleDetectChanges)
            .mouseout(scheduleDetectChanges);
    })
</script>