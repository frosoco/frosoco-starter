<!-- Roger's code from 2013-2014 -->

<div class="row">
	<div class="col-xs-8">
		<div class="events">
		<? $currentTime = time() - (24 * 60 * 60); ?>
		<? foreach ($events as $event) { ?>
		<? 
		$eventTime = strtotime($event->start); 
		// Check to see if we need to reprint the date header
		if (($eventTime - $currentTime) / (24 * 60 * 60) >= 1) { ?>
		<div class="events-listing-date"><? echo date('l, F j, Y', $eventTime); ?></div>
		<? 
		$currentTime = $eventTime;
		} ?>
		<div class="events-listing">
			<div class="event-listing-time"><? echo date('g:i A', strtotime($event->start)); ?></div>
			<div class="event-listing-image" style="background-image: url('<? echo $event->user->get()->getPhoto(); ?>');"></div>
			<div class="event-listing-description">
				<div class="event-listing-title"><a href="/events/view/<? echo $event->id; ?>"><? echo $event->name; ?></a></div>
				<div class="event-listing-author"><? echo $event->user->get()->getName(); ?></div>
				<div class="event-listing-location"><? echo $event->location; ?></div>
				<div class="event-listing-attending">
					<? $eventCount = $event->getSignups(); ?>
					<? if ($eventCount == 0) { ?>
						Nobody is currently signed up for this event.
					<? } else if ($eventCount == 1) { ?>
						1 person is signed up for this event.
					<? }else { ?>
						<? echo $eventCount; ?> people are signed up for this event.
					<? } ?>	
				</div>
			</div>
		</div>
		<? } ?>
		</div>
	</div>
	<div class="col-xs-4">
		<div class="events-actions">
			<a href="/create/event"><button class="btn btn-success">+ Create Event</button></a>
		</div>
		<!-- Calendar goes here -->
	</div>
</div>

<!--
<div class="content-cards">
<? foreach ($events as $event) { ?>
<a href="/events/view/<? echo $event->id; ?>">
<div class="content-card content-event">
	<div class="content-title"><? echo $event->name; ?></div>
	<div class="content-start"><i class="icon-time"></i><? echo date('Y-m-d g:i A', strtotime($event->start)); ?></div>
	<div class="content-summary"><? echo $event->getExcerpt(); ?></div>
	<div class="content-info">
		<span class="content-author">
			<img class="img-rounded" src="<? echo $event->user->get()->getPhoto(); ?>" /> <? echo $event->user->get()->getName(); ?>
		</span>
		<span class="content-heart">
			<i class="icon-heart"></i> 0
		</span>
	</div>
</div>
</a>
<? } ?>
</div>
<script>
	var container = document.querySelector('.content-cards');
	var msnry = new Masonry(container, {
		itemSelector: '.content-card'
	});
</script>
-->