<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Dashboard
{
	/**
	 * Construtor da Classe
	 */
	public function __construct() {
		// Remove action
		remove_action( 'welcome_panel','wp_welcome_panel' );

		// Actions
		add_action( 'wp_dashboard_setup', array( &$this, 'wp_dashboard_setup' ), 999999);
		//add_action( 'load-index.php', array( &$this, 'load_index'));

		// New dashboard
		//add_action( 'load-index.php', array( &$this, 'redirect_dashboard' ));
		//add_action( 'admin_menu', array( &$this, 'register_menu' ));
		//add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_style' ));
	}


	/**
     * Reorganiza os widgets do painel administrativo
     */
    function wp_dashboard_setup() {
        global $wp_meta_boxes;

        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
    }


	/**
	 * PRECISA DE UM COMENTÁRIO
	 */
	function load_index() {
		$showWelcome = get_user_meta( get_current_user_id(), 'show_welcome_panel', TRUE );
		if ( $showWelcome === '' ){
			update_user_meta( get_current_user_id(), 'show_welcome_panel', 1 );}
	}


	/**
	 * PRECISA DE UM COMENTÁRIO
	 */
	function redirect_dashboard() {
		if( is_admin() ) {
			$screen = get_current_screen();

			if( $screen->base == 'dashboard' ) {
				wp_redirect( admin_url( 'index.php?page=dashboard' ) );
			}
		}
	}


	/**
	 * PRECISA DE UM COMENTÁRIO
	 */
	function register_menu() {
		remove_submenu_page( 'index.php', 'index.php' );
		add_dashboard_page(
			'Início',
			'Início',
			'read',
			'dashboard',
			function(){
				include_once( 'assets/dashboard.php');
			}
		);
	}


	/**
	 * PRECISA DE UM COMENTÁRIO
	 */
	function admin_enqueue_style() {
		//wp_enqueue_style( 'theme', get_template_directory_uri() .'/assets/css/dash.css');
	}
}
new Class_Dashboard();