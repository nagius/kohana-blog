<h2><?php echo __('Delete photo :name?', array(':name'=>$photo->title)) ?></h2>
<p>
	Are you sure you want to delete the photo, <?php echo $photo->title ?>?
	This action cannot be undone.
</p>
<?php
	echo Form::open();
	echo Form::submit('yes', 'Yes');
	echo Form::submit('no', 'No');
	echo Form::close();
?>
