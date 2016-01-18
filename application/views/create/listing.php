<div class="panel create-editor">
<!-- 	<div class="create-types">
		<span id="create-post"><a href="/create/post">Post</a></span>
		<span id="create-event"><a href="/create/event">Event</a></span>
		<span id="create-quote"><a href="/create/quote">Quote</a></span>
		<span id="create-listing" class="create-type-selected">Listing</span>
	</div> -->
	<form action="/marketplace/add" method="post">
	<div class="create-item">
		<input type="text" name="listing-title" placeholder="Item name" />
	</div>
	<div class="create-asking">
		<input type="text" name="listing-asking" placeholder="Asking (number only)" />
	</div>
	<div class="create-body">
		<textarea name="listing-body" id="create-body" oninput="this.editor.update()" placeholder="Start typing here (Markdown enabled)"></textarea>
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