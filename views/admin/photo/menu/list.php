<div class="box">
	<h2>Search filter</h2>
	<p>
<?= Form::open('admin/photo/list',array('id'=>'search_form')) ?>
Keywords:
<?= Form::input('terms',Arr::get($_POST,'terms')) ?>
<br>
<?= Form::submit('','Search').Form::close() ?>



		<ul>
<?php if (isset($links)): ?>
	<li><?php echo __('Quick Links') ?> 
				<ul>
<?php foreach($links as $text=>$link): ?>
					<li><?php echo HTML::anchor($link, $text) ?></li>
<?php endforeach; ?>
				</ul>
			</li>
<?php endif; ?>
		</ul>
	</p>
</div>
