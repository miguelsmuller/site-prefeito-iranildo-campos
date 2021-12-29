<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/*
Template Name: Página Imprensa
*/
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<main role="main">
	<div class="page-title">
		<div class="container-min">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
				<?php
				$subtitle = get_field('subtitulo');
				if (!empty($subtitle)) {
					echo '<h2>'. $subtitle .'</h2>';
				}
				?>
			</div>
		</div>
	</div>
	<article class="page-article">
		<div class="container-min">
			<div class="column-form">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
</main>

<?php endwhile; else : endif;?>

<?php get_footer(); ?>
