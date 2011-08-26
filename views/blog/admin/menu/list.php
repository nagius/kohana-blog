<div class="box">
	<h2><?php echo __('Blog Management') ?></h2>
	<p>
<?= Form::open('admin/blog/article/list',array('id'=>'search_form')) ?>
Name :
<?= Form::input('title',Arr::get($_POST,'title')) ?>
<br>
<?= Form::submit('','Find').Form::close() ?>

		<ul>
<?php if (isset($quicklinks)): ?>
	<li><?php echo __('Quick Links') ?> 
				<ul>
<?php foreach($quicklinks as $text=>$link): ?>
					<li><?php echo HTML::anchor($link, $text) ?></li>
<?php endforeach; ?>
				</ul>
			</li>
<?php endif; ?>
		</ul>
	</p>
</div>
