<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_CPT_Depoimento
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
	}


	/**
	 * Cria o tipo de post depoimento
	 */
	function init_post_type() {

		$resposta = register_post_type( 'depoimento',
			array(
				'labels' => array(
					'name'               => 'Depoimentos',
					'singular_name'      => 'Depoimento',
					'add_new'            => 'Adicionar depoimento',
					'add_new_item'       => 'Adicionar depoimento',
					'edit_item'          => 'Editar depoimento',
					'new_item'           => 'Novo depoimento',
					'view_item'          => 'Ver depoimento',
					'search_items'       => 'Buscar depoimento',
					'not_found'          => 'Nenhuma depoimento encontrado',
					'not_found_in_trash' => 'Nenhuma depoimento encontrado na lixeira',
					'parent'             => 'Depoimentos',
					'menu_name'          => 'Depoimentos'
				),

				'hierarchical'    => false,
				'public'          => false,
				'query_var'       => false,
				'supports'        => array( 'title' ),
				'has_archive'     => false,
				'capability_type' => 'post',
				'menu_icon'       => 'dashicons-format-quote',
				'show_ui'         => true,
				'show_in_menu'    => true,
				'rewrite'         => array('slug' => 'depoimento', 'with_front' => false),
			)
		);

		if(function_exists("register_field_group")) {
			register_field_group(array (
				'id' => 'acf_depoimento',
				'title' => 'Depoimento',
				'fields' => array (
					array (
						'key' => 'field_57c06a3a6c7b0',
						'label' => 'Thumbnail',
						'name' => 'thumbnail',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'medium',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
					array (
						'key' => 'field_57c06aa96c7b1',
						'label' => 'Residência',
						'name' => 'residencia',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array (
						'key' => 'field_57c06a3a6c7b3',
						'label' => 'Depoimento',
						'name' => 'depoimento',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'visual',
						'toolbar' => 'basic',
						'media_upload' => 0,
					)
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'depoimento',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'default',
					'hide_on_screen' => array (
						0 => 'permalink',
						2 => 'excerpt',
						3 => 'custom_fields',
						6 => 'revisions',
						7 => 'slug',
						8 => 'author',
						9 => 'format',
						10 => 'featured_image',
						11 => 'tags',
						12 => 'send-trackbacks',
						13 => 'the_content',
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'left',
				'instruction_placement' => 'field',
				'hide_on_screen' => '',
			));
		}
	}


	/**
	 * Personaliza as mensagens do processo de salvamento
	 */
	function post_updated_messages( $messages ) {
		global $post, $post_ID;
		$link = esc_url( get_permalink($post_ID));
		$link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post_ID)));
		$date = date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ));

		$messages['depoimento'] = array(
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
		if  ( $screen->post_type == 'depoimento' ) {
			$title = 'Entre com o nome do depoente aqui';
		}
		return $title;
	}
}
new Class_CPT_Depoimento();
