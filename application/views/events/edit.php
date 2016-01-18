<div class="panel create-editor">
	<form action="/events/save/<? echo $event->id; ?>" method="post">
	<div class="create-text">
		<div class="create-label">Name</div>
		<input type="text" name="event-name" placeholder="Name" value="<? echo $event->name; ?>" />
	</div>
	<div class="create-text">
		<div class="create-label">Location</div>
		<input type="text" name="event-location" placeholder="Location" value="<? echo $event->location; ?>" />
	</div>
	<div class="create-text">
		<div class="create-label">Start</div>
		<input type="text" id="event-dtpicker-start" name="event-start" placeholder="Start time" value="<? echo date("m/d/Y h:i a", strtotime($event->start)); ?>" />
	</div>
	<div class="create-text">
		<div class="create-label">Finish</div>
		<input type="text" id="event-dtpicker-end" name="event-end" placeholder="End time" value="<? echo date("m/d/Y h:i a", strtotime($event->end)); ?>" />
	</div>
	<div class="create-text">
		<div class="create-label">Capacity</div>
		<input type="text" name="event-capacity" placeholder="Capacity" value="<? echo $event->capacity; ?>" />
	</div>
	<div class="create-body">
		<div class="create-label">Description</div>
		<textarea name="event-description" id="create-body" oninput="this.editor.update()" placeholder="Description (Markdown enabled)"><? echo $event->description; ?></textarea>
	</div>
	<div class="create-actions">
		<button class="btn btn-default">Save</button>
	</div>
	</form>
</div>
<div class="panel create-preview">
	<div class="create-preview-title">Preview</div>
	<div id="create-preview" class="create-preview-body"> </div>
</div>
<script>
	$('#event-dtpicker-start').datetimepicker({
		timeFormat: "hh:mm tt"
	});
	$('#event-dtpicker-end').datetimepicker({
		timeFormat: "hh:mm tt"
	});
	function Editor(input, preview) {
		this.update = function() {
			preview.innerHTML = markdown.toHTML(input.value);
		};
		input.editor = this;
		this.update();
	}
	var $ = function(id) { return document.getElementById(id); };
	new Editor($("create-body"), $("create-preview"));
</script>