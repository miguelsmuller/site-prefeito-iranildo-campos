<?php
class Class_Widget_Arquivo extends WP_Widget
{
    function __construct(){
        $widget_ops     = array( 'description' => '' );
        $control_ops    = array();

        parent::__construct( 'widgetArquivo', '- Widget Arquivo', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title'] );
        $pagina_facebook = isset( $instance['pagina_facebook'] ) ? $instance['pagina_facebook'] : 'FacebookDevelopers';

        if ( $title ) { echo $before_title . '<a href="' .get_post_type_archive_link( 'arquivo' ).'">' . $title . '</a>' . $after_title; }


        $args = array(
            'taxonomy'          => 'arquivos-categoria',
            'hide_empty'        => false,
            'title_li'          => ''
            );
        echo '<ul class="list-unstyled">';
            wp_list_categories( $args );
        echo '</ul>';


        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Categorias da Biblioteca' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        wp_enqueue_media();

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
    <?php
    }
}
