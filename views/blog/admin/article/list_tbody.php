<?php foreach ($articles as $article): ?>
<tr>
    <td><?= $article->id ?></td>
    <td><?= $article->title ?></td>
    <td><?= $article->state ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'edit','id'=>$article->id)),"Edit", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'history','id'=>$article->id)),"History", array('class'=>'history')) ?></td>
</tr>
<?php endforeach ?>
