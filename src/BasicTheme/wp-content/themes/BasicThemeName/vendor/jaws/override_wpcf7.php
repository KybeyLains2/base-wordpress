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
				'custom_wpcf7_select', true );
		}
	}

	function filter_label( $value ){
		if ( strpos( $value, 'label') !== false ) return str_replace( 'label:', '', $value );
	}

	function custom_wpcf7_text( $tag ){
		$label_tag = new WPCF7_Shortcode( $tag );

		$default_class = "form-group form-group-text col-xs-12";

		switch ( $label_tag->type ) {
			case 'tel':
				$default_class.= " col-sm-3";
				break;
			
			default:
				$default_class.= " col-sm-6";
				break;
		}

		$result = array_map( 'filter_label', $label_tag->options );
		$result = array_filter( $result );
		$result = reset( $result );

		$html = '<div class="' . $default_class . '">';
		$html.= 	'<label for="' . $label_tag->get_id_option() . '" class="form-label">' . $result . '</label>';
		$html.= 	wpcf7_text_shortcode_handler( $tag );
		$html.= '</div>';

		return $html;
	}

	function custom_wpcf7_textarea( $tag ){
		return '<div class="form-group form-group-textarea col-xs-12 col-sm-6 col-sm-offset-6">' . wpcf7_textarea_shortcode_handler( $tag ) . '</div>';
	}

	function custom_wpcf7_select( $tag ){
		return '<div class="form-group form-group-select col-xs-12 col-sm-3">' . wpcf7_select_shortcode_handler( $tag ) . '</div>';
	}

	function custom_wpcf7_file( $tag ){
		return '<div class="form-group form-group-file col-xs-12 col-sm-3">' . wpcf7_file_shortcode_handler( $tag ) . '</div>';
	}

	function custom_wpcf_submit( $tag ) {
		$tag = new WPCF7_Shortcode( $tag );

		$class = wpcf7_form_controls_class( $tag->type );
		$class.= ' col-xs-12 btn btn-default';

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );
		$atts['id'] = $tag->get_id_option();
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) )
			$value = __( 'Send', 'contact-form-7' );

		$atts['value'] = $value;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<button %1$s />%2$s<span class="fake-icon fake-icon-arrow-right"></span></button></div><div class="clearfix">', $atts, $value );
		
		return '<div class="clearfix"></div><div class="col-xs-10 col-xs-offset-1 col-sm-3 col-sm-offset-6">' . $html . '</div>';
	}
?>