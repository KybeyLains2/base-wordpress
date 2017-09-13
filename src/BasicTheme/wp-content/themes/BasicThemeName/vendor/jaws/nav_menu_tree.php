<?php 
	function build_tree( array &$elements, $parentId = 0 ) {
		$branch = array();
		foreach ( $elements as &$element ) {
			if ( $element->menu_item_parent == $parentId ) {
				$children = build_tree( $elements, $element->ID );
				if ( $children )
					$element->children = $children;

				$branch[$element->ID] = $element;
				unset( $element );
			}
		}
		return $branch;
	}

	function nav_menu_tree( $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );
		return  $items ? build_tree( $items, 0 ) : null;
	}
?>