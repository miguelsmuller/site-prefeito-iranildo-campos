<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Theme_Customize
{
	/**
	 * Construtor da Classe
	 */
	public function __construct() {
		add_action( 'customize_register', array( &$this, 'customize_register'));
	}


	/**
	* Define o caminho de URL do plugin plugin Advanced Custom Fields
	*/
	function customize_register( $wp_customize ) {

		$wp_customize->add_setting( 'social_facebook_url' , array(
			'default'   => '',
			'type'	=> 'option'
		));
		$wp_customize->add_control( 'social_facebook_url', array(
			'label'    => 'Página do Facebook',
			'section'  => 'theme_customize_options',
			'settings' => 'social_facebook_url',
			'priority' => 20
		));

		$wp_customize->add_setting( 'social_twibbon_url' , array(
			'default'   => '',
			'type'	=> 'option'
		));
		$wp_customize->add_control( 'social_twibbon_url', array(
			'label'    => 'Página do Twibbon',
			'section'  => 'theme_customize_options',
			'settings' => 'social_twibbon_url',
			'priority' => 20
		));
	}
}
new Class_Theme_Customize();
