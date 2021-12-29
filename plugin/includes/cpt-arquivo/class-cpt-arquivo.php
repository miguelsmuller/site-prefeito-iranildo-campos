<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_CPT_Arquivo
{
	/**
	 * Class construct
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( &$this, 'init_post_type'), 100);
		add_action( 'admin_head', array( &$this, 'admin_head' ));
		add_action( 'widgets_init', array( &$this, 'widgets_init' ));
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts') );

		// Filters
		add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages'));
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here'));

		// Cria campos para o post type
		add_action( 'post_edit_form_tag', array( &$this, 'post_edit_form_tag'));
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_box'));
		add_action( 'save_post', array( &$this, 'save_post'));
		add_action( 'admin_notices', array( &$this , 'admin_notices') );
		add_action( 'before_delete_post', array( &$this, 'before_delete_post'));

		// Mudança das colunas do WP-ADMIN
		add_filter( 'manage_edit-arquivo_columns', array( &$this, 'create_custom_column' ));
		add_action( 'manage_arquivo_posts_custom_column', array( &$this, 'manage_custom_column' ));
		add_filter( 'manage_edit-arquivo_sortable_columns', array( &$this, 'manage_sortable_columns' ));
		add_filter( 'restrict_manage_posts', array( &$this, 'restrict_manage_posts' ));
		add_action( 'pre_get_posts', array( &$this, 'pre_get_posts' ));

		// Funcionamento de reescrita da URL
		add_action( 'init', array( &$this, 'init' ));
		add_filter( 'query_vars', array( &$this, 'query_vars' ));
		//add_filter( 'template_redirect', array( &$this, 'template_redirect' ));
		//add_filter( 'template_include', array( &$this, 'template_include' ));
		add_filter( 'single_template', array( &$this, 'get_custom_post_type_template' ), 11);
	}


	/**
	 * Cria o tipo de post arquivo
	 */
	function init_post_type() {
		register_post_type( 'arquivo',
			array(
				'labels' => array(
					'name'               => 'Arquivos',
					'singular_name'      => 'Arquivo',
					'add_new'            => 'Adicionar novo arquivo',
					'add_new_item'       => 'Adicionar novo arquivo',
					'edit_item'          => 'Editar arquivo',
					'new_item'           => 'Novo arquivo',
					'view_item'          => 'Ver arquivo',
					'search_items'       => 'Buscar arquivo',
					'not_found'          => 'Nenhuma arquivo encontrado',
					'not_found_in_trash' => 'Nenhuma arquivo encontrado na lixeira',
					'parent'             => 'Arquivos',
					'menu_name'          => 'Arquivos',
				),

				'hierarchical'    => false,
				'public'          => true,
				'query_var'       => true,
				'supports'        => array( 'title' ),
				'has_archive'     => true,
				'capability_type' => 'post',
				'menu_icon'       => 'dashicons-format-aside',
				'show_ui'         => true,
				'show_in_menu'    => true,
				'rewrite'         => array('slug' => 'arquivos', 'with_front' => false),

			)
		);

		register_taxonomy('arquivo-categoria',array('arquivo'),
			array(
				'labels'  => array(
					'name'         => 'Categoria dos arquivos',
					'menu_name'    => 'Categorias dos arquivos',
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
				'rewrite'       => array( 'slug' => 'arquivos-categoria', 'with_front' => false ),
				'query_var'     => true,
		));

		register_taxonomy('arquivo-referencia',array('arquivo'),
			array(
				'labels'  => array(
					'name'         => 'Referência dos arquivos',
					'menu_name'    => 'Referências dos Arquivos',
					'search_items' => 'Buscar referência',
					'all_items'    => 'Todas as referências',
					'edit_item'    => 'Editar referência',
					'update_item'  => 'Atualizar referência',
					'add_new_item' => 'Adicionar referência'
				),
				'public'        => false,
				'show_ui'       => true,
				'show_tagcloud' => true,
				'hierarchical'  => false,
				'rewrite'       => array( 'slug' => 'arquivos-referencia', 'with_front' => false ),
				'query_var'     => true,
		));
	}


	/**
	 * Inclui código CSS no painel administrativo
	 */
	function admin_head() {
		global $post;
		if ( isset($post->post_type) && $post->post_type == 'arquivo' ){
		?>
			<style type="text/css" media="screen">
				.column-file_qtdown{
					width: 110px;
				}
				.file_qtdown.column-file_qtdown{
					text-align: center !important;
					font-size: 20px !important;
				}
				.column-indisponivel{
					width: 110px;
				}
				.column-privado{
					width: 90px;
				}
				.misc-pub-curtime{
					display: none;
				}
				.misc-pub-section {
					padding: 6px 10px 10px;
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
				#dashboard_right_now a.arquivo-count:before,
				#dashboard_right_now span.arquivo-count:before {
					content: '\f123';
				}
			</style>
		<?php
		}
	}


	/**
	 * Registra os widgets do plugin
	 */
	function widgets_init() {
		include_once 'widgets/class-widget-arquivo.php';
		register_widget( 'Class_Widget_Arquivo' );
	}


	/**
	* Carrega CSS e JS no WP-ADMIN
	*/
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'jquery-ui', get_plugin_url() . '/assets/components/jqueryui/css/theme/jquery-ui-1.10.3.custom.min.css' );
		wp_enqueue_script( 'jquery-ui-core', array('jquery'));
		wp_enqueue_script( 'jquery-ui-widget', array('jquery'));
		wp_enqueue_script( 'jquery-ui-tabs', array('jquery'));

		wp_enqueue_script( 'cpt-arquivo', get_plugin_url() . '/includes/cpt-arquivo/assets/post-type-arquivo.js' );
	}


	/**
	 * Personaliza as mensagens do processo de salvamento
	 */
	function post_updated_messages( $messages ) {
		global $post;
		$link = esc_url( get_permalink($post->ID));
		$link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post->ID)));
		$date = date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ));

		$messages['arquivo'] = array(
			1  => sprintf('<strong>Arquivo</strong> atualizado com sucesso - <a href="%s">Ver Arquivo</a>', $link),
			6  => sprintf('<strong>Arquivo</strong> publicado com sucesso - <a href="%s">Ver Arquivo</a>', $link),
			9  => sprintf('<strong>Arquivo</strong> agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Arquivo</a>',$date ,$link),
			10 => sprintf('Rascunho do <strong>Arquivo</strong> atualizado. <a target="_blank" href="%s">Ver Arquivo</a>', $link_preview),
		);
		return $messages;
	}


	/**
	* Altera o placeholder do titulo
	*/
	function enter_title_here( $title ){
		$screen = get_current_screen();
		if  ( $screen->post_type == 'arquivo' ) {
			$title = 'Entre com o nome do arquivo aqui';
		}
		return $title;
	}


	/**
	 * Muda o enctype do formulário do post type
	 */
	function post_edit_form_tag() {
		global $post;
		if (get_post_type($post->ID) != 'arquivo')
			return;

		echo ' enctype="multipart/form-data"';
	}


	 /**
	 * Cria um meta box no formulário do post type
	 */
	function add_meta_box() {
		add_meta_box(
			'metaBoxArquivo',// $id
			'Informações do Arquivo',// $title
			array( &$this, 'show_box'),
			'arquivo',// $post_type
			'normal',// $context
			'high'// $priority
		);
	}

	function show_box(){
		global $pagenow;
		global $post;

		wp_nonce_field('saveExtraFields','securityField');
		?>
		<div id="tabs" class="ui-tab">
			<ul>
				<?php if ( in_array($pagenow, array( 'post-new.php',  )) ){ ?>
					<li><a href="#tabs-novo">Novo Aquivo</a></li>
				<?php }else{ ?>
					<li><a href="#tabs-info">Informações do Arquivo Atual</a></li>
					<li><a href="#tabs-alterar">Alterar arquivo</a></li>
				<?php } ?>
			</ul>

			<?php if ( in_array($pagenow, array( 'post-new.php',  )) ){ ?>

				<div id="tabs-novo">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="arquivo">Origem do arquivo no computador:</label></th>
								<td>
									<input type="file" id="arquivo" name="arquivo" value="" style="width: 90%;">
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			<?php }else{ ?>
				<?php
					$post_meta   = get_post_meta($post->ID);
					$file_name   = $post_meta['file_name'][0];
					$file_ext    = $post_meta['file_ext'][0];
					$file_url    = $post_meta['file_url'][0];
					$file_path   = self::url_sanitize($post_meta['file_path'][0]);
					$file_qtdown = empty($post_meta['file_qtdown'][0]) ? '0' : $post_meta['file_qtdown'][0];
				?>
				<div id="tabs-info">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label>Quant. Downloads:</label></th>
								<td>
									<?php echo $file_qtdown;  ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label>URL do Arquivo:</label></th>
								<td>
									<?php echo $file_url;  ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label>Path do Arquivo:</label></th>
								<td>
									<?php echo $file_path;  ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label>Nome do Arquivo:</label></th>
								<td>
									<?php echo $file_name;  ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label>Extensão:</label></th>
								<td>
									<?php echo $file_ext;  ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="tabs-alterar">
					<input type="hidden" name="metodo" value="editar" />
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="arquivo">Origem do arquivo no computador:</label></th>
								<td>
									<input type="file" id="arquivo" name="arquivo" value="" style="width: 90%;">
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			<?php } ?>
		</div>
	<?php
	}



	/**
	 * Procedimento para salvar os dados extras
	 */
	function save_post( $post_id ) {
		// Verifica o tipo do post
		if (get_post_type($post_id) != 'arquivo') return;
		// Verificar se a publicação é salva automaticamente
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		// Verificar o valor nonce criado no forma
		if( !isset( $_POST['securityField'] ) || !wp_verify_nonce( $_POST['securityField'], 'saveExtraFields' ) ) return;
		// Verifica se o usuário atual tem permissão para salvar
		if( !current_user_can('edit_post', $post_id) ) return;

		if(!empty($_FILES['arquivo']['name'])) {
			$upload_dir = wp_upload_dir();
			$remote_dir['url'] = $upload_dir['baseurl'] . '/documentos';
			$remote_dir['path'] = $upload_dir['basedir'] . '/documentos';

			// Cria o folder caso ele não exista
			if (!file_exists($remote_dir['path'])) {
				mkdir($remote_dir['path'], 0755, true);
			}

			//APAGA ALGUM ARQUIVO ANTIGO SE EXISTIR
			$file_path  = get_post_meta($post_id, 'file_path', true);
			if ( !empty($file_path) ){
				$file_path  = $this->url_sanitize($file_path);
				if (file_exists($file_path) == TRUE) {
					unlink($file_path);
				}
			}

			// PARTE DOS DADOS A SEREM SALVOS
			$file_uploaded['file_nameold']   = $_FILES["arquivo"]["name"];

			$file_uploaded['file_ext']  = pathinfo($file_uploaded['file_nameold'], PATHINFO_EXTENSION);
			$file_uploaded['file_name'] = pathinfo($file_uploaded['file_nameold'], PATHINFO_FILENAME);
			$file_uploaded['file_name'] = time() .'-'. sanitize_title($file_uploaded['file_name']) .'.'. $file_uploaded['file_ext'];

			$file_uploaded['file_path'] = $remote_dir['path'] . '/' . $file_uploaded['file_name'];

			$file_uploaded['file_path_sanitized'] = str_replace("/", "#", $file_uploaded['file_path']);
			$file_uploaded['file_path_sanitized'] = str_replace("\\", "#", $file_uploaded['file_path_sanitized']);

			$file_uploaded['file_url'] = $remote_dir['url'] . $file_uploaded['file_name'];

			if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $file_uploaded['file_path'] )) {
				update_post_meta($post_id, "file_url", $file_uploaded['file_url']);
				update_post_meta($post_id, "file_path", $file_uploaded['file_path_sanitized']);
				update_post_meta($post_id, "file_name", $file_uploaded['file_name']);
				update_post_meta($post_id, "file_ext", $file_uploaded['file_ext']);
				update_post_meta($post_id, "file_qtdown", '0');
			}

			//$this->notify_users($post_id);
		}
		else{
			//set_transient( "post_has_file", "no" );
			//remove_action('save_post', 'save_post');
			//wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
			//add_action('save_post', 'save_post');
		}
	}


	/**
	 * Notifica os usuários por email após o salvamento dos dados
	 */
	function notify_users($post_id){
		if ( !wp_is_post_revision( $post_id ) ){
			$users = get_users( array(
				'fields' => array( 'ID', 'user_nicename', 'display_name', 'user_email' )
			));
			foreach ($users as $user) {
				$title = get_the_title( $post_id );
				$url = get_permalink( $post_id );

				$linkVisualizar = '<a href="'. $url .'/action/view">Visualizar</a>';
				$linkDownload = '<a href="'. $url .'/action/down">Download</a>';

				$mensagem = "O Arquivo <strong>$title</strong> foi inserido/atualizado no nosso site.<br/> Você pode tentar visualizar o arquivo pelo link: $linkVisualizar <br/> Ou fazer o download através do link: $linkDownload";

				wp_mail( $user->user_nicename, 'Novo Arquivivo disponível', $mensagem );
			}
		}
	}


	/**
	 * Mostra alerts em casos específicos
	 */
	function admin_notices()
	{
		if ( get_transient( "post_has_file" ) == "no" ) {
			echo "<div id='message' class='error'><p><strong>Nenhum arquivo enviado.</strong> Você precisa enviar um arquivo para poder salvar de forma adequada o arquivo.</p></div>";
		delete_transient( "post_has_file" );
		}
	}


	/**
	 * Exclui o arquivo fisico após a exclusão do post type
	 */
	function before_delete_post( $post_id ) {
		if (get_post_type($post_id) != 'arquivo')
			return;

		$post_meta   = get_post_meta($post_id);
		$file_path   = $this->url_sanitize($post_meta['file_path'][0]);

		if (file_exists($file_path) == TRUE) {
			unlink($file_path);
		}
	}


	/**
	 * Cria uma coluna na lista de slides do painel administrativo
	 */
	function create_custom_column( $columns ) {
		$columns['file-name']           = 'Nome do Arquivo';
		$columns['arquivo-referencias'] = 'Referências';
		$columns['arquivo-categoria']   = 'Categoria';
		$columns['privado']             = 'Visibilidade';
		$columns['indisponivel']        = 'Disponibilidade';
		$columns['file_qtdown']         = 'Downloads';

		unset( $columns['date'] );

		return $columns;
	}



	/**
	 * Inseri valor na coluna especifica da listagem do painel administrativo
	 */
	function manage_custom_column( $column ) {
		global $post;
		$post_meta   = get_post_meta($post->ID);
		$file_name   = $post_meta['file_name'][0];
		$file_ext    = $post_meta['file_ext'][0];
		$file_url    = $post_meta['file_url'][0];
		$file_path   = $this->url_sanitize($post_meta['file_path'][0]);
		$file_qtdown = empty($post_meta['file_qtdown'][0]) ? '0' : $post_meta['file_qtdown'][0];

		$status_post = get_post_status( $post->ID );

		switch( $column ) {
			case 'file-name':
				echo '<strong>'.$file_name.'</strong>';
				break;

			case 'arquivo-referencias':
				the_terms( $post->ID, 'arquivo-referencia', ' ');
				break;

			case 'file_qtdown' :
				echo '<strong>'.$file_qtdown.'</strong>';
				break;

			case 'privado' :
				if ($status_post == 'private') {
					echo '<span class="label-red">PRIVADO</span>';
				}else{
					echo '<span class="label-green">PUBLICO</span>';
				}
				break;

			case 'indisponivel' :
				if ($status_post == 'pending' || $status_post == 'draft') {
					echo '<span class="label-red">INDISPONÍVEL</span>';
				}else{
					echo '<span class="label-green">DISPONÍVEL</span>';
				}
				break;

			case 'arquivo-categoria' :
				$terms = get_the_terms( $post->ID, 'arquivo-categoria' );
				if ( !empty( $terms ) ) {
					$out = array();
					foreach ( $terms as $term ) {
						$out[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'arquivo-categoria' => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'arquivo-categoria', 'display' ) )
						);
					}
					echo join( ', ', $out );
				}
				else {
					echo 'Não categorizado';
				}
				break;

			default :
				break;
		}
	}


	/**
	 * Permite que a coluna seja ordenada de acordo com o valor
	 */
	function manage_sortable_columns( $columns ) {
		$columns['file_qtdown'] = 'quantidadeDownloads';

		return $columns;
	}



	/**
	 * Exibi um select para filtragem na listagem do wp-admin
	 */
	function restrict_manage_posts() {
		global $typenow;
		global $wp_query;
		if ($typenow=='arquivo') {

			$taxonomy = 'arquivo-categoria';
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
	 * No wp-admin orderna de acordo com a necessidade
	 */
	function pre_get_posts( $query ) {
		if( ! is_admin() )
			return;

		$orderby = $query->get('orderby');

		if( 'quantidadeDownloads' == $orderby ) {
			$query->set('meta_key','quantidadeDownloads');
			$query->set('orderby','meta_value_num');
		}
	}


	/**
	 * Adiciona um regra de reescrita de URL
	 */
	function init() {
		add_rewrite_rule(
			'arquivos/([^/]*)/action/([^/]*)',
			'index.php?post_type=arquivo&arquivo=$matches[1]&action=$matches[2]',
			'bottom'
		);
	}


	/**
	 * Adiciona uma váriavel de URL
	 */
	function query_vars( $vars ) {
		$vars[] = 'action';
		return $vars;
	}


	/**
	 * Redireciona a uma outra página caso tenha necessidade
	 */
	function template_redirect() {
		if ( (get_query_var('post_type') == 'arquivo') && (get_query_var( 'arquivo' ) != '') ){
			$defaultSingle = get_template_directory() . '/single-arquivo.php';
			if (file_exists($defaultSingle) == false) {
				include (dirname(__FILE__) . '/assets/single-arquivo.php');
			}else{
				include ($defaultSingle);
			}
			exit();
		}
	}


	/**
	 * Usado para carregador um template diferenciado para determinada página
	 */
	function template_include( $template ) {
		$defaultSingle = get_template_directory() . '/single-arquivo.php';
		if (file_exists($defaultSingle) == false) {
			$template =  (dirname(__FILE__) . '/assets/single-arquivo.php');
		}else{
			$template = $defaultSingle;
		}

		return $template;
	}


	function get_custom_post_type_template($single_template) {
		global $wp_query, $post;

		$cookie_value = strtotime(date('Y-m-d'));
		$cookie_name = md5($_SERVER['SERVER_NAME'] . $post->ID); // URL Atual

		if( $wp_query->is_single == true && !isset($_COOKIE[$cookie_name]) ){
			$file_qtdown = (int) get_post_meta($post->ID, 'file_qtdown', true);

			$file_qtdown++;
			update_post_meta($post->ID, 'file_qtdown', $file_qtdown);

			setcookie($cookie_name, $cookie_value, time()+3600, COOKIEPATH, COOKIE_DOMAIN, false);
		}


		if ($post->post_type == 'arquivo') {
			$defaultSingle = get_template_directory() . '/single-arquivo.php';

			if (file_exists($defaultSingle) == false) {
				$single_template =  (dirname(__FILE__) . '/assets/single-arquivo.php');
			}
		}
		return $single_template;
	}


	/**
	 * Função auxiliar que faz a limpeza do path do arquivo
	 */
	public static function url_sanitize($url){
		return str_replace("#", "/", $url);
	}
}
new Class_CPT_Arquivo();
