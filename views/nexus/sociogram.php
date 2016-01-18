<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<style>
		body {
		  height: 100%; }

			canvas {
			  background-color: #DFDFDF;
			  position: absolute;
			  left: 0px;
			  top: 0px; }

			.input {
			  position: absolute;
			  left: 15px;
			  top: 15px;
			  background-color: white;
			  box-shadow: 3px 3px 5px #888; }
			  .input form {
			    margin: 0px; }
			  .input input {
			    margin: 5px 10px;
			    border: none;
			    outline: none;
			    width: 300px; }

			.buttons {
				position: absolute;
				left: 15px;
				top: 58px;}

			.status {
				position: absolute;
				left: 15px;
				top: 88px;}

			.node {
			  position: absolute;
			  padding: 5px 10px;
			  left: 50%;
			  top: 50%;
			  cursor: default;
			  background-color: white; }
			  .node:hover {
			    outline: 1px solid steelblue; }

			.selected {
			  outline: 1px solid crimson; }
			  .selected:hover {
		    outline: 1px solid crimson; }
	</style>
	<title>Sociogram</title>
</head>
<body oncontextmenu="return false;">
	<canvas id="canvas"></canvas>
	<div class="input">
		<form>
			<input id="name" type="text" />
		</form>
	</div>
	<div class="buttons">
		<button type="button" id="save" class="btn btn-primary">Save Graph</button>
		<button type="button" id="load" class="btn btn-default">Load Graph</button>
	</div>
	<div id="status"></div>
	<script>

		$(function() {
			var availableTags = [
			<? foreach ($users as $user) { ?>
				"<? echo $user->getName(); ?>",
			<? } ?>
			];
			$('#name').autocomplete({
				source: availableTags
			});
		});

		// Get references to our canvas
		var canvas = document.getElementById("canvas");
		canvas.width = $(window).width();
		canvas.height = $(window).height();
		var ctx = canvas.getContext("2d");

		$(window).resize(function() {
			canvas.width = $(window).width();
			canvas.height = $(window).height();
		});

		// Constructor for our node
		function Node(x, y, text) {
			this.metrics = ctx.measureText(text);
			this.x = x;
			this.y = y;
			console.log(this.metrics);
			this.w = this.metrics.width + 40;
			this.h = 30;
			this.text = text;
			this.isSelected = false;
			this.isHovered = false;
		}

		// Handles drawing nodes
		Node.prototype.draw = function(context) {

			// Styling of the rectangle and text
			context.font = '12pt Calibri';
			context.textAlign = 'center';
			context.fillStyle = 'white';
			context.fillRect(this.x, this.y, this.w, this.h);

			// If the node is selected, give it a red border
			if (this.isSelected) {
				context.strokeStyle = 'red';	
				context.strokeRect(this.x, this.y, this.w, this.h);
			}

			// If the node is hovered, give it a blue border
			if (this.isHovered) {
				context.strokeStyle = 'blue';
				context.strokeRect(this.x, this.y, this.w, this.h);			
			}

			// Must go after rectangle so it overlays properly
			context.fillStyle = '#555';
			context.fillText(this.text, this.x + this.w / 2, this.y + 20);

		}

		Node.prototype.contains = function(mx, my) {
			return (this.x <= mx) && (this.x + this.w >= mx) &&
				(this.y <= my) && (this.y + this.h >= my);
		}

		// Constructor for our edge
		function Edge(n1, n2) {
			this.n1 = n1;
			this.n2 = n2;
		}

		Edge.prototype.draw = function(context) {
			context.moveTo(this.n1.x + this.n1.w / 2, this.n1.y + this.n1.h / 2);
			context.lineTo(this.n2.x + this.n2.w / 2, this.n2.y + this.n2.h / 2);
			context.lineWidth = 1;
			context.stroke();
		}

		function CanvasState(canvas) {

			this.canvas = canvas;
			this.width = canvas.width;
			this.height = canvas.height;
			this.ctx = canvas.getContext('2d');

			// This complicates things a little but but fixes mouse co-ordinate problems
		  	// when there's a border or padding. See getMouse for more detail
		  	var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;
		  	if (document.defaultView && document.defaultView.getComputedStyle) {
		    	this.stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10)      || 0;
			    this.stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)       || 0;
			    this.styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)  || 0;
			    this.styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)   || 0;
			}

			this.nodes = [];
			this.edges = [];

			this.dragging = false;
			this.selection = null;
			this.drawingLine = false;
			this.source = null;
			this.currentLine = null;
			this.click = false;

			var state = this;

			canvas.addEventListener('selectstart', function(e) {
				e.preventDefault();
				return false;
			}, false);

			canvas.addEventListener('mousedown', function(e) {

				var mx = e.pageX;
				var my = e.pageY;

				// Assume that it is a click
				state.click = true;

				var nodes = state.nodes;
				for (var i = nodes.length - 1; i >= 0; i--) {
					
					// If it contains the mouse coordinates, then we have the object
					if (nodes[i].contains(mx, my)) {
						var selection = nodes[i];
						state.dragoffx = mx - selection.x;
						state.dragoffy = my - selection.y;
						state.dragging = true;
						state.selection = selection;
						return;
					}

				}

				// Clicking outside of any object
				if (state.selection) {
					state.selection = null;
				}

			}, true);

			canvas.addEventListener('mousemove', function(e) {
				
				// The mouse has moved. Not a click anymore.
				state.click = false;

				// Turn hover off on all nodes
				for (var i = 0; i < state.nodes.length; i++) {
					state.nodes[i].isHovered = false;
				}

				// If currently dragging an element, do that
				if (state.dragging) {

					var newX = e.pageX - state.dragoffx;
					var newY = e.pageY - state.dragoffy;

					state.selection.x = newX;
					state.selection.y = newY;

				}

				// If we currently have something selected, do that
				if (state.drawingLine) {

					state.currentLine.n2.x = e.pageX;
					state.currentLine.n2.y = e.pageY;

				}

				// If hovering over an object that is not the selected object, highlight color
				mouseNode = state.retrieveNode(e);
				if (mouseNode != null && mouseNode != state.selection) {
					mouseNode.isHovered = true;
				}

				state.draw();

			}, true);

			canvas.addEventListener('mouseup', function(e) {

				// Stop dragging on mouseUp
				state.dragging = false;

				// If it is a click
				if (state.click) {

					// If there is a selected object, then change its stroke
					var selection = state.selection;
					var edges = state.edges;
					var nodes = state.nodes;
					if (selection) {

						// If it turns out we're right clicking, delete the node
						if (event.which == 3) {

							nodes.splice(nodes.indexOf(selection), 1);
							state.drawingLine = false;

							
							for (var i = 0; i < edges.length; i++) {

								if (edges[i].n1 == selection ||
									edges[i].n2 == selection) {
									edges.splice(i, 1);
									i -= 1;
								}

							}
							
							state.draw();

							return;
							
						}

						if (state.drawingLine) {
							state.currentLine.n2 = selection;
							state.edges.push(state.currentLine);
							state.drawingLine = false;
							state.currentLine = null;
						} else {
							state.drawingLine = true;
							selection.isSelected = !selection.isSelected;
							state.currentLine = new Edge(selection.x + selection.w / 2, selection.y + selection.h / 2, e.pageX, e.pageY);
							state.currentLine.n1 = selection;
							state.currentLine.n2 = new Node(e.pageX, e.pageY, "");
						}

					}

					// We have clicked in the canvas area
					else {

						// Stop drawing the line
						state.drawingLine = false;
						state.currentLine = null;
						
						// Remove selection from all nodes
						for (var i = 0; i < state.nodes.length; i++) {
							state.nodes[i].isSelected = false;
						}

					}

				}

				state.draw();

			}, true);

			// Handles clicking a box
			canvas.addEventListener('click', function(e) {

				/*

				// If we're not drawing a line
				if (!state.drawingLine) {

					// Keep track of the start node
					var mx = e.pageX;
					var my = e.pageY;

					var nodes = state.nodes;
					for (var i = nodes.length - 1; i >= 0; i--) {

						// If it contains the mouse coordinates, then we have the object
						if (nodes[i].contains(mx, my)) {
							var selection = nodes[i];
							state.dragoffx = mx - selection.x;
							state.dragoffy = my - selection.y;
							state.drawingLine = true;
							state.currentLine = new Edge(selection.x + 0.5 * selection.w, selection.y + 0.5 * selection.h, mx, my);
							state.source = selection;
							return;
						}

					}

				}

				else {

					// Turn off the line drawing
					state.drawingLine = false;

					// TODO: check if we landed on a node
					// If we did, preserve the edge

				}

				*/

			});

		}

		
		CanvasState.prototype.retrieveNode = function(e) {

			// Get the current mouse position
			var mx = e.pageX;
			var my = e.pageY;

			var nodes = this.nodes;
			for (var i = nodes.length - 1; i >= 0; i--) {

				// If it contains the mouse coordinates, then we have the object
				if (nodes[i].contains(mx, my)) {
					var selection = nodes[i];
					this.dragoffx = mx - selection.x;
					this.dragoffy = my - selection.y;
					return selection;
				}

			}

			return null;	

		}

		CanvasState.prototype.addNode = function(node) {
			this.nodes.push(node);
			this.draw();
		}

		CanvasState.prototype.clear = function() {
			this.ctx.clearRect(0, 0, this.width, this.height);
		}

		CanvasState.prototype.draw = function() {

			var ctx = this.ctx;
			var nodes = this.nodes;
			var edges = this.edges;
			this.clear();
			ctx.beginPath();

			if (this.drawingLine) {
				this.currentLine.draw(ctx);
			}

			for (var i = 0; i < edges.length; i++) {
				edges[i].draw(ctx);
			}

			for (var i = 0; i < nodes.length; i++) {
				nodes[i].draw(ctx);
			}

			localStorage['nodes'] = nodes;
			localStorage['edges'] = edges;
			
		}

		var s = new CanvasState(document.getElementById('canvas'));

		// Handles submitting names
		$('form').submit(function(e) {

			// Retrieve the name from the input
			var name = $('input:first').val();

			// Add a node
			s.addNode(new Node(100, 100, name));

			// Prevent default form submission
			e.preventDefault();

		});

		// Handles saving the thing
		$('#save').click(function() {

			// Get the data that is being stored in the object
			the_nodes = JSON.stringify(s.nodes);
			the_edges = JSON.stringify(s.edges);

			$.post('/nexus/sociogram', { nodes: the_nodes, edges: the_edges }, function(data) {
				console.log(data);
			});

		});

		// Handles loading the thing
		$('#load').click(function() {

			// Shoot off something to an AJAX endpoint about 
			$.get('/nexus/sociogram?load=true', function(data) {
				console.log(data);
				s.nodes = data[0];
				s.edges = data[1];
				s.draw();
			});

		});

	</script>
</body>