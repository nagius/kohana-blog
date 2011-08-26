<?php foreach ($subcategories as $subcategory): ?>
<tr>
    <td><?= $subcategory->id ?></td>
    <td><?= $subcategory->name ?></td>
    <td><?= $subcategory->category->load()->name ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'edit','id'=>$subcategory->id)),"Edit", array('class'=>'edit')) ?></td>
    <td><?= HTML::anchor($request->uri(array('action'=>'delete','id'=>$subcategory->id)),"Delete", array('class'=>'delete')) ?></td>
</tr>
<?php endforeach ?>
