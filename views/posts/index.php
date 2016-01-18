<div class="content-cards">
<? foreach ($posts as $post) { ?>
<a href="/posts/view/<? echo $post->id; ?>">
<div class="content-card content-post">
	<div class="content-title"><? echo $post->title; ?></div>
	<div class="content-body"><? echo $post->getPreview(); ?></div>
	<div class="content-info">
		<span class="content-author">
			<img class="img-rounded" src="<? echo $post->getAuthor()->getPhoto(); ?>" /> <? echo $post->getAuthor()->getName(); ?>
		</span>
		<span class="content-heart">
			<i class="icon-heart"></i> 0
		</span>
	</div>
</div>
</a>
<? } ?>
</div>
<script>
	var container = document.querySelector('.content-cards');
	var msnry = new Masonry(container, {
		itemSelector: '.content-card'
	});
</script>