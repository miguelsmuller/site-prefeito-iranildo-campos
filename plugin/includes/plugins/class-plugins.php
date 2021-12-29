<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Plugins
{
    public function __construct() {
        if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) ){
			include_once 'class-plugins-woocommerce.php';
		}

		if ( in_array('wordpress-seo/wp-seo.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) ){
			include_once 'class-plugins-yoast-seo.php';
		}
    }
}
new Class_Plugins();