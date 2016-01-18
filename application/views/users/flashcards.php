<div class="container">
	<div class="flashcards">
<? foreach ($people as $person) { ?>
<div class="flashcard-card">
	<img src="<? echo $person->getPhoto(); ?>" />
	<div class="flashcard-name"><? echo $person->getName(); ?></div>
</div>
<? } ?>
</div>
</div>
<script>
	$('.flashcard-card').click(function() {
		$(this).find('.flashcard-name').slideToggle('fast');
	});
</script>