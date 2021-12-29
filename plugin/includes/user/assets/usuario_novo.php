<?php
if ( is_user_logged_in() ) {
    wp_redirect( siteUrl.'/usuario_editar', 302 );
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
            $email  = $_POST['email'];

            $userId = username_exists( $email );
            if ( !$userId and email_exists($email) == false ) {

                $random_password    = wp_generate_password( $length=6, $include_standard_special_chars=false );
                $userId             = wp_create_user( $email, $random_password, $email );

                if (is_int($userId)) {
                    update_user_meta($userId, 'first_name', $nome);

                    $mensagem = 'Senha de acesso: '.$random_password;

                    $ClassEmail = new ClassEmail();
                    $ClassEmail->setRemetenteNome(siteNome);
                    $ClassEmail->setRemetenteEmail('email@demominio.com.br');
                    $ClassEmail->setResponderPara('email@demominio.com.br');
                    $ClassEmail->setDestinatario($email);
                    $ClassEmail->setAssunto('Senha de acesso');
                    $ClassEmail->setMensagem($mensagem);

                    $status = $ClassEmail->enviarEmail();
                }

            }else{

                $retorno = '<div class="alert alert-error alert-block top-alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <div class="container">
                                    <strong>Email existente</strong> É necessário escolher um email que não esteja em uso.
                                </div>
                            </div>';
            }
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
                <h1>Novo usuário</h1>
                <div class="control-group">
                    <label class="control-label" for="email">Email: </label>
                    <div class="controls">
                        <input type="text" name="email" class="span7" value="" placeholder="">
                    </div>
                </div>

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
