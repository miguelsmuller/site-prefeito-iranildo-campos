<?php
if ( !is_user_logged_in() ) {
    $pageNew = get_option( 'configUsuarios' );
    $pageNew = $pageNew['pageEditUser'];
    wp_redirect( get_page_link($pageNew), 302 );
}else{
    include dirname( __FILE__  ) . '/assets/components/recaptcha/recaptchalib.php';

    $retorno    = '';
    if (!empty($_POST)) {
        $privatekey = get_field('key_recaptcha_secret', 'option');

        $resp = recaptcha_check_answer ($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {

            $retorno = '<div class="alert alert-error alert-block top-alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <div class="container">
                                <strong>Capcha Error</strong> Os caracteres de validação não conferem.
                            </div>
                        </div>';

        } else {
            $nome   = $_POST['nome'];

            $userId = get_current_user_id();
            update_user_meta($userId, 'first_name', $nome);
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php wp_title( '|', true, 'right' ); ?></title>

        <?php wp_head();?>

    </head>
    <body  <?php body_class(); ?>>
        <?php echo $retorno; ?>
        <?php do_action( 'after_body' ); ?>

        <div class="container">

            <form action="" method="post" class="formContato validate span8 offset2" style="margin-top: 50px;">
                <h1>Editar usuário</h1>

                <div class="control-group">
                    <label class="control-label" for="nome">Nome: </label>
                    <div class="controls">
                        <input type="text" name="nome" class="span7" value="" placeholder="">
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="span6">
                        <?php
                            $publickey = get_field('key_recaptcha_public', 'option');
                            echo recaptcha_get_html($publickey);
                        ?>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <input type="submit" name="cmdEnviar" value="Enviar Formulario" class="btn btn-blue btn-large" style="margin-top: 40px;" />
                        </div>
                    </div>
                </div>

            </form>

        </div>

        <?php wp_footer(); ?>
    </body>
    </html>
<?php
}
