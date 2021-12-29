<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article class="news-cards-item">
	<?php
		$image = get_post_thumbnail_id();
		$image = wp_get_attachment_image_src( $image,'noticia');
	?>
	<?php if ( $image ) : ?>
		<a href="<?php the_permalink(); ?>"><img src="<?php echo $image[0] ?>" alt="<?php the_title(); ?>"></a>
	<?php else : ?>
		<a href="<?php the_permalink(); ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/dist/no-image.png" alt="<?php the_title(); ?>"></a>
	<?php endif; ?>

	<div class="news-cards-content">
		<div class="news-cards-info">
			<span class="icon-calendario"></span> <?php echo get_the_time('d/m/Y') ?>
		</div>
		<a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
		<div class="news-cards-footer">
			<a href="<?php the_permalink(); ?>">Leia mais <span class="icon-chevron"></span></a>
		</div>
	</div>
</article>
<?php endwhile; else : endif;?>
