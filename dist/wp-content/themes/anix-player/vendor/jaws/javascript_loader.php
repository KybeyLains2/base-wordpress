<?php 
	// http://stackoverflow.com/a/20672324
	function add_async_forscript( $url ) {
	    if ( strpos( $url, '#asyncload' ) === false )
	        return $url;
	    else if ( is_admin() )
	        return str_replace( '#asyncload', '', $url );
	    else
	        return str_replace( '#asyncload', '', $url ) . "' async defer"; 
	}
	add_filter( 'clean_url', 'add_async_forscript', 11, 1 );
?>