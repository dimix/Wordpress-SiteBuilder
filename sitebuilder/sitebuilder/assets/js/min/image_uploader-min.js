var file_frame;jQuery(document).ready(function($){$(".image-uploader .button-upload").on("click",function(e){e.preventDefault();var t=$(this).data("inputid"),a=$("#"+t),i=$("#"+$(this).data("imageid")),l=$("#"+t+"_remove-button");return file_frame?(file_frame.open(),!1):(file_frame=wp.media.frames.file_frame=wp.media({title:jQuery(this).data("uploader_title"),button:{text:jQuery(this).data("uploader_button_text")},multiple:!1}),file_frame.on("select",function(){var e=file_frame.state().get("selection").first().toJSON();a.val(e.id),i.attr("src",e.sizes.thumbnail.url),l.show(),file_frame=null}),void file_frame.open())}),$(".image-uploader .button-delete").on("click",function(e){e.preventDefault();var t=$(this).data("id");$("#"+t).val(""),$("#"+t+"_image").attr("src",""),$(this).hide()}),$(".multiple-image-uploader .button-upload").on("click",function(e){e.preventDefault();var t=$(this).data("inputid"),a=$("#gallery-"+t),i=$("#"+t);return file_frame?(file_frame.open(),!1):(file_frame=wp.media.frames.file_frame=wp.media({title:jQuery(this).data("uploader_title"),button:{text:jQuery(this).data("uploader_button_text")},multiple:!0}),file_frame.on("select",function(){a.empty();var e=new Array;$(file_frame.state().get("selection").toJSON()).each(function(i,l){var r=$('<div class="gallery-item" data-id="'+l.id+'"></div>'),n=$('<a href="javascript:;" data-inputid="'+t+'" data-id="'+l.id+'"><span class="dashicons dashicons-trash"></span></a>'),d=$('<img width="100" src="'+l.sizes.thumbnail.url+'">');r.append(d),r.append(n),a.append(r),e[i]=l.id}),i.val(e),file_frame=null}),file_frame.on("open",function(){var e=file_frame.state().get("selection");ids=i.val().split(","),ids.forEach(function(t){var a=wp.media.attachment(t);a.fetch(),e.add(a?[a]:[])})}),void file_frame.open())}),$("body").on("click",".multiple-image-uploader .gallery-item a",function(e){e.preventDefault();var t=$(this).data("id"),a=$(this).data("inputid"),i=$("#"+a),l=$(i.val().split(","));l.each(function(e,a){return a==t?void l.splice(e,1):void 0}),i.val(l.toArray()),$(this).parent().remove()})});