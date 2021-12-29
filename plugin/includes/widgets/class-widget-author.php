<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Widget_Author extends WP_Widget
{
	function __construct(){
		$widget_ops  = array( 'description' => '' );
		$control_ops = array();

		parent::__construct( 'widget-author', '- Autor', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$post = get_queried_object();

		if ( is_object($post) && isset($post->ID) ) {
			if( get_field('public_user', 'user_'.$post->post_author) ){
				extract( $args );
				$title = apply_filters('widget_title', $instance['title'] );

				$default_avatar = '';
				$default_avatar = apply_filters( 'default_avatar', $default_avatar );

				echo '<section class="sidebar-panel sidebar-author">';
				?>
					<a class="author-img">
						<?php echo get_avatar( $post->post_author, '460', $default_avatar ); ?>
					</a>
					<div class="author-title">
						<h2><?php echo $title; ?></h2>
					</div>
					<div class="author-intro">
						<?php echo get_the_author_meta( 'description', $post->post_author ); ?>
					</div>
					<a href="<?php echo get_author_posts_url( $post->post_author ); ?>" class="btn btn-blue-light author-button">Leia mais</a>
				<?php
				echo '</section>';
			}
		}
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
		$defaults = array('title' => 'Sobre o autor');
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
	<?php
	}
}
