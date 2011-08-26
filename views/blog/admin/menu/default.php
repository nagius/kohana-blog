<div class="box">
	<h2><?php echo __('Blog Management') ?></h2>
	<p>
		<ul>
			<li>Stuff for manager
			</li>
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
