<div class="box">
 	<h2><?php echo __('Filters') ?></h2>
	<p>
<?php 
	echo Form::open('admin/blog/article/list',array('id'=>'search_form'));
	echo Form::checkbox('activate', NULL, isset($_POST['activate']))."Activate";
	echo "<br>".__('Keywords').":";
	echo Form::input('keywords',Arr::get($_POST,'keywords'));
	echo "<br>".__('Among').":";

	echo "<ul>";
	foreach(array('title','description','keywords','text') as $criterion)
	{
		echo '<li>';
		echo Form::checkbox('criteria[]', $criterion, 
			in_array($criterion, Arr::get($_POST, 'criteria', array()))
		);
		echo __(ucfirst($criterion));
		echo '</li>';
	} 
	echo "</ul>";

	echo "<br>".__('Tags').":";

	echo "<ul>";
	foreach($tags as $tag)
	{
		echo "<li>";
		echo Form::checkbox('tags[]',$tag->name, in_array($tag->name, Arr::get($_POST, 'tags', array()))).ucfirst($tag->name);
		echo "</li>";
	}
	echo "</ul>";
	echo "<br>".__('Date').":";
	echo Form::input('datemin', Arr::get($_POST,'datemin'))."<br>";
	echo Form::input('datemax', Arr::get($_POST,'datemax'))."<br>";
	echo Form::submit('submit_search',__('Search')).Form::close();
 ?>

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
