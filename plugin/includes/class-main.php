<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Main
{
	/**
     * Construtor da Classe
     */
	public function __construct() {
		// Remove actions
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Actions
		add_action( 'init', array( &$this, 'init_class_main' ), 100 );
		add_action( 'after_switch_theme', array( &$this, 'after_switch_theme' ) );
		add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ) );
		add_action( 'admin_head', array( &$this, 'admin_head' ));
		add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ), 0);
		//add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts') );
		add_action( 'dashboard_glance_items', array( &$this, 'dashboard_glance_items') );

		// Filters
		add_filter( 'xmlrpc_enabled', '__return_false');
		add_filter( 'admin_bar_menu', array( &$this, 'admin_bar_menu' ), 25 );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'wp_before_admin_bar_render' ), 0);
		add_filter( 'wp_title', array( &$this, 'wp_title' ), 10, 2 );

		// Mudar o texto do rodapé do WP-ADMIN
		add_action( 'admin_footer_text', array( &$this, 'admin_footer_text' ));
		add_filter( 'update_footer', array( &$this, 'update_footer' ), 999 );

		// Remove os sufixos versão do CSS e JS
		add_filter( 'style_loader_src', array( &$this, 'remove_enqueue_version' ), 9999 );
		add_filter( 'script_loader_src', array( &$this, 'remove_enqueue_version' ), 9999 );

		// Remove shortcode do conteudo e do resumo
		add_action( 'the_excerpt', array( &$this, 'clean_excerpt' ));
		add_action( 'get_the_excerpt', array( &$this, 'clean_excerpt' ));

		// Inseri imagem de capa no RSS
		add_filter( 'the_excerpt_rss', array( &$this, 'rss_with_image' ));
		add_filter( 'the_content_feed', array( &$this, 'rss_with_image' ));

		// Remove os atributos de dimensão das imagens inseridas
		add_filter( 'post_thumbnail_html', array( &$this, 'remove_thumbnail_dimensions' ), 10 );
		add_filter( 'image_send_to_editor', array( &$this, 'remove_thumbnail_dimensions' ), 10 );

		// Força a inclusão de um título para as publicações
		add_action( 'edit_form_advanced', array( &$this, 'force_post_title'));
		add_action( 'edit_page_form', array( &$this, 'force_post_title'));

		// Opções do Advanced Custom Fields
		add_filter( 'acf/settings/path', array( &$this, 'define_acf_settings_path'));
		add_filter( 'acf/settings/dir', array( &$this, 'define_acf_settings_dir'));
		if ( (defined('DISALLOW_FILE_MODS')) && (DISALLOW_FILE_MODS == TRUE) ) {
			add_filter( 'acf/settings/show_admin', '__return_false');
		}
		include_once( get_plugin_path() . '/assets/components/advanced-custom-fields-pro/acf.php' );

		add_filter('acf/fields/google_map/api', array( &$this, 'define_google_map_key'));
		add_filter('acf/settings/google_api_key', array( &$this, 'define_google_map_key'));
		add_action('acf/init',  array( &$this, 'define_acf_init'));
	}

	/**
	 * Cria o tipo de post evento
	 */
	function init_class_main() {
		if( function_exists('acf_add_local_field_group') ){
			acf_add_local_field_group(array(
				'key' => 'group_61c50ff7250ef',
				'title' => 'Chave de API',
				'fields' => array(
					array(
						'key' => 'field_80z9u2aixfm2jad',
						'label' => 'Chave de API do Google',
						'name' => 'api_key_google',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
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
					array(
						'key' => 'field_b2zf7ma9vq3cg5l',
						'label' => 'Chave de API do Facebook',
						'name' => 'api_key_facebook',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
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
				),
				'location' => array(
					array(
						array(
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'theme-general-settings',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'acf_after_title',
				'style' => 'default',
				'label_placement' => 'left',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));

			acf_add_local_field_group(array(
				'key' => 'group_61cbb97793cdf',
				'title' => 'Google Recaptcha',
				'fields' => array(
					array(
						'key' => 'field_61cbb97c9f80a',
						'label' => 'Chave Secreta do Google Recaptcha',
						'name' => 'key_recaptcha_secret',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
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
					array(
						'key' => 'field_61cbb9819f80b',
						'label' => 'Chave Public do Google Recaptcha',
						'name' => 'key_recaptcha_public',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
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
				),
				'location' => array(
					array(
						array(
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'theme-general-settings',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'left',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		}


	}




	/**
	* Refaz as regras de reescrita da url
	*/
	function after_switch_theme() {
		flush_rewrite_rules();
	}


	/**
	* Não mostrar a barra de administração para quem não pode editar publicação
	*/
	function after_setup_theme() {
		if ( !current_user_can( 'edit_posts' ) ) {
			show_admin_bar(false);
		}
	}


	/**
     * Inclui código CSS no painel administrativo
     */
    function admin_head() {
    ?>
    <style type="text/css" media="screen">
        .attachment-details [data-setting="alt"] input,
		.attachment-details [data-setting="title"] input{
		    border: 3px solid red !important;
		}
		.wp-editor-container textarea.wp-editor-area{
		    font-size: 16px;
		}
    </style>
    <?php
    }


	/**
	* Carrega CSS e JS
	*/
	function wp_enqueue_scripts() {
		$enabled_jquery = TRUE;
		if ( apply_filters( 'enabled_jquery', $enabled_jquery )  == TRUE ) {
			wp_deregister_script( 'jquery' );

			$url_jquery = "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js";
			wp_register_script( 'jquery', $url_jquery, null, null, true );
			wp_enqueue_script( 'jquery' );
		}
	}


	/**
	* Carrega CSS e JS no WP-ADMIN
	*/
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'icomoon', get_plugin_url() . '/assets/fonts/icomoon/style.css' );
		?>
			<style type="text/css" media="screen">
				#adminmenu #toplevel_page_devim-general-settings div.wp-menu-image:before {
					font-family: 'icomoon' !important;
					content: '\e601';
				}
			</style>
		<?php
	}


	/**
	* Adiciona os post types ao widget right now do dashboard
	*/
	function dashboard_glance_items(){
		$glances = array();

		$args = array(
			'public'   => true,
			'_builtin' => false
		);

		$post_types = get_post_types($args, 'object', 'and');

		$exclude = array();
		$exclude = apply_filters( 'glance_items_remove', $exclude );

		foreach ($post_types as $post_type)
		{
			if( TRUE === in_array( $post_type->name, $exclude ) )
				continue;

			$num_posts = wp_count_posts($post_type->name);
			$num   = number_format_i18n($num_posts->publish);
			$text  = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));

			if (current_user_can('edit_posts')){
				$glance = '<a class="'.$post_type->name.'-count" href="'.admin_url('edit.php?post_type='.$post_type->name).'">'.$num.' '.$text.'</a>';
			} else {
				$glance = '<span class="'.$post_type->name.'-count">'.$num.' '.$text.'</span>';
			}

			$glances[] = $glance;
		}

		return $glances;
	}


	/**
	* Muda o texto de boas vindas na barra administrativa
	*/
	function admin_bar_menu( $wp_admin_bar ) {
		$my_account=$wp_admin_bar->get_node('my-account');
		$newtitle = str_replace( 'Olá', 'Sua conta ', $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}


	/**
	* Remove a logo do wordpress da barra administrativa
	*/
	function wp_before_admin_bar_render() {
		global $wp_admin_bar;

		/* Remove their stuff */
		$wp_admin_bar->remove_menu('wp-logo');
	}


	/**
	* Gera o titúlo para SEO
	*/
	function wp_title( $title, $sep ) {
		global $page, $paged;

		if ( is_feed() ) {
			return $title;
		}

		// Add the blog name.
		$title .= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= ' ' . $sep . ' ' . $site_description;
		}

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 ) {
			$title .= ' ' . $sep . ' ' . sprintf( __( 'Page %s', 'odin' ), max( $paged, $page ) );
		}

		return $title;
	}


	/**
	* Muda o texto do rodapé
	*/
	function admin_footer_text() {
		$theme = wp_get_theme();
		echo 'Desenvolvido por <a href="'. $theme->AuthorURI .'" target="_blank">'. $theme->Author .'</a>';
	}



	/**
	* Muda o texto do rodapé
	*/
	function update_footer() {
		$theme = wp_get_theme();
		$version = 'Versão atual do tema '.$theme->Name. " é " .$theme->Version . ' / '. get_bloginfo('version');
		return $version;
	}


	/**
	* Remove os sufixos versão do CSS e JS
	*/
	function remove_enqueue_version( $src ) {
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}


	/**
	* Remove shortcode do conteudo e do resumo
	*/
	function clean_excerpt( $excerpt ) {
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		//$excerpt = preg_replace ("/\[(\S+)\]", "", $excerpt);
		return $excerpt;
	}


	/**
	* Inseri imagem de capa no RSS
	*/
	function rss_with_image( $content ) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
			$content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
		}
		return $content;
	}


	/**
	* Remove os atributos de dimensão das imagens inseridas
	*/
	function remove_thumbnail_dimensions( $html ) {
		$remove_thumbnail_dimensions = TRUE;
		if ( apply_filters( 'remove_thumbnail_dimensions', $remove_thumbnail_dimensions )  == TRUE ) {
			$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
			return $html;
		}
	}


	/**
	* Força a inclusão de um título para as publicações
	*/
	function force_post_title(){
	  echo "<script type='text/javascript'>\n";
	  echo "
	  jQuery('#publish').click(function(){
			var title_content = jQuery('[id^=\"titlediv\"]').find('#title');
			if (title_content.val().length < 1)
			{
				jQuery('[id^=\"titlediv\"]').find('#titlewrap').css('border-left', '4px solid red')
					.delay( 5000 )
					.animate({'border-width': '0px' }, 500);
				setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
				setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
				return false;
			}
		});
	  ";
	   echo "</script>\n";
	}


	/**
	* Define o caminho de URL do plugin plugin Advanced Custom Fields
	*/
	function define_acf_settings_path( $path ) {
		$path = get_plugin_path() . '/assets/components/advanced-custom-fields-pro/';
		//$path = dirname( __FILE__  ) . '/assets/components/advanced-custom-fields-pro/';

		// return
		return $path;
	}


	/**
	* Define o caminho fisico do plugin plugin Advanced Custom Fields
	*/
	function define_acf_settings_dir( $dir ) {
		$dir = get_plugin_url() . '/assets/components/advanced-custom-fields-pro/';
		//$dir = plugins_url() . '/devim-core-plugin/assets/components/advanced-custom-fields-pro/';

		// return
		return $dir;
	}



	function define_google_map_key($api){
		$api['key'] = get_field('api_key_google', 'option');
		return $api;
	}


	/**
	* Define o caminho fisico do plugin plugin Advanced Custom Fields
	*/
	function define_acf_init() {
		if( function_exists('acf_add_options_page') ) {
			$option_page = acf_add_options_page(array(
				'page_title'    => 'Custom Options',
				'menu_title'    => 'Custom',
				'menu_slug'     => 'theme-general-settings',
				'capability'    => 'edit_posts',
				'redirect'      => false
			));
		}
	}
}
new Class_Main();
