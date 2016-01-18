<div class="create-editor quote">
<!-- 	<div class="create-types">
		<span id="create-post"><a href="/create/post">Post</a></span>
		<span id="create-event"><a href="/create/event">Event</a></span>
		<span id="create-quote" class="create-type-selected">Quote</span>
		<span id="create-listing"><a href="/create/listing">Listing</a></span>
	</div> -->
	<form action="/quotes/add" method="post">
	<div class="create-quote">
		<textarea name="body" id="create-body" oninput="this.editor.update()" placeholder="Insanity: doing the same thing over and over again and expecting different results."></textarea>
	</div>
	<div class="create-author">
		<input name="author" id="create-author"  oninput="this.editor.update()" type="text" placeholder="Albert Einstein" autocomplete="none" />
	</div>
	<div class="create-actions">
		<button type="submit" class="btn btn-default">Submit</button>
	</div>
	</form>
</div>

<!-- Commented out due to formatting issues.
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
</script>-->