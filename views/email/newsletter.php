<!doctype html>
<html>
<body style="font-family: sans-serif; font-weight: 300;">
	<h2>Weekly Newsletter for <? echo $dates[0]; ?> to <? echo $dates[1]; ?></h2>
	<? foreach ($events as $event) { ?>
	<div style="margin-top: 10px; margin-bottom: 10px;">
		<strong><? echo $event->name; ?> (<a href="http://frosoco.stanford.edu/events/view/<? echo $event->id; ?>">link</a>)</strong>
		<div>Start: <? echo $event->getStart(); ?></div>
		<div>End: <? echo $event->getEnd(); ?></div>
		<div style="margin-top: 5px;"><? echo $this->markdown->parse($event->description); ?></div>
	</div>
	<? } ?>
</body>
</html>