<script type="text/javascript" src="/media/js/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{ 
    //attach onSubmit to the form
    $('#search_form').submit(function()
    {
        //When submitted do an ajaxSubmit
        $(this).ajaxSubmit(
        {
            dataType: 'json',
            success: function(html, responseCode) 
            {
                $('#articles tbody tr').remove();
                $('#articles tbody').append(html);
                $('#articles').trigger("update");
            }
        });
        //return false to prevent normal submit
        return false;
    })
}
); 

$(document).ready(function() 
{ 
    $("#articles").tablesorter(); 
} 
); 

</script>

<?= HTML::anchor(Request::instance()->uri(array('action'=>'new', 'page'=>NULL)), __("Create article")) ?>
<h2><?php echo $legend ?></h2>
<?= $pagination ?>
<table id='articles' class="tablesorter">
<thead>
<tr>
    <th>Date</th>
    <th>Title</th>
    <th>State</th>
    <th>Actions</th>
    <th></th>
</tr>
</thead>
<tbody>
<?= $tbody ?>
</tbody>
</table>
<?= $pagination ?>

