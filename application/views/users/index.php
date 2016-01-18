	<div class="people">
		<input id="user-search" type="text" class="form-control" placeholder="Search" />
		<div id="filters">
			<p>Filter by hall: </p>
			<input type="checkbox" value=".A1" id="A1" /><label for="A1">A1</label>
			<input type="checkbox" value=".A2" id="A2" /><label for="A2">A2</label>
			<input type="checkbox" value=".A3" id="A3" /><label for="A3">A3</label>
			<input type="checkbox" value=".S1" id="S1" /><label for="S1">S1</label>
			<input type="checkbox" value=".S2" id="S2" /><label for="S2">S2</label>
			<input type="checkbox" value=".S3" id="S3" /><label for="S3">S3</label>
		</div>
		<div class="people-grid">
			<? foreach ($users as $user) { ?>
			<? $hall = $user->house[0] . strval($user->room)[0]; ?>
			<div class="people-grid-person <? echo $hall ?>">
				<a href="/users/view/<? echo $user->id; ?>"><img src="<? echo $user->getPhoto(); ?>" /></a>
				<div class="people-grid-info">
					<? $display_name = '';
					if ($user->isStaff())  {
						$display_name = $user->getName() . ' (' . $user->title . ')';
					} else {
						$display_name = $user->getName();
					}
					?>
					<div class="people-grid-name"><? echo $display_name; ?></div>
					<div class="people-grid-email"><? echo $user->getEmail(); ?></div>
					<div class="people-grid-year"><? echo $user->getYear(); ?></div>
					<div class="people-grid-location"><? echo $user->getLocation(); ?></div>
				</div>
			</div>
			<? } ?>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			var $container = $("div.people-grid");
			$container.isotope({
				itemSelector: '.people-grid-person',
				layoutMode: 'fitRows',
				transitionDuration: 0.2
			});

			$('#user-search').keyup(function() {
				var query = $(this).val().toLowerCase();

				$container.isotope({
					filter: function(){
						return ($(this).html().toLowerCase().indexOf(query) >= 0);
					}
				});
			});


			// filter by hall
			$checkboxes = $('#filters input');
			$checkboxes.change(function(){
				var filters = [];
			    // get checked checkboxes values
			    $checkboxes.filter(':checked').each(function(){
			    	filters.push( this.value );
			    });
			    // ['.A1', '.A2'] -> '.A1, .A2'
			    filters = filters.join(', ');
			    $container.isotope({ filter: filters });
			});
		});
	</script>
