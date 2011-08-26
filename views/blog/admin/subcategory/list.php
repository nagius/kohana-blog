<script type="text/javascript" src="/media/js/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{               
    $('#subcategories a.delete').click(function()
    {
        var href = $(this).attr("href"); 
        var $dialog = $('<div></div>').html('Delete subcategory ?').dialog(
        { 
            modal: true,
            title: 'Confirm',
            buttons: 
            { 
                "Delete": function()
                {
                    $(this).dialog("close"); 
                    $.ajax( //ajax request starting
                    {
                        url: href, //send the ajax request 
                        type:"POST",//request is a POSt request
                        dataType: "json",//expect json as return
                        success: function(data, responseCode) //trigger this on success
                        {
                            var html='<p class="'+data.flash_class+'">'+data.text+'</p>';
                            jQuery('<div id="flash_message" class="grid_16">').hide().html(html).prependTo('#content').fadeIn();
                            if(data.success==true)
                            {
                                $('#subcategories tr:has(td a[href ="'+href+'"])').fadeOut('slow'); //take away the deleted record 
                            }

                            //Show for 3 seconds
                            setTimeout(function()
                            {
                                $('#flash_message').fadeOut('slow');
                            }, 3000); 
                        }
                    });
                }, 
                "Cancel": function()
                {
                    $(this).dialog("close"); 
                } 
            }
        });
        return false;
    });

});

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
                $('#subcategories tbody tr').remove();
                $('#subcategories tbody').append(html);
                $('#subcategories').trigger("update");
            }
        });
        //return false to prevent normal submit
        return false;
    })
}
); 

$(document).ready(function() 
{ 
    $("#subcategories").tablesorter(); 
} 
); 

</script>

<h2><?php echo __('Subcategory List') ?></h2>

<table id='subcategories' class="tablesorter">
<thead>
<tr>
    <th>Id</th>
    <th>Name</th>
    <th>Category</th>
    <th>Actions</th>
    <th></th>
</tr>
</thead>
<tbody>
<?= $tbody ?>
</tbody>
</table>

