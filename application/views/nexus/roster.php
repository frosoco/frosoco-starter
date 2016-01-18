<div class="panel">
	<form method="post">
		<div class="checkbox">
			<input type="checkbox" name="freshmen" value="freshmen"> Freshmen</input>
		</div>
		<div class="checkbox">
			<input type="checkbox" name="sophomores" value="sophomores"> Sophomores</input>
		</div>
		<div class="checkbox">
			<input type="checkbox" name="staff" value="staff"> Staff</input>
		</div>
		<div class="text">
			<div class="create-label">Group Size</div>
			<input type="text" name="size" />
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</form>
</div>
<div class="panel">
	<? if ($users) { ?>
	<? $currentGroup = -1; ?>
	<? foreach ($users as $key=>$user) { ?>
	<?
	$groupNum = (int)($key/$group_size);
	if ($groupNum != $currentGroup) {
		if ($currentGroup != -1) { ?>
			</div>
		<? } $currentGroup = $groupNum; ?>
		<div class="roster-group">
	<? } ?>
	<div class="roster-user"><? echo $user->getName() . ' (' . $user->getEmail() . ')'; ?></div>
	<? } ?>
	<? } ?>
</div>