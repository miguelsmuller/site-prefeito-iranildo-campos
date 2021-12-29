<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Customize
{
	/**
	 * Construtor da Classe
	 */
	public function __construct() {
		add_action( 'customize_register', array( &$this, 'customize_register'));
		add_action( 'wp_head', array( &$this, 'wp_head') );
	}


	/**
	* Define o caminho de URL do plugin plugin Advanced Custom Fields
	*/
	function customize_register( $wp_customize ) {
		$wp_customize->add_setting( 'android_top_bar_color' , array(
			'default'   => '#000000',
			'transport' => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'android_top_bar_color', array(
			'label'    => 'Android top bar color',
			'section'  => 'title_tagline',
			'settings' => 'android_top_bar_color',
			'priority' => 20
		) ) );
	}


	/**
	* Define o caminho de URL do plugin plugin Advanced Custom Fields
	*/
	function wp_head(){
		echo '<meta name="theme-color" content="'. get_theme_mod('android_top_bar_color', '#000000') .'">';
	}
}
new Class_Customize();
