<h2><?php echo $legend; ?></h2>
<?php echo Form::open(); ?> 

<?php echo isset($errors['name']) ? '<p class="error">'.$errors['name'].'</p>' : ''; ?> 
<p>

 <?php echo $photo->label('title'); ?>
 <?php echo $photo->input('title'); ?>
 <?php echo $photo->label('subtitle'); ?>
 <?php echo $photo->input('subtitle'); ?>
 <?php echo $photo->label('article'); ?>
 <?php echo $photo->input('article'); ?>


</p>

<p class="submit">
	<?php echo Form::submit('submit', $submit); ?> 
</p>
<?php echo Form::close(); ?> 
