var file_frame;	//	For the Image picker

jQuery(document).ready(function ($) {
	
	//	Single Image Selection

	$('.image-uploader .button-upload').on('click', function(event) {
	
		event.preventDefault();
	
		var inputId = $(this).data('inputid');
		var formfield = $('#'+inputId);
		var preview = $('#'+$(this).data('imageid'));
		var removeButton = $('#'+inputId+'_remove-button');
	
		if (file_frame)
		{
			file_frame.open();
			return false;
		}
	
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false
		});
	
		file_frame.on('select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
		
			formfield.val(attachment.id);
			preview.attr('src', attachment.sizes.thumbnail.url);
			removeButton.show();
		
			file_frame = null;
		});
	
		file_frame.open();
	
	});
	$('.image-uploader .button-delete').on('click', function(event) {
	
		event.preventDefault();
	
		var id = $(this).data('id');
		$('#'+id).val('');
		$('#'+id+'_image').attr('src', '');
		$(this).hide();
	});
	
	//	Multiple Image Selection
	
	$('.multiple-image-uploader .button-upload').on('click', function(event) {
	
		event.preventDefault();
	
		var inputId = $(this).data('inputid');
		var galleryId = $('#'+'gallery-'+inputId);
		var formfield = $('#'+inputId);
		// var preview = $('#'+$(this).data('imageid'));
		// var removeButton = $('#'+inputId+'_remove-button');
	
		if (file_frame)
		{
			file_frame.open();
			return false;
		}
	
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery(this).data('uploader_title'),
			button: {
				text: jQuery(this).data('uploader_button_text'),
			},
			multiple: true
		});
	
		file_frame.on('select', function() {
			
			galleryId.empty();
			
			var inputValue = new Array();
			
			// console.log(file_frame.state().get('selection').toJSON());
			
			$(file_frame.state().get('selection').toJSON()).each(function(index, item) {
				
				var div = $('<div class="gallery-item" data-id="'+item.id+'"></div>');
				var anchor = $('<a href="javascript:;" data-inputid="'+inputId+'" data-id="'+item.id+'"><span class="dashicons dashicons-trash"></span></a>')
				var image = $('<img width="100" src="' + item.sizes.thumbnail.url + '">');
				
				div.append(image);
				div.append(anchor);
				galleryId.append(div);
				
				inputValue[index] = item.id;
			});
			
			formfield.val(inputValue);
		
			file_frame = null;
		});
		file_frame.on('open',function() {
			
			//	Auto-selection of the images
			
			var selection = file_frame.state().get('selection');
			ids = formfield.val().split(',');
			
			ids.forEach(function(id) {
				var attachment = wp.media.attachment(id);
				attachment.fetch();
				selection.add(attachment ? [ attachment ] : []);
			});
		});
		
		file_frame.open();
	
	});
	$('body').on('click', '.multiple-image-uploader .gallery-item a', function(event) {
		
		event.preventDefault();
		
		//	Remove tapped item
		
		var imageId = $(this).data('id');
		var inputId = $(this).data('inputid');
		var formfield = $('#'+inputId);
		var ids = $(formfield.val().split(','));
		
		ids.each(function(index, item){
			if (item == imageId)
			{
				ids.splice(index, 1);
				
				return;
			}
		});
		
		formfield.val(ids.toArray());
		
		$(this).parent().remove();
		
	});
	
});