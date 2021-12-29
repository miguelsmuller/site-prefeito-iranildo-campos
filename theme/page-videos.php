<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/*
Template Name: Página Videos
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
		<div class="container">
			<div class="col-12">
<?php
$videos = new WP_Query(array(
	'post_type'      => 'video',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'posts_per_page' => -1,
	'no_found_rows'  => true
));
?>

<?php if ( $videos->have_posts() ) : ?>
	<div class="videos-cards">
		<?php while ( $videos->have_posts() ) : $videos->the_post(); ?>
			<?php
			$post_meta   = get_post_meta(get_the_id());
			$title       = isset($post_meta['video_title'][0]) ? $post_meta['video_title'][0] : '';
			$thumbnail   = isset($post_meta['video_thumbnail'][0]) ? $post_meta['video_thumbnail'][0] : '';
			?>
			<article class="videos-cards-item">
				<a class="modal" data-type="youtube" data-autoplay="true" href="http://youtu.be/<?php the_title() ?>">
					<img src="<?php echo $thumbnail ?>" alt="<?php echo $title ?>">
					<div class="videos-cards-overlay"></div>
					<div class="videos-cards-content">
						<span class="icon-play"></span>
						<h2><?php echo $title ?></h2>
					</div>
				</a>
			</article>
		<?php endwhile;?>
	</div>
<?php endif;?>

			</div>
		</div>
	</article>
</main>

<?php endwhile; else : endif;?>

<?php get_footer(); ?>
