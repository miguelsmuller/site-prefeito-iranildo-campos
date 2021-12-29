<?php
class ClassWidgetMailChimp extends WP_Widget
{
    protected $MailChimp;

    function __construct(){
        // PEGA AS INFORMAÇÕES DE CONFIG DO PLUGIN
        $configOption = get_option( 'configMailChimp' );
        $API_KEY = isset( $configOption['API_KEY'] ) ? $configOption['API_KEY'] : '';
        $this->MailChimp = new \Drewm\MailChimp($configOption['API_KEY']);

        $widget_ops     = array( 'description' => '' );
        $control_ops    = array();

        $this->WP_Widget( 'widgetNewsletter', '- Widget Form Newsletter', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title'] );
        $label_button = isset( $instance['label_button'] ) ? $instance['label_button'] : '';
        $id_lista = isset( $instance['id_lista'] ) ? $instance['id_lista'] : '';

        if ( $title ) { echo $before_title . $title . $after_title; }

        $option = $this->MailChimp->call('helper/account-details');
        $user_id = ''; //$option['user_id'];
        ?>

<!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup">
<form action="http://devim.us7.list-manage.com/subscribe/post?u=<?php echo $user_id; ?>&amp;id=<?php echo $id_lista; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div class="form-group">
        <input type="text" value="" name="FNAME" class="form-control" id="mce-FNAME" placeholder="Nome">
    </div>
    <div class="form-group">
        <input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" placeholder="E-Mail">
    </div>
    <div id="mce-responses" class="form-group">
        <div class="response" id="mce-error-response" style="display:none"></div>
        <div class="response" id="mce-success-response" style="display:none"></div>
    </div>
    <input type="submit" value="<?php echo $label_button; ?>" name="subscribe" id="mc-embedded-subscribe" class="btn btn-theme btn-lg btn-block">
</form>
</div>
<!--End mc_embed_signup-->

        <?php

        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['id_lista'] = $new_instance['id_lista'] ;
        $instance['label_button'] = $new_instance['label_button'] ;

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Assinar Newsletter' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'label_button' ); ?>">Texto do Botão:</label>
            <input id="<?php echo $this->get_field_id( 'label_button' ); ?>" name="<?php echo $this->get_field_name( 'label_button' ); ?>" value="<?php echo $instance['label_button']; ?>" class="widefat" type="text" />
        </p>

        <p>
            <?php $listas = $this->MailChimp->call('lists/list'); ?>
            <label for="<?php echo $this->get_field_id( 'id_lista' ); ?>">Lista Associada:</label>
            <select id="<?php echo $this->get_field_id( 'id_lista' ); ?>" name="<?php echo $this->get_field_name( 'id_lista' ); ?>" class="widefat">
                <?php
                foreach ($listas['data'] as $key => $value) {
                    printf('<option value="%s" %s>%s</option>',
                        $value['id'],
                        selected( $value['id'], $instance['id_lista'], false),
                        $value['name']
                    );
                }
                ?>
            </select>
        </p>
    <?php
    }
}
