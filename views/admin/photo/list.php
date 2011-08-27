<script type="text/javascript" src="/media/js/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{               
	$('#photos a.delete').click(function()
	{
		var href = $(this).attr("href"); 
		var $dialog = $('<div></div>').html('Delete this photo ?').dialog(
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
								$('#photos tr:has(td a[href ="'+href+'"])').fadeOut('slow'); //take away the deleted record 
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
				$('#photos tbody tr').remove();
				$('#photos tbody').append(html);
				$('#photos').trigger("update");
			}
		});
		//return false to prevent normal submit
		return false;
	})
}
); 

$(document).ready(function() 
{ 
	$("#photos").tablesorter(); 
} 
); 

</script>



<h2><?php echo __('Photo List') ?></h2>

<table id='photos' class="tablesorter">
<thead>
<tr>
    <th>Id</th>
    <th>Title</th>
    <th>Subtitle</th>
	<th>Actions</th>
	<th></th>
	<th></th>
</tr>
</thead>
<tbody>
<?= $tbody ?>
</tbody>
</table>


