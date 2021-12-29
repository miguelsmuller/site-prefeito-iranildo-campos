<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Navigator
{
    /**
     * Construtor da Classe
     */
	public function __construct() {
        // Actions
		add_action('after_body', array( &$this, 'after_body' ) );
	}


    /**
     * Informa ao visitante a necessidade de atualização do browser (IE por exemplo)
     */
    public function after_body() {
        if ( empty( $_SERVER['HTTP_USER_AGENT'] ) )
            return false;

        $key = md5( $_SERVER['HTTP_USER_AGENT'] );
        $response = get_site_transient('browser_' . $key);

        // Se o próprio navegador não disser que está desatualizado
        if ( false === $response ) {
            global $wp_version;

            $options = array(
                'body'          => array( 'useragent' => $_SERVER['HTTP_USER_AGENT'] ),
                'user-agent'    => 'WordPress/' . $wp_version . '; ' . home_url()
            );
            $response = wp_remote_post( 'http://api.wordpress.org/core/browse-happy/1.1/', $options );

            if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 )
                return false;

            $response = maybe_unserialize( wp_remote_retrieve_body( $response ) );
            if ( ! is_array( $response ) )
                return false;

            set_site_transient( 'browser_' . $key, $response, 604800 ); // cache for 1 week
        }

        // Mostra um aviso de navegador desatualizado
        if ( $response && $response['upgrade'] ) {
            if ( $response['insecure'] ){
                $msg = 'O navegador %name% que você está usando é inseguro. Tente atualizar através do link %update_url%';
                $msg = apply_filters( 'navigator_insecure', $msg );
            } else {
                $msg = 'O navegador %name% que você está usando está desatualizado. Tente atualizar através do link %update_url%';
                $msg = apply_filters( 'navigator_upgrade', $msg );
            }

            $msg = str_replace("%name%", esc_html( $response['name']), $msg);
            $msg = str_replace("%version%", esc_html( $response['version']), $msg);
            $msg = str_replace("%current_version%", esc_html( $response['current_version']), $msg);
            $msg = str_replace("%upgrade%", esc_html( $response['upgrade']), $msg);
            $msg = str_replace("%insecure%", esc_html( $response['insecure']), $msg);
            $msg = str_replace("%update_url%", esc_html( $response['update_url']), $msg);
            $msg = str_replace("%img_src%", esc_html( $response['img_src']), $msg);
            $msg = str_replace("%img_src_ssl%", esc_html( $response['img_src_ssl']), $msg);
            ?>

            <div class="alert alert-warning alert-top">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="container">
                    <?php echo $msg ?>
                </div>
            </div>

            <?php
        }
    }
}
new Class_Navigator();