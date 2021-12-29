<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>

<?php
if (is_date()){
	$page_title = 'Notícias Publicadas no período de '. get_the_time('F \d\e\ Y');
}elseif (is_category()){
	$categoria = single_cat_title("", false);
	$page_title = 'Notícias publicadas na categoria "'. $categoria.'"';
}elseif (is_tag()){
	$categoria = single_cat_title("", false);
	$page_title = 'Notícias publicadas com a referência "'. $categoria.'"';
}else{
	$page_title = 'Notícias Publicadas';
}
?>

<main role="main">
	<div class="page-title">
		<div class="container-min">
			<div class="col-12">
				<h1><?php echo $page_title; ?></h1>
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
			<?php
				global $wp_query;

				$args['name'] = '';
				$args['pagename'] = '';
				$args = array_merge( $wp_query->query_vars, $args );

				query_posts( $args );

				$total_results = $wp_query->found_posts;
			?>

			<?php if ( have_posts() ) : ?>
			<div class="news-cards">
				<?php get_template_part( 'loop', 'archive' ); ?>
			</div>
			<?php else : endif;?>

		</div>
	</article>
</main>

<?php get_footer(); ?>
