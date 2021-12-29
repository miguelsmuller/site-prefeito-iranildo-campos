<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_User
{
    /**
     * Construtor da Classe
     */
    public function __construct() {
        if( false == get_option( 'config_user' )) {
            add_option( 'config_user' );
        }

        // Actions
        add_action( 'wp_authenticate', array( &$this, 'wp_authenticate') );
        add_action( 'after_switch_theme', array( &$this, 'after_switch_theme' ));
        add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ));
        add_action( 'init', array( &$this, 'init'));
        add_action( 'admin_init', array( &$this, 'admin_init'), 1 );
        add_action( 'admin_menu', array( &$this, 'admin_menu'));
        add_action( 'template_redirect', array( &$this, 'template_redirect') );
        add_action( 'pre_user_query', array( &$this, 'pre_user_query' ));
        add_action( 'admin_head', array( &$this, 'hide_menu' ));

        // Filters
        add_filter( 'template_include', array( &$this, 'template_include') );
        add_filter( 'user_contactmethods', array( &$this, 'user_contactmethods' ),10,1);
        add_filter( 'editable_roles', array( &$this, 'editable_roles') );
        add_filter( 'map_meta_cap', array( &$this, 'map_meta_cap' ),10,4);

        // Mudança das colunas do WP-ADMIN
        add_filter( 'manage_users_columns', array( &$this, 'manage_users_columns') );
        add_action( 'manage_users_custom_column', array( &$this, 'manage_users_custom_column'), 10, 3 );

        // Remove a notificação de atualização
        if(! current_user_can('update_core')){
            add_filter('pre_site_transient_update_core', array( &$this, 'remove_core_updates'));
            add_filter('pre_site_transient_update_plugins', array( &$this, 'remove_core_updates'));
            add_filter('pre_site_transient_update_themes', array( &$this, 'remove_core_updates'));
        }
    }


    /**
     * Permite o login através do email
     */
    function wp_authenticate(&$username) {
        $user = get_user_by( 'email', $username );

        if(!empty($user->user_login))
            $username = $user->user_login;
    }


    /**
     * Permite o login através do email
     */
    function after_switch_theme(){
    	global $wpdb;
	    /**
	    * ATUALIZA USUARIO
	    */
	    $wpdb->get_results("UPDATE $wpdb->usermeta SET user_id = '2' WHERE user_id = '1';");
	    $wpdb->get_results("UPDATE $wpdb->users SET ID = '2' WHERE ID = '1';");
    }

    /**
    * Cria o novo capabilities
    */
    function after_setup_theme() {
        $editor = get_role('editor');
        add_role('manager','Gerente', $editor->capabilities);
        $manager = get_role('manager');
        if (!$manager->has_cap( 'edit_theme_options' ) ) {
            $manager->add_cap( 'edit_theme_options' );
        }
        if (!$manager->has_cap( 'list_users' ) ) {
            $manager->add_cap( 'list_users' );
        }
        if (!$manager->has_cap( 'create_users' ) ) {
            $manager->add_cap( 'create_users' );
        }
        if (!$manager->has_cap( 'delete_users' ) ) {
            $manager->add_cap( 'delete_users' );
        }
    }


    /**
     * Modifica a URL padrão do usuário/autor e cria a role de gerente
     */
    function init() {
        global $wp_rewrite;
        $wp_rewrite->author_base = 'perfil';
        $wp_rewrite->search_base = 'find';
        $wp_rewrite->pagination_base = 'p';

        if( function_exists('acf_add_local_field_group') ):
			acf_add_local_field_group(array (
				'key' => 'group_562ab18d84210',
				'title' => 'Configuração do Usuário',
				'fields' => array (
					array (
						'key' => 'field_562ab1a3206e7',
						'label' => 'Usuário Público',
						'name' => 'public_user',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => 'Sim, esse usuário é publico',
						'default_value' => 0,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'user_form',
							'operator' => '==',
							'value' => 'all',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		endif;
    }


    /**
     * Desabilita o acesso ao painel administrativo
     * Cria um formulário pra ser usada pra configuração do usuário
     */
    function admin_init() {
        // DESABILITA O WP-ADMIN
        if ( ! current_user_can( 'publish_posts' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            wp_redirect( site_url() );
            exit;
        }

        // CRIA O FORMULÁRIO
        add_settings_section(
            'section_user',
            'Configuração de adição/edição',
            null,
            'page_user'
        );

        add_settings_field(
            'pageNewUser',
            'Página para inclusão de Usuário:',
            function(){
                $config_user = get_option( 'config_user' );
                $pageNewUser = isset( $config_user['pageNewUser'] ) ? $config_user['pageNewUser'] : '';
                echo '<select id="pageNewUser" name="config_user[pageNewUser]">';
                    echo '<option value="0"'. selected( '0', $pageNewUser, false) .'>— Selecionar —</option>';
                    $pages = get_pages();
                    foreach ( $pages as $page ) {
                        printf('<option value="%s" %s>%s</option>',
                            $page->ID,
                            selected( $page->ID, $pageNewUser, false),
                            $page->post_title
                        );
                    }
                echo '</select>';
            },
            'page_user',
            'section_user'
        );

        add_settings_field(
            'pageEditUser',
            'Página para edição de usuário:',
            function(){
                $config_user = get_option( 'config_user' );
                $pageEditUser = isset( $config_user['pageEditUser'] ) ? $config_user['pageEditUser'] : '';
                echo '<select id="pageEditUser" name="config_user[pageEditUser]">';
                    echo '<option value="0"'. selected( '0', $pageEditUser, false) .'>— Selecionar —</option>';
                    $pages = get_pages();
                    foreach ( $pages as $page ) {
                        printf('<option value="%s" %s>%s</option>',
                            $page->ID,
                            selected( $page->ID, $pageEditUser, false),
                            $page->post_title
                        );
                    }
                echo '</select>';
            },
            'page_user',
            'section_user'
        );

        register_setting(
            'page_user',
            'config_user'
        );
    }


    /**
     * Cria um item do menu para o formulário criado
     */
    function admin_menu() {
        add_submenu_page(
            'options-general.php', //$parent_slug
            'Usuário', //$page_title
            'Usuário', //$menu_title
            'administrator', //$capability
            'config-user', //$menu_slug
            function () {
                ?>
                <div class="wrap">
                    <div id="icon-options-general" class="icon32"></div>
                    <h2>Configurações para Usuários</h2>
                    <form method="post" action="options.php">
                        <?php
                        settings_fields( 'page_user' );
                        do_settings_sections( 'page_user' );
                        submit_button();
                        ?>
                    </form>
                </div>
                <?php
            }
        );
    }


    /**
     * Redireciona a uma outra página caso tenha necessidade
     */
    function template_redirect()
    {
        $config_user = get_option( 'config_user' );
        $pageNewUser = isset( $config_user['pageNewUser'] ) ? $config_user['pageNewUser'] : '';
        $pageEditUser = isset( $config_user['pageEditUser'] ) ? $config_user['pageEditUser'] : '';

        if( is_page( $pageNewUser ) && !empty($pageNewUser) && is_user_logged_in() )
        {
            $redirect = ($pageEditUser != '0') ? get_page_link($pageEditUser) : site_url();
            wp_redirect( $redirect, 302  );
            exit();
        }

        if( is_page( $pageEditUser ) && !empty($pageEditUser) && !is_user_logged_in() )
        {
            $redirect = ($pageNewUser != '0') ? get_page_link($pageNewUser) : site_url();
            wp_redirect( $redirect, 302  );
            exit();
        }
    }


    /**
     * Usado para carregador um template diferenciado para determinada página
     */
    function pre_user_query($user_search) {
        $user = wp_get_current_user();

        if ( $user->roles[0] != 'administrator' ) {
            global $wpdb;

            $user_search->query_where =
            str_replace('WHERE 1=1',
                "WHERE 1=1 AND {$wpdb->users}.ID IN (
                     SELECT {$wpdb->usermeta}.user_id as ID FROM $wpdb->usermeta
                        WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}user_level'
                        AND {$wpdb->usermeta}.meta_value <> 10)",
                $user_search->query_where
            );

        }
    }


    /**
     * Usado para carregador um template diferenciado para determinada página
     */
    function hide_menu() {
        if ( !current_user_can( 'manage_options' ) ) {
            remove_submenu_page( 'themes.php', 'themes.php' );
            remove_submenu_page( 'themes.php', 'widgets.php' );
        }
    }


    /**
     * Usado para carregador um template diferenciado para determinada página
     */
    function template_include( $template ) {
        $config_user = get_option( 'config_user' );
        $pageNewUser = isset( $config_user['pageNewUser'] ) ? $config_user['pageNewUser'] : '';
        $pageEditUser = isset( $config_user['pageEditUser'] ) ? $config_user['pageEditUser'] : '';

        if ( is_page( $pageNewUser ) && !empty($pageNewUser) ) {
            $new_template = locate_template( array( 'page-usuario-novo.php' ) ) ;
            if ( '' != $new_template ) {
                return $new_template;
            }else{
                return dirname( __FILE__  ) .'/class-user/page-usuario-novo.php';
            }
        }

        if ( is_page( $pageEditUser ) && !empty($pageEditUser) ) {
            $new_template = locate_template( array( 'page-usuario-editar.php' ) ) ;
            if ( '' != $new_template ) {
                return $new_template;
            }else{
                return dirname( __FILE__  ) .'/class-user/page-usuario-editar.php';
            }
        }

        return $template;
    }


    /**
     * Modifica os campos do perfil do usuário
     */
    function user_contactmethods( $user_contact ) {
        unset($user_contact['aim']);
        unset($user_contact['jabber']);

        return $user_contact;
    }


    /**
     * Modifica os campos do perfil do usuário
     */
    function editable_roles( $roles ){
        if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
          unset( $roles['administrator']);
        }
        return $roles;
    }


    /**
     * Modifica os campos do perfil do usuário
     */
    function map_meta_cap( $caps, $cap, $user_id, $args ){
        switch( $cap ){
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                if( isset($args[0]) && $args[0] == $user_id )
                    break;
                elseif( !isset($args[0]) )
                    $caps[] = 'do_not_allow';
                $other = new WP_User( absint($args[0]) );
                if( $other->has_cap( 'administrator' ) ){
                    if(!current_user_can('administrator')){
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            case 'delete_user':
            case 'delete_users':
                if( !isset($args[0]) )
                    break;
                $other = new WP_User( absint($args[0]) );
                if( $other->has_cap( 'administrator' ) ){
                    if(!current_user_can('administrator')){
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            default:
                break;
        }
        return $caps;
    }


    /**
     * Cria uma coluna na lista de usuários do painel administrativo
     */
    function manage_users_columns( $columns ) {
        $columns['user_registered'] = 'Data de registro';
        return $columns;

    }


    /**
     * Inseri valor na coluna especifica da listagem de usuários do painel administrativo
     */
    function manage_users_custom_column( $value, $column_name, $user_id ) {
        if ( 'user_registered' == $column_name ) {
            $userdata = get_userdata( $user_id );
            return date_i18n( get_option( 'date_format' ), strtotime( $userdata->user_registered ) );
        }
    }


    /**
     * Remove a notificação de atualização de atualização
     */
    function remove_core_updates(){
        global $wp_version;
        return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
    }
}
new Class_User();
