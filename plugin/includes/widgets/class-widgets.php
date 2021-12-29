<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Widgets
{
    /**
	 * Initialize class
	 */
    public function __construct() {
    	// Actions
        add_action('widgets_init', array( &$this, 'widgets_init' ));

        // Filters
        add_filter('widget_text', array( &$this, 'widget_text' ));
    }


    /**
     * Widgets init
     */
    function widgets_init() {
    	$widget_facebook = TRUE;
		if ( apply_filters( 'widget_facebook', $widget_facebook )  == TRUE ) {
			include_once 'class-widget-facebook.php';
			register_widget( 'Class_Widget_Facebook' );
		}

		$widget_see_more = TRUE;
		if ( apply_filters( 'widget_see_more', $widget_see_more )  == TRUE ) {
			include_once 'class-widget-see-more.php';
			register_widget( 'Class_Widget_See_More' );
		}

		$widget_image = TRUE;
		if ( apply_filters( 'widget_image', $widget_image )  == TRUE ) {
			include_once 'class-widget-image.php';
			register_widget( 'Class_Widget_Image' );
		}

		$widget_author = TRUE;
		if ( apply_filters( 'widget_author', $widget_author )  == TRUE ) {
			include_once 'class-widget-author.php';
			register_widget( 'Class_Widget_Author' );
		}
    }


    /**
	 * PRECISA DE UM COMENTÁRIO
	 */
    function widget_text($text) {
		$text = str_replace('<style', '', $text);
		$text = str_replace('</style', '', $text);
		return $text;
	}
}
new Class_Widgets();