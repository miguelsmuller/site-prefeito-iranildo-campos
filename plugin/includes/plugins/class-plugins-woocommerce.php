<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Woocommerce
{
	public function __construct() {
		if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))))
		{
			// Add theme support
			add_theme_support( 'woocommerce' );

			// Remove action
			remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));
			if ( !current_user_can( 'manage_options' ) ) {
				remove_action( 'admin_notices', 'woothemes_updater_notice' );
			}

			// Action
			add_action('admin_head', array( &$this, 'change_woocomerce_icon'));

			// Filter
			add_filter('gettext', array( &$this, 'change_woocommerce_name'));
			add_filter('ngettext', array( &$this, 'change_woocommerce_name'));
		}
	}


	/**
	 * Muda o icone padrão do WooCommerce
	 */
	function change_woocomerce_icon(){
	?>
		<style type="text/css" media="screen">
		#adminmenu #toplevel_page_woocommerce .menu-icon-generic div.wp-menu-image:before {
			content: "\f239";
			font-family: dashicons !important;
		}
		</style>
	<?php
	}


	/**
	 * Muda o nome padrão do WooCommerce
	 */
	function change_woocommerce_name( $translated ) {
		$translated = str_replace( 'WooCommerce', 'E-Commerce', $translated );
		return $translated;
	}
}
new Class_Woocommerce();