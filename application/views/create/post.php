<div class="panel create-editor">
	<div class="create-types">
		<span id="create-post" class="create-type-selected">Post</span>
		<span id="create-event"><a href="/create/event">Event</a></span>
		<span id="create-quote"><a href="/create/quote">Quote</a></span>
		<span id="create-listing"><a href="/create/listing">Listing</a></span>
	</div>
	<form action="/posts/add" method="post">
	<div class="create-title">
		<input type="text" name="post-title" placeholder="Title" />
	</div>
	<div class="create-body">
		<textarea name="post-body" id="create-body" oninput="this.editor.update()" placeholder="Start typing here (Markdown enabled)"></textarea>
	</div>
	<div class="create-actions">
		<button class="btn btn-default">Submit</button>
	</div>
	</form>
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