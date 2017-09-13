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
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '1.0.0' );
		wp_enqueue_style( 'plugins', get_template_directory_uri() . '/assets/css/plugins.css', array(), '1.0.0' );
		wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );

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
	include 'vendor/jaws/nav_menu_tree.php';
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
	include 'vendor/jaws/tgm_plugin_activation.php';
?>
