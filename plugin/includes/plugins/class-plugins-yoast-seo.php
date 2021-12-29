<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Yoast_Seo
{
    public function __construct() {
        add_filter( 'wpseo_metabox_prio', array( &$this, 'wpseo_metabox_prio' ) );
    }

    function wpseo_metabox_prio() {
	    global $post;
	    if ( $post->post_type == 'post' || $post->post_type == 'page' ){
	        return 'high';
	    }else{
	        return 'low';
	    }
	}
}
new Class_Yoast_Seo();