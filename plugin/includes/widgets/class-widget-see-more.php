<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Widget_See_More extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();

        parent::__construct( 'widget-veja-mais', '- Tags/Categorias/Periodo', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );

        echo $before_widget;
        echo $before_title;
        if ( $title ) { echo $title; }
        echo $after_title;

        echo '<div class="widget-veja-mais">';

            $tags = isset( $instance['tags'] ) ? $instance['tags'] : false;
            $tags_quant = $instance['tags_quant'];
            $categorias = isset( $instance['categorias'] ) ? $instance['categorias'] : false;
            $periodo = isset( $instance['periodo'] ) ? $instance['periodo'] : false;

            if ($tags || $categorias || $periodo){
                echo '<ul id="myTab" class="nav nav-pills nav-justified">';
                    if ($tags){ echo '<li class="active"><a href="#tags" data-toggle="tab">Referências</a></li>'; }
                    if ($categorias){ echo '<li><a href="#categorias" data-toggle="tab">Categorias</a></li>'; }
                    if ($periodo){ echo '<li><a href="#periodo" data-toggle="tab">Periodo</a></li>'; }
                echo '</ul>';

                echo '<div id="myTabContent" class="tab-content">';
                    if ($tags){
                        echo '<div class="tab-pane fade in active widget-tagcloud" id="tags">';
                        $args = array(
                            'number'    => $tags_quant,
                            'orderby'   => 'count',
                            'order'     => 'DESC'
                        );
                        wp_tag_cloud($args);
                        echo '</div>';
                    }

                    if ($categorias){
                        echo '<div class="tab-pane fade widget-list" id="categorias">';
                        $args = array(
                            'type'          => 'post',
                            'parent'      => 0,
                            'hierarchical'  => 1,
                            'orderby'       => 'slug',
                            'order'         => 'ASC',
                        );
                        $categories = get_categories($args);
                        echo '<ul class="menu">';
                            foreach($categories as $item){
                                echo '<li class="item">';
                                //echo '<a href="'. get_category_link( $item->term_id ) .'"><span class="item-desc">'. $item->name .'</span><span class="item-quant">'. $item->count .'</span></a>';
                                echo '<a href="'. get_category_link( $item->term_id ) .'"><span class="item-desc">'. $item->name .'</span></a>';
                                echo '</li>';
                            }
                        echo '</ul>';
                        echo '</div>';
                    }

                    if ($periodo){
                        echo '<div class="tab-pane fade widget-list" id="periodo">';
                            echo '<ul class="menu">';
                                wp_get_archives( array( 'type' => 'monthly', 'limit' => 12 ) );
                            echo '</ul>';
                        echo '</div>';
                    }
                echo '</div>';
            }
        echo '</div>';
        echo $after_widget;


    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['tags_quant'] = $new_instance['tags_quant'] ;
        $instance['tags'] = $new_instance['tags'] ;
        $instance['categorias'] = $new_instance['categorias'] ;
        $instance['periodo'] = $new_instance['periodo'] ;

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Mais Publicações em:', 'tags_quant' => '15' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        wp_enqueue_media();

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['tags']), true ); ?> id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>">Mostrar Tags</label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'tags_quant' ); ?>">Quantidade de Tags</label>
            <input id="<?php echo $this->get_field_id( 'tags_quant' ); ?>" name="<?php echo $this->get_field_name( 'tags_quant' ); ?>" value="<?php echo $instance['tags_quant']; ?>" class="widefat" type="number" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['categorias']), true ); ?> id="<?php echo $this->get_field_id( 'categorias' ); ?>" name="<?php echo $this->get_field_name( 'categorias' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'categorias' ); ?>">Mostrar Categorias</label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['periodo']), true ); ?> id="<?php echo $this->get_field_id( 'periodo' ); ?>" name="<?php echo $this->get_field_name( 'periodo' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'periodo' ); ?>">Mostrar Período</label>
        </p>


    <?php
    }
}

?>