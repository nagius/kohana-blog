<?php foreach ($photos as $photo): ?>
<tr>
    <td><?= $photo->id ?></td>
	<td><?= HTML::anchor($photo->path, $photo->title) ?></td>
    <td><?= $photo->subtitle ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'edit','id'=>$photo->id)),"Edit", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'crop','id'=>$photo->id)),"Crop", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'delete','id'=>$photo->id)),"Delete", array('class'=>'delete')) ?></td>
</tr>
<?php endforeach ?>

