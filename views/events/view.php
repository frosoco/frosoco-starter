<div class="panel view-content">
    <div class="view-content-title"><? echo $event->name; ?></div>
    <div class="view-content-location"><i class="icon-location-arrow"></i> <? echo $event->location; ?></div>
    <div class="view-content-start"><i class="icon-time"></i> <? echo date('Y-m-d g:i A', strtotime($event->start)); ?> to <? echo date('Y-m-d g:i A', strtotime($event->end)); ?></div>
	<div class="view-content-body"><? echo $this->markdown->parse($event->description); ?></div>
	<? if ($event->user->get()->id == $this->session->userdata('id')) { ?>
	<div class="view-content-edit"><a href="/events/edit/<? echo $event->id;?>">Edit this Event</a></div>
	<? } ?>
</div>
<div class="panel view-information">
	<div class="view-information-person">
		<div class="view-information-person-image">
			<img class="img-rounded" src="<? echo $event->user->get()->getPhoto(); ?>" /> 
		</div>
		<div class="view-information-person-name">
			<? echo $event->user->get()->getName(); ?>
			<div class="view-information-person-email">
				<? echo $event->user->get()->getEmail(); ?>
			</div>
		</div>
	</div>
    <div class="view-information-attendees">
        <? $signup_count = 0; ?>
        <? foreach ($signups as $signup) { ?>
        <? $signup_count += 1; ?>
		<? $attendee = $signup->user->get(); ?>
	    <a href="/users/view/<? echo $attendee->id; ?>" title="<? echo $attendee->getName(); ?>">
		<div class="view-information-attendee">
			<img class="img-rounded" src="<? echo $attendee->getPhoto(); ?>" />
		</div>
		</a>
		<? } ?>
	</div>
    <div class="view-information-actions">
		<? if ($this->session->userdata('id') && $event->open && $signup_count < $event->capacity) { ?>
	    	<? if ($signedup) { ?>
		    	<button type="button" class="btn btn-success" disabled="disabled">Signed up!</button>
		    <? } else { ?>
			    <button type="button" id="signup" class="btn btn-default">Sign up</button>
		    <? } ?>
		    <i style="display: none;" id="action-spinner" class="icon-spinner icon-spin icon-large"></i>
        <? } else { ?>
            <button type="button" class="btn btn-default" disabled="disabled">Closed</button>
        <? } ?>
    </div>
    <? if (strstr($this->session->userdata('role'), 'staff')) { ?>
    <div class="view-information-attendees">
        <table class="table">
        <? foreach ($signups as $signup) { ?>
        <? $attendee = $signup->user->get(); ?>
        <tr>
            <td><? echo $attendee->getName(); ?></td>
            <td><? echo $signup->time; ?></td>
        </tr>
        <? } ?>
        </table>
    </div>
    <? } ?>
</div>
<script>
	$('#signup').click(function() {
		$('#action-spinner').show();
		$.post("/events/signup", { event_id: <? echo $event->id; ?>}, function(data) {
			$('#action-spinner').hide();
			$('#signup').removeClass('btn-default').addClass('btn-success').attr('disabled','disabled').text('Signed up!');
		});
	});
</script>
