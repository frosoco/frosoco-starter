<div class="panel view-content">
	<div class="view-content-title"><? echo $title; ?></div>
	<div class="view-content-body"><? echo $post; ?></div>
</div>
<div class="panel view-information">
	<div class="view-information-person">
		<div class="view-information-person-image">
			<img class="img-rounded" src="<? echo $author->getPhoto(); ?>" /> 
		</div>
		<div class="view-information-person-name">
			<? echo $author->getName(); ?>
			<div class="view-information-person-email">
				<? echo $author->getEmail(); ?>
			</div>
		</div>
	</div>
	<div class="view-information-comments">
		Comments are currently closed.
	</div>
</div>