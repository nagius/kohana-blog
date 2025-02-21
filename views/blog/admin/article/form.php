<script type="text/javascript">
	$(document).ready(function() {
		$("textarea").markItUp(mySettings);
	});
</script>

<h2><?php echo $legend; ?></h2>
<?php echo Form::open(); ?> 

<?php echo isset($errors['title']) ? '<p class="error">'.$errors['title'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('title') ?> 
	<?php echo $article->input('title') ?> 
</p>

<?php if ($slug_editable): ?>
	<?php echo isset($errors['slug']) ? '<p class="error">'.$errors['slug'].'</p>' : '' ?> 
	<p>
		<?php echo $article->label('slug') ?> 
		<?php echo $article->input('slug') ?> 
	</p>
<?php endif; ?>

<?php echo isset($errors['text']) ? '<p class="error">'.$errors['text'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('text') ?> 
	<?php echo $article->input('text') ?> 
</p>

<?php echo isset($errors['description']) ? '<p class="error">'.$errors['description'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('description') ?> 
	<?php echo $article->input('description') ?> 
</p>

<?php echo isset($errors['keywords']) ? '<p class="error">'.$errors['keywords'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('keywords') ?> 
	<?php echo $article->input('keywords') ?> 
</p>

<?php echo isset($errors['state']) ? '<p class="error">'.$errors['state'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('state') ?> 
	<?php echo $article->input('state') ?> 
</p>

<?php echo isset($errors['subcategory']) ? '<p class="error">'.$errors['subcategory'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('subcategory') ?> 
	<?php echo $article->input('subcategory') ?> 
</p>

<?php echo isset($errors['tags']) ? '<p class="error">'.$errors['tags'].'</p>' : '' ?> 
<p>
	<?php echo $article->label('tags') ?> 
	<?php echo $article->input('tags') ?> 
</p>

<?php if ($comment_needed): ?>
	<?php echo isset($errors['comment']) ? '<p class="error">'.$errors['comment'].'</p>' : '' ?> 
	<p>
		<?php echo $article->label('comment') ?> 
		<?php echo $article->input('comment') ?> 
	</p>
<?php endif; ?>

<p class="submit">
	<?php echo Form::submit('submit', $submit); ?> 
</p>
<?php echo Form::close(); ?> 
