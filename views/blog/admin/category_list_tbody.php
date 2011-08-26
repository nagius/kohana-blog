<?php foreach ($categories as $category): ?>
<tr>
    <td><?= $category->id ?></td>
    <td><?= $category->name ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'edit','id'=>$category->id)),"Edit", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'delete','id'=>$category->id)),"Delete", array('class'=>'delete')) ?></td>
</tr>
<?php endforeach ?>
