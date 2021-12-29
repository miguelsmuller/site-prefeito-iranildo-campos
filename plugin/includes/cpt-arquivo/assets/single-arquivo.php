<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (have_posts()) : while (have_posts()) : the_post();
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
	    require_once (ABSPATH . '/wp-admin/includes/file.php');
	    WP_Filesystem();
	}

    //DADOS BÁSICOS DA PUBLICAÇÃO
    $postID              = get_the_ID();
    $title               = get_the_title();
    $permalink           = get_permalink();

    //META DADOS DA PUBLICAÇÃO
    $post_meta   = get_post_meta($postID);
    $file_name   = $post_meta['file_name'][0];
    $file_ext    = $post_meta['file_ext'][0];
    $file_path   = Class_CPT_Arquivo::url_sanitize($post_meta['file_path'][0]);
    $file_qtdown = empty($post_meta['file_qtdown'][0]) ? '0' : $post_meta['file_qtdown'][0];

    $status_post = get_post_status( $postID );

    if ( $status_post == 'pending' || $status_post == 'draft' ) {
        header('Content-type: text/html; charset=utf-8');
        echo "<meta http-equiv='refresh' content='3;url=$permalink/action/down/'>";
        echo '<br/>Esse download existe mais se encontra indisponível no momento. Tente novamente mais tarde.';
        wp_redirect( wp_login_url( get_actual_url() ), 302 );
    } else {

        if ( (($status_post == 'private') && (is_user_logged_in())) || ($status_post == 'publish') ){
            $action = get_query_var( 'action' );

            if ($action == 'down'){
                if (headers_sent()) {
                    header( "refresh:5;url=site_url()" );
                    echo 'HTTP header already sent';
                }else {
                    if (!is_file($file_path)) {
                        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
                        echo 'File not found';
                        header( "refresh:5;url=site_url()" );

                    } else if (!is_readable($file_path)) {
                        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
                        echo 'File not readable';
                        header( "refresh:5;url=site_url()" );

                    } else {

                        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');

                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header("Content-Disposition: attachment; filename=\"".$title.".".$file_ext."\"");
                        //header('Content-Disposition: attachment; filename="'.$title.'.'.$file_ext.'"');
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: public');
                        header('Pragma: no-cache');
                        header('Content-Length: ' . filesize($file_path));

                        //ob_end_flush();
                        ob_clean();
                        flush();

                        echo $wp_filesystem->get_contents($file_path); // same @readfile($file_path);
                    }
                }

            } else {
                if ($file_ext == 'pdf'){

                    header('Content-type: application/pdf');
                    echo $wp_filesystem->get_contents($file_path); // same readfile($file_path);

                }elseif ( ($file_ext == 'jpeg') || ($file_ext == 'jpg') || ($file_ext == 'gif') || ($file_ext == 'png') ){

                    ob_clean();
                    header('Content-type: image/jpg');
                    echo $wp_filesystem->get_contents($file_path); // same readfile($file_path);

                }elseif ( $file_ext == 'txt' ){

                    header('Content-type: text/plain');
                    echo $wp_filesystem->get_contents($file_path); // same readfile($file_path);

                }else{
                    header('Content-type: text/html; charset=utf-8');
                    echo "<meta http-equiv='refresh' content='3;url=$permalink/action/down/'>";
                    echo '<br/>O navegador não tem suporte para abrir esse tipo de documento. <br/> Você será encaminhado ao download em alguns segundos.';
                }
            }
        }else{
            header('Content-type: text/html; charset=utf-8');
            echo "<meta http-equiv='refresh' content='3;url=$permalink/action/down/'>";
            echo '<br/>Você precisa ser um membro registrado para poder acessar esse arquivo.';
            wp_redirect( esc_url( site_url() ) , 302 );
        }
    }

endwhile; else: endif;
?>