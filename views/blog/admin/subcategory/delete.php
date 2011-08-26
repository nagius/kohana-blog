<h2><?php echo __('Delete Subcategory :name?', array(':name'=>$subcategory->name)) ?></h2>
<p>
	Are you sure you want to delete the subcategory, <?php echo $subcategory->name ?>?
	This action cannot be undone.
</p>
<?php
	echo Form::open();
	echo Form::submit('yes', 'Yes');
	echo Form::submit('no', 'No');
	echo Form::close();
?>
