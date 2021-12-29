<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_CPT_Slide
{
    /**
	 * Class construct
	 */
    public function __construct(){
        // Actions
        add_action( 'init', array( &$this, 'init_post_type' ), 100);
        add_action( 'admin_head', array( &$this, 'admin_head' ));

        // Filters
        add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages' ));
        add_filter( 'enter_title_here', array( &$this, 'enter_title_here'));

        // Mudança das colunas do WP-ADMIN
        add_filter( 'manage_edit-slide_columns', array( &$this, 'create_custom_column' ));
        add_action( 'manage_slide_posts_custom_column', array( &$this, 'manage_custom_column' ));
        add_filter( 'manage_edit-slide_sortable_columns', array( &$this, 'manage_sortable_columns' ));
    }


    /**
     * Cria o tipo de post slide
     */
    function init_post_type() {
        register_post_type( 'slide',
            array(
                'labels' => array(
                    'name'               => 'Slides',
                    'singular_name'      => 'Slide',
                    'add_new'            => 'Adicionar slide',
                    'add_new_item'       => 'Adicionar slide',
                    'edit_item'          => 'Editar slide',
                    'new_item'           => 'Novo slide',
                    'view_item'          => 'Ver slide',
                    'search_items'       => 'Buscar slide',
                    'not_found'          => 'Nenhuma slide encontrado',
                    'not_found_in_trash' => 'Nenhuma slide encontrado na lixeira',
                    'parent'             => 'Slides',
                    'menu_name'          => 'Slides'
                ),

                'hierarchical'       => false,
                'public'             => false,
                'query_var'          => true,
                'supports'           => array('title'),
                'has_archive'        => false,
                'capability_type'    => 'post',
                'menu_icon'          => 'dashicons-images-alt2',
                'show_ui'            => true,
                'show_in_menu'       => true,
            )
        );

        if(function_exists("register_field_group")) {
            register_field_group(array (
                'id' => 'acf_slide',
                'title' => 'Slide',
                'fields' => array (
                    array (
                        'key' => 'field_54b5cb6a1d6fa',
                        'label' => 'Imagem',
                        'name' => 'thumbnail',
                        'type' => 'image',
                        'required' => 1,
                        'save_format' => 'object',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                    array (
                        'key' => 'field_54b5cb761d6fb',
                        'label' => 'Tipo de Destino',
                        'name' => 'tipo_destino',
                        'type' => 'radio',
                        'required' => 1,
                        'choices' => array (
                            'interno' => 'Destino Interno',
                            'externo' => 'Destino Externo',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => '',
                        'layout' => 'horizontal',
                    ),
                    array (
                        'key' => 'field_54b5cb781d6fc',
                        'label' => 'Destino Externo',
                        'name' => 'destino_externo',
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
                        'conditional_logic' => array (
                            'status' => 1,
                            'rules' => array (
                                array (
                                    'field' => 'field_54b5cb761d6fb',
                                    'operator' => '==',
                                    'value' => 'externo',
                                ),
                            ),
                            'allorany' => 'all',
                        ),
                    ),
                    array (
                        'key' => 'field_54b5cb791d6fd',
                        'label' => 'Destino Interno',
                        'name' => 'destino_interno',
                        'type' => 'post_object',
                        'required' => 0,
                        'conditional_logic' => array (
                            'status' => 1,
                            'rules' => array (
                                array (
                                    'field' => 'field_54b5cb761d6fb',
                                    'operator' => '==',
                                    'value' => 'interno',
                                ),
                            ),
                            'allorany' => 'all',
                        ),
                        'post_type' => array (
                            0 => 'post',
                            1 => 'page',
                            2 => 'evento',
                            3 => 'arquivo',
                        ),
                        'taxonomy' => array (
                            0 => 'all',
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                    ),
                    array (
                        'key' => 'field_54b5cb7b1d6fe',
                        'label' => 'Abrir em nova Janela',
                        'name' => 'target',
                        'type' => 'checkbox',
                        'choices' => array (
                            'sim' => 'Sim, eu gostaria que essa página fosse aberta em uma nova janela',
                        ),
                        'default_value' => '',
                        'layout' => 'vertical',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'slide',
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
                        1 => 'the_content',
                        2 => 'excerpt',
                        3 => 'custom_fields',
                        4 => 'discussion',
                        5 => 'comments',
                        6 => 'revisions',
                        7 => 'slug',
                        8 => 'author',
                        9 => 'format',
                        10 => 'featured_image',
                        11 => 'categories',
                        12 => 'tags',
                        13 => 'send-trackbacks',
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
     * Inclui código CSS no painel administrativo
     */
    function admin_head() {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'slide' ){
        ?>
            <style type="text/css" media="screen">
                .column-featured_image{
                    width: 300px;
                }
                .misc-pub-visibility,
                .misc-pub-curtime{
                    display: none;
                }
                .misc-pub-section {
                    padding: 6px 10px 18px;
                }
                .label-red,
                .label-green,
                .label-gray{
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
                .label-gray{
                    background-color: #777;
                }
            </style>
        <?php
        }
        ?>
    <?php
    }


    /**
     * Personaliza as mensagens do processo de salvamento
     */
    function post_updated_messages( $messages ) {
        global $post;
        $postDate = date_i18n(get_option('date_format'), strtotime( $post->post_date ));

        $messages['slide'] = array(
            1  => '<strong>Slide</strong> atualizado com sucesso',
            6  => '<strong>Slide</strong> publicado com sucesso',
            9  => sprintf('<strong>Slide</strong> agendando para <strong>%s</strong>', $postDate),
            10 => 'Rascunho do <strong>Slide</strong> atualizado'
        );
        return $messages;
    }


    /**
    * Altera o placeholder do titulo
    */
    function enter_title_here( $title ){
        $screen = get_current_screen();
        if  ( $screen->post_type == 'slide' ) {
            $title = 'Entre com o nome do slide aqui';
        }
        return $title;
    }


    /**
     * Cria uma coluna na lista de slides do painel administrativo
     */
    function create_custom_column( $columns ) {
        global $post;

        $new = array();
        foreach($columns as $key => $title) {
            if ( $key=='title' )
                $new['featured_image'] = 'Slide';
            if ( $key=='date' ){
                $new['status'] = 'Situação';
                $new['link']    = 'Link';
            }
            $new[$key] = $title;
        }
    return $new;
    }


    /**
     * Inseri valor na coluna especifica da listagem do painel administrativo
     */
    function manage_custom_column( $column ) {
        global $post;

        $status_post = get_post_status( $post->ID );

        switch( $column ) {
            case 'featured_image' :
                $thumbnail = get_field('thumbnail');

                if( $thumbnail ) {
                    if ( in_array( 'slide', get_intermediate_image_sizes() )){
                        $new_url = wp_get_attachment_image_src($thumbnail['id'], 'slide');
                        $thumbnail['url'] = $new_url[0];
                    }else{
                        $new_url = wp_get_attachment_image_src($thumbnail['id'], 'thumbnail');
                        $thumbnail['url'] = $new_url[0];
                    }

                    $url_edit_post = site_url() .'/wp-admin/post.php?post='.$post->ID.'&action=edit';
                    echo '<a href="'. $url_edit_post .'"><img width="100%" src="'. $thumbnail['url'] .'" /></a>';
                }
                break;

            case 'status' :
                if ($status_post == 'pending' || $status_post == 'draft') {
                    echo '<span class="label-red">Rascunho</span>';
                }else{
                    echo '<span class="label-green">Publicado</span>';
                }
                break;

            case 'link' :
             echo get_field('tipo_destino');
                if (get_field('tipo_destino') == 'interno'){
                    $destino_interno = get_field('destino_interno');
                    echo '<span class="label-gray">Interno</span><br><br><a href="'. get_permalink( $destino_interno->ID ) .'"target="_blank">['. $destino_interno->post_type . '] - ' . $destino_interno->post_title .'</a>';
                }else{
                    echo '<span class="label-gray">Externo</span><br><br><a href="'. get_field('destino_externo') .'" target="_blank">'. get_field('destino_externo') .'</a>';
                }
                break;
        }
    }


    /**
     * Permite que a coluna seja ordenada de acordo com o valor
     */
    function manage_sortable_columns( $columns ){
        $columns['status'] = 'status';
        return $columns;
    }
}
new Class_CPT_Slide();
