<?php
class SiteBuilder {
	
	private static $instance = null;
	private $jsonFileName = 'site.json';
	private $siteDescriptor = null;
	private $sitePath = null;
	
	static public function getInstance($sitePath = null)
	{
		if (is_null(static::$instance))
		{
			if (is_null($sitePath))
			{
				echo 'You need to pass the path!';
				die;
			}
			
			static::$instance = new static($sitePath);
		}
		return static::$instance;
	}
	
	function __construct($sitePath)
	{
		$this->sitePath = $sitePath;
		
		$filePath = $this->sitePath . DS . $this->jsonFileName;
		if (file_exists($filePath))
		{
			$fileContent = file_get_contents($filePath);
			$this->siteDescriptor = json_decode($fileContent, true);
			
			$this->build();
		}
		else
		{
			throw new SBException('JSON Descriptor not found', 5000);
			
		}
	}
	
	public static function setAdminStyles() {
	
		if (is_admin())
		{
			wp_enqueue_style('admin-style', get_template_directory_uri(). '/sitebuilder/assets/css/admin.css' );
			wp_enqueue_style('farbtastic-style', get_template_directory_uri(). '/sitebuilder/assets/external/farbtastic/farbtastic.css' );
			wp_enqueue_style('datetimepicker-style', get_template_directory_uri(). '/sitebuilder/assets/external/datetimepicker/jquery-ui-timepicker-addon.min.css' );
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
			
			wp_enqueue_script('datetimepicker-js', get_template_directory_uri() . '/sitebuilder/assets/external/datetimepicker/jquery-ui-timepicker-addon.min.js', ['jquery', 'jquery-ui-datepicker']);
			wp_enqueue_script('datetimepicker-js-i18n', get_template_directory_uri() . '/sitebuilder/assets/external/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.min.js', ['jquery', 'jquery-ui-datepicker']);
			wp_enqueue_script('datetimepicker-js-i18n', get_template_directory_uri() . '/sitebuilder/assets/external/datetimepicker/jquery-ui-sliderAccess.js', ['jquery', 'jquery-ui-datepicker']);
			wp_enqueue_script('farbtastic-js', get_template_directory_uri() . '/sitebuilder/assets/external/farbtastic/farbtastic.js', ['jquery']);
			wp_enqueue_script('admin-js', get_template_directory_uri() . '/sitebuilder/assets/js/min/admin-min.js', ['jquery', 'jquery-ui-sortable']);
			wp_enqueue_script('image-uploader-js', get_template_directory_uri() . '/sitebuilder/assets/js/min/image_uploader-min.js', ['jquery']);
		}
	}
	
	/* Public Methods */
	
	public function get_posts_of_type($type, $options = [])
	{
		$args = array(
			'posts_per_page'	=>	999,
			'offset'			=>	0,
			'orderby'			=>	'date',
			'order'				=>	'DESC',
			'post_type'			=>	$type,
			'post_status'		=>	'publish',
			'suppress_filters'	=>	true
		);
		
		$args = array_merge($args, $options);
		$posts = get_posts($args);
		
		return $posts;
	}
	
	public function init_scripts()
	{
		$menus = $this->siteDescriptor['menus'];
		
		if (!is_null($menus))
		{
			foreach($menus as $menu)
			{
				register_nav_menus($menu);
			}
		}
	}
	
	/* Custom Posts callbacks */
	
	public function posts_columns($columns)
	{
		$custom_post = $this->currentPostTypeDescriptor();
		if (!is_null($custom_post))
		{
			$custom_post_columns = $custom_post['custom_columns'];
			if (is_array($custom_post_columns))
			{
				$columns = array_merge($columns, $custom_post_columns);
			}
		}
		
		return $columns;
	}
	
	public function posts_columns_data($column, $post_id)
	{
		$custom_post = $this->currentPostTypeDescriptor();
		if (!is_null($custom_post))
		{
			$values = get_post_custom_values($column, $post_id);
			if (is_array($values))
			{
				echo $values[0];
			}
		}
		
	}
	
	public function createCustomPostsMetaBoxes()
	{
		$custom_posts = $this->siteDescriptor['custom_posts'];
		foreach ($custom_posts as $custom_post)
		{
			if (get_post_type() == $custom_post['id'])
			{
				foreach ($custom_post['meta_boxes'] as $meta_box)
				{
					add_meta_box(
						$meta_box['id'],
						$meta_box['title'],
						[$this, 'createMetabox'],
						$custom_post['id'],
						$meta_box['context'],
						$meta_box['priority']
					);
				}
			}
		}
	}
	
	public function doSaveCustomPost()
	{
		global $post;
		if (isset($post))
		{
			$postId = $post->ID;
		}
		
		$custom_post = $this->currentPostTypeDescriptor();
		if (!is_null($custom_post))
		{
			foreach ($custom_post['meta_boxes'] as $meta_box)
			{
				foreach ($meta_box['descriptors'] as $descriptor)
				{
					$slug = $descriptor['slug'];
				
					if (isset($_POST[$slug]))
					{
						update_post_meta($postId, $slug, $_POST[$slug]);
					}
				}
			}
		}
	}
	
	public function createMetabox($post, $metabox)
	{
		$descriptors = null;
		
		$custom_posts = $this->siteDescriptor['custom_posts'];
		foreach ($custom_posts as $custom_post)
		{
			if ($custom_post['id'] == $post->post_type)
			{
				foreach($custom_post['meta_boxes'] as $meta_box)
				{
					if ($meta_box['id'] == $metabox['id'])
					{
						$descriptors = $meta_box['descriptors'];
						
						break;
					}
				}
			}
			
			if (!is_null($descriptors))
			{
				break;
			}
		}
		
		
		if (!is_null($descriptors))
		{
			SiteBuilderFormHelper::createMetaBoxInputsFromDescriptors($descriptors);
		}
		
		// debug($post);
		// debug($metabox);
	}
	
	/* Private Methods */
	
	private function currentPostTypeDescriptor()
	{
		$post_type_descriptor = null;
		$custom_posts = $this->siteDescriptor['custom_posts'];
		foreach ($custom_posts as $custom_post)
		{
			if (get_post_type() == $custom_post['id'])
			{
				$post_type_descriptor = $custom_post;
				break;
			}
		}
		
		return $post_type_descriptor;
	}
	
	private function build()
	{
		$this->createCustomPosts();
		$this->createCustomTaxonomies();
		
		add_action('add_meta_boxes', [$this, 'createCustomPostsMetaBoxes']);
		add_action('save_post', [$this, 'doSaveCustomPost']);
		add_action('admin_enqueue_scripts', [__CLASS__, 'setAdminStyles']);
		
		add_action('manage_posts_custom_column', [$this, 'posts_columns_data'], 10, 2);
		add_filter('manage_posts_columns', [$this, 'posts_columns']);
		
		add_action('init', [$this, 'init_scripts']);
	}
	
	private function createCustomPosts()
	{
		$custom_posts = $this->siteDescriptor['custom_posts'];
		
		foreach ($custom_posts as $custom_post)
		{
			register_post_type($custom_post['id'], $custom_post);
		}
	}
	
	private function createCustomTaxonomies()
	{
		$custom_taxonomies = $this->siteDescriptor['custom_taxonomies'];
		
		foreach ($custom_taxonomies as $custom_taxonomy)
		{
			register_taxonomy($custom_taxonomy['id'], $custom_taxonomy['custom_posts'], $custom_taxonomy);
		}
	}
}
?>
