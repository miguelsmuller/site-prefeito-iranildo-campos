<?php
class Class_Widget_Evento extends WP_Widget
{
	function __construct(){
        $widget_ops  = array( 'description' => 'Mostra uma lista dos eventos cadastrados' );
        $control_ops = array();

        parent::__construct( 'widget-evento', '- Widget Evento', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );

        $evento = new WP_Query( array(
            'post_type'      => 'evento',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            'meta_query'     => array(
                array(
                    'key'     => 'data_inicio',
                    'compare' => '>=',
                    'value'   => date('Ymd'),
                    ),
            ),
            'meta_key' => 'data_inicio',
            'orderby'  => 'meta_value_num',
            'order'    => 'ASC'
        ) );

        echo $before_widget;
        echo '<div class="panel panel-default panel-eventos">';

        if ( $title ) { echo '<div class="panel-heading">' . '<a href="' .get_post_type_archive_link( 'evento' ).'">' . $title . '</a></div>'; }

        echo '<div class="panel-body">';
        echo '<ul class="event-list event-list-min">';
        if ( $evento->have_posts() ) : while ( $evento->have_posts() ) : $evento->the_post();

            $date_inicio = DateTime::createFromFormat('d/m/Y', get_field('data_inicio'));
            $dia_inicio =  $date_inicio->format('d');
            $mes_inicio =  $date_inicio->format('M');
            ?>
                <li class="event-list-item">
                        <div class="event-list-date">
                            <span class="event-list-day"><?php echo $dia_inicio;?></span>
                            <span class="event-list-month"><?php echo $mes_inicio;?></span>
                        </div>
                        <div class="event-list-info">
                            <?php the_title(); ?>
                        </div>
                </li>
            <?php
        endwhile; else :
            echo '<li class="event-list-no-item">Nenhum evento para os próximos dias</li>';
        endif;
        echo '</ul>';
        echo '</div>';

        echo '</div>';
        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Eventos Recentes' );
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
