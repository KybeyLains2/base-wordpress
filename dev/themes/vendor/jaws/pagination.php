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
?>