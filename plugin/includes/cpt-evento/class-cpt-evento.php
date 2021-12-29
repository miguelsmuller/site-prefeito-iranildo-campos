<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include 'class-widget-evento.php';

add_action( 'init', 'registe_widget_evento' );
function registe_widget_evento() {
  register_widget( 'Class_Widget_Evento' );
}

class Class_CPT_Evento
{
	/**
	 * Class construct
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( &$this, 'init_post_type' ), 100 );
		add_action( 'admin_init', array( &$this, 'admin_init'), 1 );
		add_action( 'admin_head', array( &$this, 'admin_head' ));

		//Filters
		add_filter( 'pre_get_posts', array( &$this, 'pre_get_posts' ));
		add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages' ));
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here'));

		//Botões do Editor de conteúdo
		add_filter( 'mce_buttons', array( &$this, 'mce_buttons'), 999);
		add_filter( 'mce_buttons_2', array( &$this, 'mce_buttons_2'), 999);

		//Mudança das colunas do WP-ADMIN
		add_filter( 'manage_edit-evento_columns', array( &$this, 'create_custom_column' ));
		add_action( 'manage_evento_posts_custom_column', array( &$this, 'manage_custom_column' ));
		add_filter( 'manage_edit-evento_sortable_columns', array( &$this, 'manage_sortable_columns' ));
		add_filter( 'restrict_manage_posts', array( &$this, 'restrict_manage_posts' ));

		//Funcionamento do JSON
		add_filter( 'template_redirect', array( &$this, 'template_redirect' ));
		add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ));
    }


	/**
	 * Cria o tipo de post evento
	 */
	function init_post_type() {

		$resposta = register_post_type( 'evento',
			array(
				'labels' => array(
					'name'               => 'Eventos',
					'singular_name'      => 'Evento',
					'add_new'            => 'Adicionar evento',
					'add_new_item'       => 'Adicionar evento',
					'edit_item'          => 'Editar evento',
					'new_item'           => 'Novo evento',
					'view_item'          => 'Ver evento',
					'search_items'       => 'Buscar evento',
					'not_found'          => 'Nenhuma evento encontrado',
					'not_found_in_trash' => 'Nenhuma evento encontrado na lixeira',
					'parent'             => 'Eventos',
					'menu_name'          => 'Eventos'
				),

				'hierarchical'    => false,
				'public'          => true,
				'query_var'       => true,
				'supports'        => array( 'title', 'editor' ),
				'has_archive'     => true,
				'capability_type' => 'post',
				'menu_icon'       => 'dashicons-calendar',
				'show_ui'         => true,
				'show_in_menu'    => true,
				'rewrite'         => array('slug' => 'evento', 'with_front' => false),
			)
		);

		register_taxonomy('evento-categoria',array('evento'),
			array(
				'labels'  => array(
					'name'         => 'Categoria dos eventos',
					'menu_name'    => 'Categoria dos eventos',
					'search_items' => 'Buscar categoria',
					'all_items'    => 'Todas as categorias',
					'edit_item'    => 'Editar categoria',
					'update_item'  => 'Atualizar categoria',
					'add_new_item' => 'Adicionar categoria'
				),
				'public'        => false,
				'show_ui'       => true,
				'show_tagcloud' => false,
				'hierarchical'  => true,
				'rewrite'       => array( 'slug' => 'evento-categoria', 'with_front' => false ),
				'query_var'     => true,
		));


		if( false == get_option( 'quant_eventos_index' ) ) {
			add_option( 'quant_eventos_index' );
			update_option( 'quant_eventos_index', 6 );
		}


		if(function_exists("register_field_group")) {
			register_field_group(array (
				'id' => 'acf_evento',
				'title' => 'Evento',
				'fields' => array (
					array (
						'key' => 'field_54b5d4df58c59',
						'label' => 'Data de Inicio',
						'name' => 'data_inicio',
						'type' => 'date_picker',
						'required' => 1,
						'display_format' => 'd/m/Y',
						'return_format' => 'd/m/Y',
						'first_day' => 0,
					),
					array (
						'key' => 'field_54b5d53b58c5a',
						'label' => 'Data de Término',
						'name' => 'data_termino',
						'type' => 'date_picker',
						'display_format' => 'd/m/Y',
						'return_format' => 'd/m/Y',
						'first_day' => 0,
					),
					array (
						'key' => 'field_54b5d56c58c5b',
						'label' => 'Site do Evento',
						'name' => 'site',
						'prefix' => '',
						'type' => 'url',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => 'http://www.google.com.br',
					),
					array (
						'key' => 'field_54b5d73f9356b',
						'label' => 'Imagem de capa',
						'name' => 'thumbnail',
						'type' => 'image',
						'instructions' => 'Envie a imagem no seguinte tamanho 00px X 00px',
						'save_format' => 'object',
						'preview_size' => 'thumbnail',
						'library' => 'all',
					),
					array (
						'key' => 'field_54b5d5a758c5c',
						'label' => 'Local do Evento',
						'name' => 'local',
						'type' => 'google_map',
						'center_lat' => '-22.916560',
						'center_lng' => '-43.247333',
						'zoom' => '',
						'height' => '',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'evento',
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

		if(function_exists("register_field_group")) {
			register_field_group(array (
				'id' => 'acf_tipo-de-evento',
				'title' => 'Tipo de Evento',
				'fields' => array (
					array (
						'key' => 'field_54b5edc68936f',
						'label' => 'Cor de exibição',
						'name' => 'corCategoriaEvento',
						'type' => 'color_picker',
						'default_value' => '',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'ef_taxonomy',
							'operator' => '==',
							'value' => 'evento-categoria',
							'order_no' => 0,
							'group_no' => 0,
						),
					)
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array (
					),
				),
				'menu_order' => 0,
			));
		}
	}


	/**
	 * Cria campos extras na página de evento
	 */
	function admin_init(){
		register_setting( 'reading', 'quant_eventos_index', 'esc_attr' );
		add_settings_field(
			'quant_eventos_index',
			'<label for="quant_eventos_index"><strong>[EVENTO]</strong> Quantidade de eventos na página inicial</label>',
			function(){
				$value = get_option('quant_eventos_index');
				echo '<input name="quant_eventos_index" type="number" step="1" min="1" id="quant_eventos_index" value="' . $value . '" class="small-text">';
			},
			'reading'
		);
	}


	/**
	 * Inclui código CSS no painel administrativo
	 */
	function admin_head() {
		global $post;
		if ( isset($post->post_type) && $post->post_type == 'evento' ){
		?>
			<style type="text/css" media="screen">
				.column-data_inicio,
				.column-data_termino{
					width: 150px;
				}
				.data_inicio.column-data_inicio,
				.data_termino.column-data_termino{
					text-align: center !important;
					font-size: 20px !important;
					font-weight: 900 !important;
				}
				.label-red,
				.label-green{
					position: relative;
					top: 5px;
					padding: .3em 0.6em .3em;
					font-weight: bold;
					border-radius: .25em;
					line-height: 1;
					color: #FFF;
					text-align: center;
					white-space: nowrap;
					vertical-align: baseline;
					display: inline;
				}
				.label-red{
					background-color: #D9534F;
				}
				.label-green{
					background-color: #5CB85C;
				}
			</style>
		<?php
		}
		$screen = get_current_screen();
		if( $screen->base == 'dashboard' ) {
		?>
			<style type="text/css" media="screen">
				#dashboard_right_now a.evento-count:before,
				#dashboard_right_now span.evento-count:before {
					content: '\f145';
				}
			</style>
		<?php
		}
	}


	/**
	 * No archive-evento.php ordena os registro por data de inicio
	 */
	function pre_get_posts( $query ) {
		if ( $query->is_main_query() && !is_admin() && is_post_type_archive( 'evento' ) ) {
			$query->set( 'posts_per_page', -1 );
			$query->set( 'meta_key', 'data_inicio' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'ASC' );
			$query->set( 'meta_query', array(
				array(
					'key' => 'data_inicio',
					'value' => time(),
					'compare' => '>=',
				),
			) );
		}
		return $query;
	}


	/**
	 * Personaliza as mensagens do processo de salvamento
	 */
	function post_updated_messages( $messages ) {
		global $post, $post_ID;
		$link = esc_url( get_permalink($post_ID));
		$link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post_ID)));
		$date = date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ));

		$messages['evento'] = array(
			1  => sprintf('<strong>Evento</strong> atualizado com sucesso - <a href="%s">Ver Evento</a>', $link),
			6  => sprintf('<strong>Evento</strong> publicado com sucesso - <a href="%s">Ver Evento</a>', $link),
			9  => sprintf('<strong>Evento</strong> agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Evento</a>',$date ,$link),
			10 => sprintf('Rascunho do <strong>Evento</strong> atualizado. <a target="_blank" href="%s">Ver Evento</a>', $link_preview),
		);
		return $messages;
	}


	/**
	* Altera o placeholder do titulo
	*/
	function enter_title_here( $title ){
		$screen = get_current_screen();
		if  ( $screen->post_type == 'evento' ) {
			$title = 'Entre com o nome do evento aqui';
		}
		return $title;
	}


	/**
	* Modifica os botões padrões do editor de conteúdo
	*/
	function mce_buttons($buttons) {
		global $post;
		if ( isset($post->post_type) && $post->post_type == 'evento' ){
			$buttons = array(
				'bold', 'italic', 'underline', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'removeformat', 'undo', 'redo', 'fullscreen'
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
		if ( isset($post->post_type) && $post->post_type == 'evento' ){
			$buttons = array();
		}
		return $buttons;
	}


	/**
	 * Cria uma coluna na lista de slides do painel administrativo
	 */
	function create_custom_column($columns) {
		$columns['evento-categoria'] = 'Categoria do evento';
		$columns['data_inicio']      = 'D. Inicio';
		$columns['data_termino']     = 'D. Término';

		unset( $columns['comments'] );
		unset( $columns['date'] );

		return $columns;
	}


	/**
	 * Inseri valor na coluna especifica da listagem do painel administrativo
	 */
	function manage_custom_column ($column) {
		global $post;

		switch( $column ) {
			case 'evento-categoria' :
				$terms = get_the_terms( $post->ID, 'evento-categoria' );
				if ( !empty( $terms ) ) {
					$out = array();
					foreach ( $terms as $term ) {
						$out[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'evento-categoria' => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'evento-categoria', 'display' ) )
						);
					}
					echo join( ', ', $out );
				}
				else {
					echo 'Nenhuma categoria';
				}
				break;

			case 'data_inicio' :
				$date_inicio = DateTime::createFromFormat('d/m/Y', get_field('data_inicio'));
				echo $date_inicio->format('d/m/Y');
				break;

			case 'data_termino' :
				$date_termino = DateTime::createFromFormat('d/m/Y', get_field('data_termino'));
				echo $date_termino->format('d/m/Y');
				break;

			default :
				break;
		}
	}


	/**
	 * Permite que a coluna seja ordenada de acordo com o valor
	 */
	function manage_sortable_columns( $columns ) {
		$columns['data_inicio']  = 'data_inicio';
		$columns['data_termino'] = 'data_termino';

		return $columns;
	}


	/**
	 * Exibi um select para filtragem na listagem do wp-admin
	 */
	function restrict_manage_posts() {
		global $typenow;
		global $wp_query;
		if ($typenow=='evento') {

			$taxonomy = 'evento-categoria';
			$terms = get_terms( $taxonomy );
			if ( $terms ) {
				printf( '<select name="%s" class="postform">', $taxonomy );
				printf( '<option value="0" selected>%s</option>', "Todas as categorias" );
				foreach ( $terms as $term ) {
					if(isset($_GET["evento-categoria"])){
						if($_GET["evento-categoria"] == $term->slug){
							printf( '<option value="%s" selected>%s</option>', $term->slug, $term->name );
						}else{
							printf( '<option value="%s">%s</option>', $term->slug, $term->name );
						}
					}else{
						printf( '<option value="%s">%s</option>', $term->slug, $term->name );
					}
				}
				print( '</select>' );
			}
		}
	}


	/**
	 * Redireciona a uma outra página caso tenha necessidade
	 */
	function template_redirect() {
		if ( (get_query_var( 'evento' ) == 'json-list') && (get_query_var( 'post_type' ) == 'evento') ) {
			add_filter( 'template_include', function(){
				return dirname(__FILE__) . '/assets/feed-evento.php';
			});
		}
	}


	/**
	 * Triggered before any other hook when a user accesses the admin area
	 */
	function wp_enqueue_scripts() {
		if (is_post_type_archive('evento')){
			$jsonevents = site_url() . '/evento-feed';
			wp_localize_script(
				'fullcalendar',
				'fullcalendar',
				array( 'events' => $jsonevents )
			);
		}
	}
}

$Class_CPT_Evento = new Class_CPT_Evento();
