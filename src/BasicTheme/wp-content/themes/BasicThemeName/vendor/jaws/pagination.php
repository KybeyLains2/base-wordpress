<?php
	/**
	 * Paginação
	 * Código por: http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
	 * http://madlyluv.com/extras/tutoriais/wordpress/paginacao-de-posts-sem-plugin/
	 */
	function post_pagination($pages = '', $range = 4) {
		$showitems = ($range * 2) + 1;

		global $paged;
		if ( empty($paged) ) $paged = 1;

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( !$pages ) {
				$pages = 1;
			}
		}

		if (1 != $pages ) {
			echo '<nav class="post-pagination">';
			echo '<span class="post-pagination-title">Páginas</span>';

			if ( ($paged > 2) && ($paged > $range + 1) && ($showitems < $pages) ) {
				echo '<a href="' . get_pagenum_link($paged - 1) . '" class="post-pagination-link current">&laquo;</a>';
			}

			if ( ($paged > 6) && ($showitems < $pages) ) {
				echo '<a href="' . get_pagenum_link(1) . '" class="post-pagination-link">1</a> <span class="post-pagination-item current">...</span>';
			}

			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( (1 != $pages) && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || ($pages <= $showitems)) ) {
					echo ($paged == $i) ? '<span class="post-pagination-item current">' . $i . '</span>': '<a href="' . get_pagenum_link($i) . '" class="post-pagination-link inactive" >'.$i.'</a>';
				}
			}

			if ( ($paged < $pages - 1) && ($paged + $range - 1 < $pages) && ($showitems < $pages) ) {
				echo '<span class="post-pagination-item current">...</span> <a href="' . get_pagenum_link($pages) . '" class="post-pagination-link">' . $pages . '</a>';
			}

			if ( ($paged < $pages) && ($showitems < $pages) ) {
				echo '<a href="' . get_pagenum_link($paged + 1) . '" class="post-pagination-link current">&raquo;</a>';
			}

			echo '</nav>';
		}
	}

	function infinite_pagination( $pages = '', $args = null ){
		global $paged;
		global $wp_query;

		if ( empty($paged) ) $paged = 1;


		if ( $pages == '' ) {
			$pages = $wp_query->max_num_pages;
			if ( !$pages ) {
				$pages = 1;
			}
		}

		if ( $paged == 1 )
			echo '<div class="js-infinite-load"><img src="' . get_template_directory_uri() . '/assets/img/loader_post.gif" alt="" class="center-block" /></div>';

		$params = array(
			'action'		=> 'get_infinite_posts',
			'found_posts' 	=> $wp_query->found_posts,
			'posts_per_page'=> $wp_query->query_vars['posts_per_page'],
			'offset'		=> $wp_query->query_vars['posts_per_page'] * $paged,
			'query'			=> $wp_query->query,
			'paged'			=> $paged,
			'pages'			=> $pages
		);

		if ( is_null( $wp_query->query ) && !is_null( $args ) ) {
			$params = array_merge( $params, $args );
		}

		if ( $params['pages'] != 1 ) {
			echo '<a href="' . admin_url( 'admin-ajax.php?' . http_build_query( $params ) ) . '" class="post-pagination-link infinite-pagination-link">Próximo &raquo;</a>';
		}
	}

	/*=================================================================*/
	/*                             Ajax                                */
	/*=================================================================*/
	add_action( 'wp_ajax_get_infinite_posts', 'prefix_ajax_get_infinite_posts' );
	add_action( 'wp_ajax_nopriv_get_infinite_posts', 'prefix_ajax_get_infinite_posts' );
	function prefix_ajax_get_infinite_posts() {
		$offset = $_GET['offset'];

		if ( !isset( $_GET['offset'] ) )
			$offset = $_GET['posts_per_page'] * $_GET['paged'];

		$args = array(
			'posts_per_page'	=> $_GET['posts_per_page'],
			'offset'			=> $offset,
			'suppress_filters'	=> false
		);

		$args = array_merge( $args, $_GET['query'] );

		$posts = get_posts( $args );
		$count = 1;

		foreach ($posts as $post) {
			setup_postdata( $GLOBALS['post'] =& $post );
			set_query_var( 'post_count', $count );
			get_template_part( 'templates/content', 'item' );
			$count++;
		}
		set_query_var( 'post_count', 0 );

		wp_reset_postdata();

		if ( $_GET['found_posts'] > ( $_GET['posts_per_page'] + $offset ) ) {
			$_GET['paged'] = $_GET['paged'] + 1;
			$_GET['offset'] = $_GET['posts_per_page'] * $_GET['paged'];
			infinite_pagination( '', $_GET );
		}

		die();
	}

?>
