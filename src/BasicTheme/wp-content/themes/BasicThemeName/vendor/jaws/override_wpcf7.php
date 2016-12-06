<?php
	add_action( 'init', 'custom_wpcf7_shortcode_text', 10 );
	function custom_wpcf7_shortcode_text() {

		if ( function_exists( 'wpcf7_remove_shortcode' ) ) {
			wpcf7_remove_shortcode( 'submit' );
			wpcf7_add_shortcode( 'submit', 'custom_wpcf_submit' );

			wpcf7_remove_shortcode( 'text' );
			wpcf7_remove_shortcode( 'text*' );
			wpcf7_remove_shortcode( 'email' );
			wpcf7_remove_shortcode( 'email*' );
			wpcf7_remove_shortcode( 'url' );
			wpcf7_remove_shortcode( 'url*' );
			wpcf7_remove_shortcode( 'tel' );
			wpcf7_remove_shortcode( 'tel*' );

			wpcf7_add_shortcode(
				array( 'text', 'text*', 'email', 'email*', 'url', 'url*', 'tel', 'tel*' ),
				'custom_wpcf7_text', true );

			wpcf7_remove_shortcode( 'textarea' );
			wpcf7_remove_shortcode( 'textarea*' );
			wpcf7_add_shortcode(
				array( 'textarea', 'textarea*' ),
				'custom_wpcf7_textarea', true );

			wpcf7_remove_shortcode( 'select' );
			wpcf7_remove_shortcode( 'select*' );
			wpcf7_add_shortcode(
				array( 'select', 'select*' ),
				'custom_wpcf7_select', true );

			wpcf7_remove_shortcode( 'file' );
			wpcf7_remove_shortcode( 'file*' );
			wpcf7_add_shortcode(
				array( 'file', 'file*' ),
				'custom_wpcf7_file', true );
		}
	}

	function label_tag( $label_tag, $tag, $type = 'text' ){
		$wpcf7_version = WPCF7_VERSION;
		$wpcf7_version = floatval( $wpcf7_version );

		$function = 'wpcf7_' . $type . '_shortcode_handler';

		if ( $wpcf7_version >= 4.6 ) 
			$function = 'wpcf7_' . $type . '_form_tag_handler';

		$default_class = "form-group form-group-" . $type . " col-xs-12";
		$placeholder_index = array_search( 'placeholder', $tag['options'] );

		// Attribute args
		$tag_options = array_map( function( $v ){
			$value = '';

			if (
				stripos( $v, 'class:col' ) === false &&
				stripos( $v, 'placeholder' ) === false
			) {
				$value = $v;
			}
			return $value;
		}, $tag['options'] );
		// /Attribute args

		$container_class = array_map( function( $v ){
			$value = '';

			if ( stripos( $v, 'class:col' ) !== false ) {
				$value = str_replace( 'class:', '', $v );
			}
			return $value;
		}, $tag['options']);
		$container_class = array_filter( $container_class );
		$default_class.= ' ' . implode( ' ', $container_class );

		$tag['options'] = array_filter( $tag_options );

		unset( $tag['values'] );

		$html = '<div class="' . $default_class . '">';
		$html.= 	$function( $tag );
		$html.= '</div>';

		if ( $label_tag->labels ) {
			$label_result = reset( $label_tag->labels );

			$html = '<div class="' . $default_class . '">';
			$html.= 	'<label for="' . $label_tag->get_id_option() . '" class="form-label">' . $label_result . '</label>';
			$html.= 	$function( $tag );
			$html.= '</div>';
		}

		return $html;
	}

	function insert_id( $tag ){
		if ( !preg_grep( '/id:/', $tag['options'] ) ) {
			$tag_options[] = 'id:' . $tag['name'];
			$tag['options'] = array_merge( $tag['options'], $tag_options );
		}
		return $tag;
	}

	function custom_wpcf7_text( $tag ){
		$tag = insert_id( $tag );
		$label_tag = new WPCF7_Shortcode( $tag );

		return label_tag($label_tag, $tag, 'text');
	}

	function custom_wpcf7_textarea( $tag ){
		$tag = insert_id( $tag );
		$label_tag = new WPCF7_Shortcode( $tag );

		return label_tag($label_tag, $tag, 'textarea');
	}

	function custom_wpcf7_select( $tag ){
		return '<div class="form-group form-group-select col-xs-12 col-sm-3">' . wpcf7_select_shortcode_handler( $tag ) . '</div>';
	}

	function custom_wpcf7_file( $tag ){
		return '<div class="form-group form-group-file col-xs-12 col-sm-3">' . wpcf7_file_shortcode_handler( $tag ) . '</div>';
	}

	function custom_wpcf_submit( $tag ) {
		$tag = new WPCF7_Shortcode( $tag );

		$class = 'form-group form-group-submit';
		$class = $tag->get_class_option( $class );

		$atts = array();

		$atts['class'] = wpcf7_form_controls_class( $tag->type );
		$atts['id'] = $tag->get_id_option();
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) )
			$value = __( 'Send', 'contact-form-7' );

		$atts['value'] = $value;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<button %1$s />%2$s</button>', $atts, $value );

		return sprintf( '<div class="%1$s">%2$s</div>', $class, $html );
	}

	// add_filter( 'wpcf7_ajax_loader', 'filter_wpcf7', 10, 1 );
	function filter_wpcf7( $url ){
		return get_template_directory_uri() . '/assets/img/plugins/loader_form.gif';
	}
?>
