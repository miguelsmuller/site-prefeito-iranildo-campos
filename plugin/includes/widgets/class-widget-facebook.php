<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Widget_Facebook extends WP_Widget
{
    function __construct(){
        $widget_ops  = array( 'description' => '' );
        $control_ops = array();

        parent::__construct( 'widget-facebook', '- Facebook', $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title'] );

        $facebook_appid = isset( $instance['facebook_appid'] ) ? $instance['facebook_appid'] : '1568414253402399';
        $facebook_page = isset( $instance['facebook_page'] ) ? $instance['facebook_page'] : 'FacebookDevelopers';

        echo '<div class="widget-facebook">';

        echo $before_widget;
        echo $before_title;
        if ( $title ) { echo $title; }
        echo $after_title;
        ?>
            <div class="facebook-container">
                <iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/<?php echo $facebook_page; ?>&amp;width=243&amp;height=250&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=<?php echo $facebook_appid; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:243px; height:250px;" allowTransparency="true"></iframe>
            </div>
        <?php
        echo $after_widget;

        echo '</div>';
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['facebook_appid'] = strip_tags( $new_instance['facebook_appid'] );
        $instance['facebook_page']  = strip_tags( $new_instance['facebook_page'] );

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Facebook Oficial', 'facebook_appid'=> '1568414253402399', 'facebook_page'=> 'FacebookDevelopers' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        wp_enqueue_media();

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'facebook_appid' ); ?>">App ID:</label>
            <input id="<?php echo $this->get_field_id( 'facebook_appid' ); ?>" name="<?php echo $this->get_field_name( 'facebook_appid' ); ?>" value="<?php echo $instance['facebook_appid']; ?>" class="widefat" type="text" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'facebook_page' ); ?>">Página:</label>
            <input id="<?php echo $this->get_field_id( 'facebook_page' ); ?>" name="<?php echo $this->get_field_name( 'facebook_page' ); ?>" value="<?php echo $instance['facebook_page']; ?>" class="widefat" type="text" />
        </p>
    <?php
    }
}
