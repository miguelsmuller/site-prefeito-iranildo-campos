<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="single-title">
	<?php
	if ( has_post_thumbnail() ) {
	    the_post_thumbnail();
	}
	?>
</div>

<main role="main">
	<article class="page-article">
		<div class="container-min">
			<div class="col-9 col-modified">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
			<div class="col-3">
				<?php dynamic_sidebar('sidebar-blog'); ?>
			</div>
		</div>
	</article>
</main>

<?php endwhile; else : endif;?>

<?php get_footer(); ?>
