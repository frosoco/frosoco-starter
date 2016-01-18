
		<div class="attendants-display">
			<?	foreach ($user_responses as $sunet => $user_response) { ?>
			<? $user = $user_response['userData'];
			$hall = $user->house[0] . strval($user->room)[0];
			?>
			<div class="attendants-display-card <? echo $hall ?>">
				<a href="/users/view/<? echo $user->id; ?>"><img src="<? echo $user->getPhoto(); ?>" /></a>
				<div class="people-grid-info">
					<? $display_name = '';
					if ($user->isStaff())  {
						$display_name = $user->getName() . ' (' . $user->title . ')';
					} else {
						$display_name = $user->getName();
					}
					?>
					<div class="attendants-display-name"><? echo $display_name; ?></div>
					<div class="attendants-display-name"><? echo $user->getEmail(); ?></div>
					<div class="attendants-display-name"><? echo $user->getYear(); ?></div>
					<div class="attendants-display-name"><? echo $user->getLocation(); ?></div>
				</div>
			</div>
			<? } ?>
		</div>
	</div>

