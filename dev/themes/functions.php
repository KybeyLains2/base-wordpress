<?php 
	/*=================================================================*/
	/*                       Wordpress Overwrite                       */
	/*=================================================================*/

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );

	// add_image_size( name_size, width, height, crop );
	add_image_size( 'example_size', 500, 300 );

	register_nav_menus( array(
		'header_menu' => 'Header Menu'
	) );

	function theme_name_scripts() {
		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' );
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
		
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '1.0.0' );
		wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );
		wp_enqueue_style( 'plugins', get_template_directory_uri() . '/assets/css/plugins.css', array(), '1.0.0' );

		wp_enqueue_script( 'plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), '1.0.0', true );
		wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js?', array('jquery'), '1.0.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

	/*=================================================================*/
	/*                        Cuztom Overwrite                         */
	/*=================================================================*/
	include 'vendor/cuztom/cuztom.php';

	/*=================================================================*/
	/*                         Jaws Overwrite                          */
	/*=================================================================*/
	// include locate_template( 'vendor/jaws/simple_html_dom.php' );
	include 'vendor/jaws/disable_emojis.php';
	include 'vendor/jaws/pagination.php';
	// include 'vendor/jaws/override_wpcf7.php';
	
	include 'vendor/schema-breadcrumbs/class.schema_breadcrumbs.php';
	function fix_yoast_breadcrumb(){
		// only instantiate the class if Yoast breadcrumbs are used
		if( function_exists( 'yoast_breadcrumb' ) ) {
			Schema_Breadcrumbs::instance();
		}
	}
	add_action( 'after_setup_theme', 'fix_yoast_breadcrumb' );

	/**
	 * Include the TGM_Plugin_Activation class.
	 */
	require_once dirname( __FILE__ ) . '/vendor/TGM-Plugin-Activation/class-tgm-plugin-activation.php';

	add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );

	/**
	 * Register the required plugins for this theme.
	 *
	 * This function is hooked into tgmpa_init, which is fired within the
	 * TGM_Plugin_Activation class constructor.
	 */
	function my_theme_register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'		=> 'Yoast SEO',
				'slug'		=> 'wordpress-seo',
				'required'	=> true
			),
			array(
				'name'		=> 'Regenerate Thumbnails',
				'slug'		=> 'regenerate-thumbnails',
				'required'	=> true
			),
			array(
				'name'		=> 'Google Analytics Dashboard for WP',
				'slug'		=> 'google-analytics-dashboard-for-wp',
				'required'	=> true
			),
			array(
				'name'		=> 'Contact Form 7',
				'slug'		=> 'contact-form-7',
				'required'	=> true
			),
			array(
				'name'		=> 'Wordfence Security',
				'slug'		=> 'wordfence',
				'required'	=> true
			),
			array(
				'name'		=> 'Manual Image Crop',
				'slug'		=> 'manual-image-crop',
				'required'	=> false
			),
			array(
				'name'		=> 'Theme Preview',
				'slug'		=> 'theme-preview',
				'required'	=> false
			)
		);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                    // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			/*
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
				'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
				// <snip>...</snip>
				'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
			*/
		);

		tgmpa( $plugins, $config );
	}
?>