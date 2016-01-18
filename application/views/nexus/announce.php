	<div class="panel">
		<form class="form-horizontal" role="form" action="/nexus/send_announcement" method="post">
	  <div class="form-group">
	    <label for="email" class="col-lg-2 control-label">Email</label>
	    <div class="col-lg-10">
	      <input type="email" name="email" class="form-control" id="announce-email" placeholder="Email">
	    </div>
	  </div>
	<div class="form-group">
	    <label for="message" class="col-lg-2 control-label">Message</label>
	    <div class="col-lg-10">
	      <textarea id="announce-message" oninput="this.editor.update()" name="message" class="form-control" rows="3"></textarea>
	    </div>
	  </div>
	 <div class="form-group">
	    <label for="message" class="col-lg-2 control-label">Targets</label>
	 	<div class="col-lg-10">
	  	<label class="checkbox-inline">
		  <input type="checkbox" id="inlineCheckbox1" name="targets[]" value="residents"> Residents
		</label>
		<label class="checkbox-inline">
		  <input type="checkbox" id="inlineCheckbox2" name="targets[]" value="staff"> Staff
		</label>
		<label class="checkbox-inline">
		  <input type="checkbox" id="inlineCheckbox3" name="targets[]" value="seniorstaff"> Senior Staff
		</label>
	</div>
	  </div>
	  <div class="form-group">
	    <div class="col-lg-offset-2 col-lg-10">
	      <button type="submit" class="btn btn-default">Send message</button>
	    </div>
	  </div>

	</form>
	</div>


<div class="panel create-preview">
	<div class="create-preview-title">Preview</div>
	<div id="announce-preview" class="create-preview-body"> </div>
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
	new Editor($("announce-message"), $("announce-preview"));
</script>