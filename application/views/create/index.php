<div class="panel create-editor">
	<div class="create-types">
		<span id="create-post" class="create-type-selected">Post</span>
		<span id="create-event">Event</span>
		<span id="create-quote">Quote</span>
	</div>
	<div class="create-title">
		<input type="text" name="title" placeholder="Title" />
	</div>
	<div class="create-body">
		<textarea name="body" id="create-body" oninput="this.editor.update()" placeholder="Start typing here"></textarea>
	</div>
	<div class="create-actions">
		<button class="btn btn-default">Submit</button>
	</div>
</div>
<div class="panel create-preview">
	<div class="create-preview-title">Preview</div>
	<div id="create-preview" class="create-preview-body"> </div>
</div>
<script>
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