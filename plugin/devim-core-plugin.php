<?php
/*
 * Plugin Name: - Devim Core Plugin
 * Plugin URI: http://www.devim.com.br/
 * Description: This plugin is indispensable for the functioning of the site. KEEP CAREFULLY.
 * Version: 1.0.0
 * Author: Devim - Agência Web
 * Author URI: http://www.devim.com.br/
 * License: GPLv3 License
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Devim_Plugin' ) ) :

/**
 * Plugin main class
 */
class Devim_Plugin
{
	/**
	 * @var object
	 */
	private static $instance = null;


	/**
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	/**
	 * Initialize the plugin actions.
	 */
	private function __construct() {
		// LOADING NATIVE FUNCTIONS
		include_once 'includes/inc-functions.php';

		// LOAD BASE CLASS
		include_once 'includes/class-main.php';

		// LOAD EXTRA CLASS
		include_once 'includes/login/class-login.php';
		include_once 'includes/menu/class-menu.php';
		include_once 'includes/navigator/class-navigator.php';
		include_once 'includes/plugins/class-plugins.php';
		include_once 'includes/post-page/class-post-page.php';
		include_once 'includes/shortcode/class-shortcode.php';
		include_once 'includes/user/class-user.php';
		include_once 'includes/widgets/class-widgets.php';
		include_once 'includes/customize/class-customize.php';

		// LOAD ADDITIONAL CLASS
		add_action( 'init', array( &$this , 'init'), 0);
	}

	/**
	 * Function description
	 */
	function init() {
		$class_cpt_arquivo = FALSE;
		if ( apply_filters( 'class_cpt_arquivo', $class_cpt_arquivo )  == TRUE ) {
			include_once 'includes/cpt-arquivo/class-cpt-arquivo.php';
		}

		$class_cpt_evento = FALSE;
		if ( apply_filters( 'class_cpt_evento', $class_cpt_evento )  == TRUE ) {
			include_once 'includes/cpt-evento/class-cpt-evento.php';
		}

		$class_cpt_slide = FALSE;
		if ( apply_filters( 'class_cpt_slide', $class_cpt_slide )  == TRUE ) {
			include_once 'includes/cpt-slide/class-cpt-slide.php';
		}

		$class_dashboard = FALSE;
		if ( apply_filters( 'class_dashboard', $class_dashboard )  == TRUE ) {
			include_once 'includes/dashboard/class-dashboard.php';
		}

		$class_mailchimp = FALSE;
		if ( apply_filters( 'class_mailchimp', $class_mailchimp )  == TRUE ) {
			include_once 'includes/mailchimp/class-mailchimp.php';
		}
	}
}
add_action( 'plugins_loaded', array( 'Devim_Plugin', 'get_instance' ), 0 );

endif;
