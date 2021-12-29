<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_CPT_Proposta
{
	/**
	 * Class construct
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( &$this, 'init_post_type' ), 100 );

		//Filters
		add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages' ));
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here'));

		//Botões do Editor de conteúdo
		add_filter( 'mce_buttons', array( &$this, 'mce_buttons'), 999);
		add_filter( 'mce_buttons_2', array( &$this, 'mce_buttons_2'), 999);
	}


	/**
	 * Cria o tipo de post proposta
	 */
	function init_post_type() {

		$resposta = register_post_type( 'proposta',
			array(
				'labels' => array(
					'name'               => 'Propostas',
					'singular_name'      => 'Proposta',
					'add_new'            => 'Adicionar proposta',
					'add_new_item'       => 'Adicionar proposta',
					'edit_item'          => 'Editar proposta',
					'new_item'           => 'Novo proposta',
					'view_item'          => 'Ver proposta',
					'search_items'       => 'Buscar proposta',
					'not_found'          => 'Nenhuma proposta encontrado',
					'not_found_in_trash' => 'Nenhuma proposta encontrado na lixeira',
					'parent'             => 'Propostas',
					'menu_name'          => 'Propostas'
				),

				'hierarchical'    => false,
				'public'          => false,
				'query_var'       => false,
				'supports'        => array( 'title', 'editor' ),
				'has_archive'     => false,
				'capability_type' => 'post',
				'menu_icon'       => 'dashicons-megaphone',
				'show_ui'         => true,
				'show_in_menu'    => true,
				'rewrite'         => array('slug' => 'proposta', 'with_front' => false),
			)
		);
	}


	/**
	 * Personaliza as mensagens do processo de salvamento
	 */
	function post_updated_messages( $messages ) {
		global $post, $post_ID;
		$link = esc_url( get_permalink($post_ID));
		$link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post_ID)));
		$date = date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ));

		$messages['proposta'] = array(
			1  => sprintf('<strong>Proposta</strong> atualizada com sucesso - <a href="%s">Ver Proposta</a>', $link),
			6  => sprintf('<strong>Proposta</strong> publicada com sucesso - <a href="%s">Ver Proposta</a>', $link),
			9  => sprintf('<strong>Proposta</strong> agendanda para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Proposta</a>',$date ,$link),
			10 => sprintf('Rascunho do <strong>Proposta</strong> atualizada. <a target="_blank" href="%s">Ver Proposta</a>', $link_preview),
		);
		return $messages;
	}


	/**
	* Altera o placeholder do titulo
	*/
	function enter_title_here( $title ){
		$screen = get_current_screen();
		if  ( $screen->post_type == 'proposta' ) {
			$title = 'Entre com o nome da proposta aqui';
		}
		return $title;
	}


	/**
	* Modifica os botões padrões do editor de conteúdo
	*/
	function mce_buttons($buttons) {
		global $post;
		if ( isset($post->post_type) && $post->post_type == 'proposta' ){
			$buttons = array(
				'bold', 'italic', 'underline', 'bullist', 'numlist', 'link', 'unlink', 'removeformat', 'undo', 'redo', 'fullscreen'
			);
			// 'bold', 'italic', 'underline', 'blockquote', 'strikethrough', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo', 'link', 'unlink', 'fullscreen', 'hr', 'wp_more', 'undo', 'redo'
		}
		return $buttons;
	}


	/**
	* Modifica os botões padrões do editor de conteúdo
	*/
	function mce_buttons_2($buttons) {
		global $post;
		if ( isset($post->post_type) && $post->post_type == 'proposta' ){
			$buttons = array();
		}
		return $buttons;
	}
}
new Class_CPT_Proposta();
