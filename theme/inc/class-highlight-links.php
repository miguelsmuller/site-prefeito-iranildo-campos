<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Highlight_Links extends Walker_Nav_Menu {
	 function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
	    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

	    $item_icon = '<span class="'. $item->description .'"></span>' ;
	    $item_id = 'id=nav-menu-item-' . $item->ID;

	    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	    $attributes .= ' class="highlight-links-item menu-link ' . $class_names . '"';

	    $item_output = sprintf( '%1$s<a %2$s%3$s>%4$s%5$s%6$s%7$s</a>%8$s',
	        $args->before,
	        $item_id,
	        $attributes,
	        $args->link_before,
	        $item_icon,
	        apply_filters( 'the_title', $item->title, $item->ID ),
	        $args->link_after,
	        $args->after
	    );

	    // build html
	    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "\n";
	}
}
