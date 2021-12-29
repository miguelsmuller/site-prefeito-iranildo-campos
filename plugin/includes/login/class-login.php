<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Login
{
	/**
	 * Construtor da Classe
	 */
	public function __construct() {
		// Action
		add_action( 'wp_logout', array( &$this, 'wp_logout' ));

		// Filter
		add_filter( 'login_redirect', array( &$this, 'login_redirect'), 10, 3 );
		add_filter( 'login_headerurl', array( &$this, 'login_header_url' ));
		add_filter( 'login_headertitle', array( &$this, 'login_header_title' ));
	}


	/**
	 * Após o logout redireciona para a página inicial
	 */
	function wp_logout() {
		wp_redirect(home_url()); exit();
	}


	/**
	 * Após o login redireciona para a página especifica
	 */
	function login_redirect( $redirect_to, $request, $user ) {
		//global $user;
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'subscriber', $user->roles ) ) {
				return home_url();
			} else {
				return $redirect_to;
			}
		}
		return $redirect_to;
	}


	/**
	 * Muda a URL da logo da página de login
	 */
	function login_header_url() {
		return site_url();
	}

	/**
	 * Muda o titulo da logo da página de login
	 */
	function login_header_title() {
		$title = get_bloginfo( 'name' );
		return $title;
	}
}
new Class_Login();