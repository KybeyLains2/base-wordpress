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

	function tag_options ( $v ){
		$value = '';

		if (
			stripos( $v, 'class:col' ) === false &&
			stripos( $v, 'label:' ) === false &&
			stripos( $v, 'placeholder' ) === false
		) {
			$value = $v;
		}
		return $value;
	}

	function container_class ( $v ){
		$value = '';

		if ( stripos( $v, 'class:col' ) !== false ) {
			$value = str_replace( 'class:', '', $v );
		}
		return $value;
	}

	function container_class_submit ( $v ){
		$value = '';

		if ( stripos( $v, 'col' ) !== false ) {
			$value = $v;
		}
		return $value;
	}

	function button_class ( $v ){
		$value = '';

		if ( stripos( $v, 'col' ) === false ) {
			$value = $v;
		}
		return $value;
	}

	function label_tag( $label_tag, $tag, $type = 'text' ){
		$wpcf7_version = WPCF7_VERSION;
		$wpcf7_version = floatval( $wpcf7_version );
		$label_result = '';

		$function = 'wpcf7_' . $type . '_shortcode_handler';

		if ( $wpcf7_version >= 4.6 ) 
			$function = 'wpcf7_' . $type . '_form_tag_handler';

		$default_class = "form-group form-group-" . $type . " col-xs-12";

		// Attribute args
		$tag_options = array_map( 'tag_options', $tag['options'] );
		$container_class = array_map( 'container_class', $tag['options']);
		$container_class = array_filter( $container_class );
		$default_class.= ' ' . implode( ' ', $container_class );
		// /Attribute args

		$tag['options'] = array_filter( $tag_options );
		// $tag['options'][] = 'class:' . $type . '-' . $label_tag->get_id_option();

		if ( $type != 'select' ) 
			unset( $tag['values'] );


		$html = '<div class="' . $default_class . '">';
		$html.= 	$function( $tag );
		$html.= '</div>';

		if ( $label_tag && $label_tag->labels && $type != 'checkbox' ) {
			if ( $label_result == '' ) {
				$label_result = reset( $label_tag->labels );

				$search = array_search( 'first_is_label', $label_tag->options );

				// if ( $search !== false && $type == 'select' ) {
				if ( $type == 'select' ) {
					if ( is_array($tag) ) {
						unset( $tag['values'][0] );
						unset( $tag['raw_values'][0] );
						unset( $tag['labels'][0] );
					}elseif ( is_object($tag) ){
						unset( $tag->values[0] );
						unset( $tag->raw_values[0] );
						unset( $tag->labels[0] );
					}
				}else{
					$tag['values'] = array();
				}
			}

			if ( $label_result ) {
				$html = '<div class="' . $default_class . '">';
				$html.= 	'<label for="' . $label_tag->get_id_option() . '" class="form-label">' . $label_result . '</label>';
				$html.= 	$function( $tag );
				$html.= '</div>';
			}
		}

		return $html;
	}

	function insert_id( $tag ){
		if ( !preg_grep( '/id:/', $tag['options'] ) ) {
			$tag_options[] = 'id:' . $tag['name'] . '_' . rand();
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
		$tag = insert_id( $tag );
		$label_tag = new WPCF7_Shortcode( $tag );

		return label_tag($label_tag, $tag, 'select');
	}

	function custom_wpcf7_file( $tag ){
		$tag = insert_id( $tag );
		$label_tag = new WPCF7_Shortcode( $tag );

		return label_tag($label_tag, $tag, 'file');
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
