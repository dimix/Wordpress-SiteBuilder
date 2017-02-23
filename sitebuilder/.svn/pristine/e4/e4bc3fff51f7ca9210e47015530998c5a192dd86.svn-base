<?php
class SiteBuilderFormHelper {
	
	public static function createMetaBoxInputsFromDescriptors($descriptors) {
		global $post;
		$customData = get_post_custom($post->ID);
		
		echo '<div class="admin-input-box">';
		
		foreach ($descriptors as $descriptor)
		{
			$type = $descriptor['type'];
			$value = (isset($descriptor['default']) ? $descriptor['default']: '');
			$label = $descriptor['label'];
			$slug = $descriptor['slug'];
			
			if (isset($customData[$slug]) && count($customData[$slug]))
			{
				$value = $customData[$slug][0];
			}
			
			switch ($type)
			{
				case 'text':
				{
					echo SiteBuilderFormHelper::textInput($slug, $value, $label);
					break;
				}
				case 'number':
				{
					echo SiteBuilderFormHelper::numberInput($slug, $value, $label);
					break;
				}
				case 'checkbox':
				{
					echo SiteBuilderFormHelper::checkboxInput($slug, $value, $label);
					break;
				}
				case 'colorpicker':
				{
					echo SiteBuilderFormHelper::colorPickerInput($slug, $value, $label);
					break;
				}
				case 'select':
				{
					$values = $descriptor['values'];
				
					echo SiteBuilderFormHelper::select($slug, $values, $value);
					break;
				}
				case 'datetime':
				{
					echo SiteBuilderFormHelper::date($slug, $value, $label, true, true);
					break;
				}
				case 'time':
				{
					echo SiteBuilderFormHelper::date($slug, $value, $label, false, true);
					break;
				}
				case 'date':
				{
					echo SiteBuilderFormHelper::date($slug, $value, $label, true, false);
					break;
				}
				case 'texteditor':
				{
					SiteBuilderFormHelper::textEditor($slug, $value);
					break;
				}
				case 'imageupload':
				{
					$meta = get_post_meta($post->ID, $slug, true);
					$imageURL = '';
					if ($meta)
					{
						$image = wp_get_attachment_image_src($meta, 'thumbnail');
						$imageURL = $image[0];
					}
				
					echo SiteBuilderFormHelper::imageUpload($slug, $value, $imageURL, $label);
					
					break;
				}
				case 'multiple-imageupload':
				{
					echo SiteBuilderFormHelper::multipleImageUpload($slug, $value, $label);
					
					break;
				}
				case 'productbox':
				{
					$limit = $descriptor['limit'];
				
					echo SiteBuilderFormHelper::productBox($slug, $value, $label, $limit);
					
					break;
				}
			}
		}
		
		echo '</div>';
	}
	
	private static function label($label) {
		$html = '<div class="admin-label"><label>'.$label.'</label></div>';
		
		return $html;
	}
	
	private static function textInput($name, $value, $label= null) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<div class="admin-input"><input class="i18n-multilingual" type="text" name="'.$name.'" value="'.$value.'"></div>';
	
		return $html;
	}
	
	private static function numberInput($name, $value, $label= null) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<div class="admin-input"><input class="i18n-multilingual" type="number" name="'.$name.'" value="'.$value.'"></div>';
	
		return $html;
	}
	
	private static function checkboxInput($name, $checked, $label= null) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<div class="admin-input"><input type="checkbox" name="'.$name.'" value="1" '.($checked ? 'checked="checked"' : '').'></div>';
		
		return $html;
	}
	
	private static function colorPickerInput($name, $value, $label= null) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<div class="admin-input">
				<input name="'.$name.'" id="'.$name.'" value="'.$value.'">
				<div class="colorpicker" data-pickerid="'.$name.'"></div>
			</div>';
		
		return $html;
	}
	
	private static function select($name, $values, $selectedValue, $label = null) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<select id="'.$name.'" name="'.$name.'" class="postform">';
		
		foreach($values as $key => $value)
		{
			$html .= '<option value="'.$key.'" ';
			if ($selectedValue == $key) { $html .= 'selected="selected"'; }
			$html .= '>'.$value.'</option>';
		}
		
		$html .= '</select>';
		
		return $html;
	}
	
	private static function date($slug, $value, $label, $useDate = true, $useTime = false) {
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$class = 'datetimepicker';
		if (!$useDate)
		{
			$class = 'timepicker';
		}
		else if (!$useTime)
		{
			$class = 'datepicker';
		}
		
		$html .= '<div class="admin-input"><input type="text" name="'.$slug.'" id="date-'.$slug.'" value="'.$value.'" class="'.$class.'"></div>';
		
		return $html;
	}
	
	private static function textEditor($inputName, $value) {
		wp_editor( $value, $inputName );
	}
	
	private static function imageUpload($name, $value, $imageURL, $label= null) {
		global $post;
		$imageID = $name.'_image';
		$meta = get_post_meta($post->ID, $name, true);
		
		$image = '';
		if ($meta)
		{
			$image = wp_get_attachment_image_src($meta, 'thumbnail');
			$image = $image[0];
		}
		
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$removeButtonStyle = 'display:none';
		if ($imageURL != '')
		{
			$removeButtonStyle = 'display:inline';
		}
		
		$html .= '<div class="admin-input image-uploader">
				<div class="pull-left">
					
					<button class="button button-upload button-primary" data-inputid="'.$name.'" data-imageid="'.$imageID.'">'.__('Choose Image', 'sitebuilder').'</button>
					<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'">
				</div>
				<div class="pull-right">
					<img class="admin-product-image" src="'.$imageURL.'" id="'.$imageID.'">
					<br>
					<button id="'.$name.'_remove-button" style="'.$removeButtonStyle.'" class="m-t-10 button button-delete" data-id="'.$name.'">'.__('Remove', 'sitebuilder').'</button>
				</div>
			</div><hr><br>';
			
		return $html;
	}
	
	private static function multipleImageUpload($name, $value, $label= null) {
		
		global $post;
		
		$html = '';
		if ($label != null) {
			$html .= SiteBuilderFormHelper::label($label);
		}
		
		$html .= '<div class="admin-input multiple-image-uploader">
				<button class="button button-upload button-primary" data-inputid="'.$name.'">'.__('Choose Images', 'sitebuilder').'</button>
				<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'">
				
				<div class="gallery" id="gallery-'.$name.'">';
		
		$ids = explode(',', $value);
		foreach($ids as $id)
		{
			$image = wp_get_attachment_image_src($id, 'thumbnail');
			$imageURL = $image[0];
			
			$html .= '<div class="gallery-item" data-id="'.$id.'">';
			$html .= '<a href="javascript:;" data-inputid="'.$name.'" data-id="'.$id.'"><span class="dashicons dashicons-trash"></span></a>';
			$html .= '<img width="100" src="'.$image[0].'">';
			$html .= '</div>';
			
		}
		
		$html .= '</div>
			</div><hr><br>';
		
		return $html;
		
	}
}
?>
