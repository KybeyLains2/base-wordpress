<?php 
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
				'name'		=> 'iThemes Security (formerly Better WP Security)',
				'slug'		=> 'better-wp-security',
				'required'	=> true
			),
			array(
				'name'		=> 'SMTP Mailer',
				'slug'		=> 'smtp-mailer',
				'required'	=> true
			),
			array(
				'name'		=> 'Clean Image Filenames',
				'slug'		=> 'clean-image-filenames',
				'required'	=> true
			),
			array(
				'name'		=> 'Manual Image Crop',
				'slug'		=> 'manual-image-crop',
				'required'	=> false
			),
			array(
				'name'		=> 'WP Migrate DB',
				'slug'		=> 'wp-migrate-db',
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