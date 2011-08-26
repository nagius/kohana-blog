<h2><?php echo $article->title ?></h2>
<p>
	By <?php echo ucfirst($article->author->load()->username) ?> 
	on <?php echo $article->verbose('date') ?> 
</p>
<hr>
Abstract :
<?php echo $article->abstract ?>
<hr>
<?php echo $article->text ?>
<br>Photos: <br>
<?php foreach($article->photos as $photo): ?>
<?= HTML::Image($photo->path, array('alt'=>$photo->title)) ?>
<br>
<?php endforeach ?>

<?php echo $comment_form ?> 
<?php echo $comment_list ?> 
