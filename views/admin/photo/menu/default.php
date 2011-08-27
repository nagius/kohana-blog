<div class="box">
	<h2><?php echo __('Photo Management') ?></h2>
	<p>
Stuff to manage photos
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
