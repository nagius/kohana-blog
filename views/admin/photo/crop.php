<script language="Javascript">
    jQuery(function() {
        jQuery('#cropbox').Jcrop({
		onChange: showPreview,
		onSelect: updateCoords,
		aspectRatio: 1
	});
    });


function showPreview(coords)
{
	// img_width and img_height are taken in the view from Image object
	
	var rx = 100 / coords.w;
	var ry = 100 / coords.h;

	$('#preview').css({
		width: Math.round(rx * img_width) + 'px',
		height: Math.round(ry * img_height) + 'px',
		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
		marginTop: '-' + Math.round(ry * coords.y) + 'px'
	});
};

function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
};

function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
};

</script>
<script language="Javascript">
    var img_width=<?= $img->width ?>;
    var img_height=<?= $img->height ?>;
</script>



<h2><?php echo $legend; ?></h2>
<?php echo Form::open(); ?> 

<?php echo isset($errors['name']) ? '<p class="error">'.$errors['name'].'</p>' : ''; ?> 

<p>

<div style="overflow: hidden; width: 100px; height: 100px; margin-left: 5px; margin-bottom: 5px;">
<?= HTML::image($photo->path, array('id' => 'preview')) ?>
</div>
<?= HTML::image($photo->path, array('id' => 'cropbox')) ?>

<?= Form::open(NULL, array('onsubmit' => "return checkCoords();")) ?>
            <input type="hidden" id="x" name="x" />
            <input type="hidden" id="y" name="y" />
            <input type="hidden" id="w" name="w" />
            <input type="hidden" id="h" name="h" />

</p>

<p class="submit">
	<?php echo Form::submit('submit', $submit); ?> 
</p>
<?php echo Form::close(); ?> 
