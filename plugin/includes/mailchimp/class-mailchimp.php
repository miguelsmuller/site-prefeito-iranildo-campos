<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Mailchimp
{
    protected $MailChimp;
    protected $configOption;

    public function __construct() {
    	include 'assets/Mailchimp.php';
		include 'widgets/class-widget-mailchimp.php';

        if( false == get_option( 'configMailChimp' ) ) {
            add_option( 'configMailChimp' );
        }
        $this->configOption = get_option( 'configMailChimp' );

        // Action
        add_action('admin_init', array( &$this, 'add_form' ));
        add_action('admin_menu', array( &$this, 'add_sub_menu' ));

        // Action
        if ( !empty($this->configOption['API_KEY']) ){
            $this->MailChimp = new \Drewm\MailChimp($this->configOption['API_KEY']);

            add_action('user_register', array( &$this, 'user_register' ));
            add_action('profile_update', array( &$this, 'user_update' ), 10, 2);

            add_action('widgets_init', array( &$this, 'widgets_init' ));
        }
    }

    /**
     * Cria um formulário pra ser usada pra configuração do tema
     */
    function add_form() {
        add_settings_section(
            'section',
            'Configuração MailChimp',
            '',
            'configMailChimp'
        );
            add_settings_field(
                'apiKey',
                'API Key:',
                function(){
                    $API_KEY = isset( $this->configOption['API_KEY'] ) ? $this->configOption['API_KEY'] : '';
                    echo '<input type="text" id="API_KEY" name="configMailChimp[API_KEY]" value="'. $API_KEY .'" class="regular-text">';
                },
                'configMailChimp',
                'section'
            );
            add_settings_field(
                'insertOnNewUser',
                'Lista a adicionar usuário quando ele fizer registro:',
                function(){
                    $insertOnNewUser = isset( $this->configOption['insertOnNewUser'] ) ? $this->configOption['insertOnNewUser'] : '';

                    echo '<select id="insertOnNewUser" name="configMailChimp[insertOnNewUser]">';
                        echo '<option value="0"'. selected( '0', $insertOnNewUser, false) .'>— Selecionar —</option>';
                        if ( !empty($this->configOption['API_KEY']) ){
                            $listas = $this->MailChimp->call('lists/list');
                            foreach ($listas['data'] as $key => $value) {
                                printf('<option value="%s" %s>Lista "%s"</option>',
                                    $value['id'],
                                    selected( $value['id'], $insertOnNewUser, false),
                                    $value['name']
                                );
                            }
                        }
                    echo '</select>';
                },
                'configMailChimp',
                'section'
            );
        register_setting(
            'configMailChimp',
            'configMailChimp'
        );
    }


    /**
     * Cria um item do menu para o formulário criado
     */
    function add_sub_menu() {
        add_submenu_page(
            'options-general.php',// $parent_slug
            'Lista de Email',// $page_title
            'Lista de Email',// $menu_title
            'manage_options',// $capability
            'lista_email',// $menu_slug
            function(){
                ?>
                <div class="wrap">
                    <div id="icon-options-general" class="icon32"></div>
                    <h2>Configurações Gerais</h2>
                    <form method="post" action="options.php">
                        <?php
                        settings_fields( 'configMailChimp' );
                        do_settings_sections( 'configMailChimp' );
                        submit_button();
                        ?>
                    </form>
                </div>
            <?php
            }
        );
    }


    /**
     * Quando um novo usuário é criado
     */
    function user_register($userId) {
        $this->insert_into_list(get_userdata( $userId ));
    }


    /**
     * Quando um usuário é atualizado
     */
    function user_update( $userId, $oldUserData ) {
        $this->insert_into_list(get_userdata( $userId ));
    }


    /**
     * Função auxiliar para inserir na lista do mailchimp
     */
    function insert_into_list($argsUser) {

        $insertOnNewUser = isset( $this->configOption['insertOnNewUser'] ) ? $this->configOption['insertOnNewUser'] : '';
        if ( !empty($insertOnNewUser) ){
            $resultInsert = $this->MailChimp->call('/lists/subscribe',array(
                'id'                => $insertOnNewUser,
                'email'             => array('email'=>$argsUser->user_email),
                'merge_vars'        => array('FNAME'=>$argsUser->first_name, 'LNAME'=>$argsUser->last_name),
                'double_optin'      => false,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
            ));
            update_user_meta($argsUser->ID, 'codMailChimp', $resultInsert['leid']);
        }
    }


    /**
     * Registra o widget
     */
    function widgets_init() {
        register_widget( 'ClassWidgetMailChimp' );
    }
}
new Class_Mailchimp();