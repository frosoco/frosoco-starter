    <div class="page-header">
        <h1>Staff</h1>
    </div>

    <? foreach ($staff_members as $staff) { ?>
    <div class="row bio">
        <img src="<? echo $staff->getPhoto(); ?>" class="img-circle bio-pic" />
        <div class="bio-desc">
            <h2><? echo $staff->first_name . ' ' . $staff->last_name;?></h2>
            <h4><? echo $staff->title; ?></h4>
            <p><? echo $staff->description; ?></p>
        </div>
    </div>
    <? } ?>
