<h2><?php echo $legend; ?></h2>
<?php echo Form::open(NULL, array('enctype' => 'multipart/form-data')); ?> 

<?php echo isset($errors['name']) ? '<p class="error">'.$errors['name'].'</p>' : ''; ?> 
<p>
<?php foreach ($photo->inputs() as $label => $input): ?>
    <dt><?php echo $label ?></dt>
    <dd><?php echo $input ?></dd>

<?php endforeach ?>

</p>

<p class="submit">
	<?php echo Form::submit('submit', $submit); ?> 
</p>
<?php echo Form::close(); ?> 
