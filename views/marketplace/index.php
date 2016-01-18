<div class="panel-users">
	<div class="create-actions">
		<a href="/create/listing"><button class="btn btn-success create-btn">+ Add Item</button></a>
	</div>

	<table class="table table-hover">
		<tr>
			<th>Item</th>
			<th>Description</th>
			<th>Asking</th>
			<th>Seller</th>
			<!-- <th>Profile</th> --> 
		</tr>
		<? foreach ($listings as $listing) { ?>
		<tr>
			<? $user = $listing->user->get(); ?>
			<td><? echo $listing->item; ?></td>
			<td><? echo $listing->description; ?></td>
			<td><? echo $listing->asking; ?></td>
			<td><a href="mailto:<? echo $user->sunet; ?>@stanford.edu"><? echo $user->getName();?></a></td>
		</tr>
		<? } ?>
	</table>
</div>