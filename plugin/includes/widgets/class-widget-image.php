<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Widget_Image extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();

        parent::__construct( 'widget-imagem', '- Imagem', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );

        $url = $instance['url'];
    ?>

    <div class="panel panel-default panel-image ">

        <img src="<?php echo $url; ?>" class="img-responsive img-thumbnail" alt="">

    </div>

    <?php
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['url'] = strip_tags( $new_instance['url'] );

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'url' => 'Mais Publicações em:', 'tags_quant' => '15' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        wp_enqueue_media();

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'url' ); ?>">URL</label>
            <input id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" class="widefat" type="text" />
        </p>

    <?php
    }
}

?>