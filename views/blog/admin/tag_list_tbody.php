<?php foreach ($tags as $tag): ?>
<tr>
    <td><?= $tag->id ?></td>
    <td><?= $tag->name ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'edit','id'=>$tag->id)),"Edit", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'delete','id'=>$tag->id)),"Delete", array('class'=>'delete')) ?></td>
</tr>
<?php endforeach ?>
