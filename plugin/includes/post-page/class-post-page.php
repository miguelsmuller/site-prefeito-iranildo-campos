<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Post_Option
{
    /**
     * Construtor da Classe
     */
    public function __construct() {
        // Actions
        add_action( 'init', array( &$this , 'init'));
        add_action( 'admin_init' , array( &$this , 'admin_init'));
        add_action( 'template_redirect', array( &$this, 'template_redirect'));

        // Filters
        add_filter( 'pre_get_posts', array( &$this , 'pre_get_posts_index_post') );
        add_filter( 'pre_get_posts', array( &$this , 'pre_get_posts_search') );
        add_filter( 'mce_buttons' , array( &$this , 'enable_more_editor_buttons'), 0);
        add_filter( 'wp_get_attachment_link' , array( &$this, 'wp_get_attachment_link' ));
        add_filter( 'the_content', array( &$this, 'addlightboxrel_replace' ));

        // Shortcodes
        add_shortcode( 'gallery', array( &$this, 'shortcode_gallery_change' ));
    }


    /**
     * Habilita a opção de resumo nas páginas
     */
    function init() {
        add_post_type_support( 'page', 'excerpt' );

        if( false == get_option( 'quant_posts_index' ) ) {
            add_option( 'quant_posts_index' );
            update_option( 'quant_posts_index', 10 );
        }
    }


    /**
     * Cria campos extras na página de evento
     */
    function admin_init() {
        register_setting( 'reading', 'quant_posts_index', 'esc_attr' );
        add_settings_field(
            'quant_posts_index',
            '<label for="quant_posts_index"><strong>[POSTS]</strong> Quantidade de posts na página inicial</label>',
            function(){
                $value = get_option('quant_posts_index');
                echo '<input name="quant_posts_index" type="number" step="1" min="1" id="quant_posts_index" value="' . $value . '" class="small-text">';
            },
            'reading'
        );
    }


    /**
     * Redireciona a uma outra página caso o resultado de uma busca retorne apenas 1 resultado
     */
    function template_redirect() {
        if (is_search()) {
            global $wp_query;
            if ($wp_query->post_count == 1) {
                wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            }
        }
    }


    /**
     * Define a quantidade de posts exibidos na página inicial
     */
    function pre_get_posts_index_post( $query ) {
        if ( $query->is_main_query() && is_home()  ) {
            $query->set( 'posts_per_page', get_option( 'quant_posts_index' ) );
        }

        return $query;
    }



    /**
     * Define post types que apareceram nos resultados da busca
     */
    function pre_get_posts_search( $query ) {
        if( !is_admin() && isset($_GET['post_type'])){
            if ($query->is_search || $query->is_feed) {
                if (in_array('page', $_GET['post_type'])) {
                    $query_types = get_query_var('post_type');
                    array_push($query_types, "page");
                    $query->set('post_type', $query_types);
                }
            }
        }

        return $query;
    }


    /**
     * Habital o botão de barra horizontal no editor de conteúdo
     */
    function enable_more_editor_buttons($buttons) {
        $buttons[] = 'hr';
        return $buttons;
    }


    /**
     * Faz modificações no padrão da URL gerada pelo WP
     */
    function wp_get_attachment_link( $attachment_link ) {
        $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        $replacement = '<a$1rel="lightbox[gallery]" data-lightbox="gallery" href=$2$3.$4$5$6>';

        $attachment_link = preg_replace($pattern, $replacement, $attachment_link);
        return $attachment_link;
    }


    /**
     * Faz modificações no content gerada pelo WP
     */
    function addlightboxrel_replace ($content) {
    	global $post;

        $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        $replacement = '<a$1rel="lightbox[%LIGHTID%]" data-lightbox="gallery"  href=$2$3.$4$5$6</a>';

        $content = preg_replace($pattern, $replacement, $content);

        $content = str_replace("%LIGHTID%", $post->ID, $content);

        return $content;
    }


    /**
     * Modifica o padrão da galeria do wordpress
     */
    function shortcode_gallery_change( $atts ) {
        $atts['link'] = 'file';
        return gallery_shortcode( $atts );
    }
}
new Class_Post_Option();