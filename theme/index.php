<?php get_header(); ?>

<main role="main">

	<section class="section-highlight" class="highlight-slide">

		<!-- SLIDE DE IMAGENS -->
		<?php
		$slide = new WP_Query(array(
			'post_type'      => 'slide',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => 'thumbnail',
			'meta_value'     => ' ',
			'meta_compare'   => '!='
		));
		?>

		<?php if ( $slide->have_posts() ) : ?>
		<div id="highlight-slide" class="highlight-slide">

			<?php while ( $slide->have_posts() ) : $slide->the_post(); ?>
			<div class="highlight-slide-item">
				<?php $thumbnail = get_field('thumbnail'); ?>
				<img src="<?php echo $thumbnail['url']; ?>" alt="Lorem ipsum">
			</div>
			<?php endwhile;?>

		</div>
		<?php endif;?>


		<!-- MENU DE ACESSO RÁPIDO -->
		<div class="container">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'menu-highlight-links',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => 'fallbackNoMenu',
				'walker'         => new Highlight_Links,
				'items_wrap'     => '<div class="highlight-links">%3$s</div>'
			));
			?>
		</div>

	</section>


	<!-- DEPOIMENTOS -->
	<?php
	$depoimento = new WP_Query(array(
		'post_type'      => 'depoimento',
		'orderby'        => 'rand',
		'posts_per_page' => 6,
		'no_found_rows'  => true
	));
	?>
	<?php if ( $depoimento->have_posts() ) : ?>
	<section class="section-testimonial">
		<div class="container">
			<h1 class="section-title">Depoimentos</h1>

			<div id="testimonial-slide" class="testimonial-slide">

				<?php while ( $depoimento->have_posts() ) : $depoimento->the_post(); ?>
				<div class="testimonial-item">
					<div class="testimonial-info">
						<div class="testimonial-photo">
							<?php
							$image = get_field('thumbnail');
							?>
							<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="Lorem ipsum">
						</div>
						<div class="testimonial-name">
							<h2><?php the_title(); ?></h2>
							<span><?php the_field('residencia'); ?></span>
						</div>
					</div>
					<div class="testimonial-content">
						<?php the_field('depoimento'); ?>
					</div>
				</div>
				<?php endwhile;?>

			</div>
		</div>
	</section>
	<?php endif;?>


	<!-- EVENTOS -->
	<?php
	$evento = new WP_Query(array(
		'post_type'      => 'evento',
		'meta_key'       => 'data_inicio',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'data_inicio',
				'value'   => time(),
				'compare' => '>=',
			)
		),
		'posts_per_page' => -1,
	));
	?>
	<?php if ( $evento->have_posts() ) : ?>
	<section class="section-calendar">
		<div class="container">
			<h1 class="section-title">Agenda</h1>

			<div id="calendar-slide" class="calendar-slide">

				<?php while ( $evento->have_posts() ) : $evento->the_post(); ?>
				<div class="calendar-slide-item">
					<?php $data_inicio = DateTime::createFromFormat('d/m/Y H:i:s', get_field('data_inicio')); ?>
					<span class="calendar-slide-data"><?php echo $data_inicio->format('d/m'); ?></span>

					<div class="calendar-slide-info"><?php the_field('local_resumido'); ?></div>

					<h2><?php the_title(); ?></h2>

					<div class="calendar-slide-footer">
						<a href="<?php the_permalink(); ?>">Leia mais <span class="icon-chevron"></span></a>
					</div>
				</div>
				<?php endwhile;?>

			</div>
		</div>
	</section>
	<?php endif;?>


	<!-- VIDEOS -->
	<?php
	$videos = new WP_Query(array(
		'post_type'      => 'video',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => 3,
		'no_found_rows'  => true
	));
	?>
	<?php if ( $videos->have_posts() ) : ?>
	<section class="section-videos">
		<div class="container">
			<h1 class="section-title">Vídeos</h1>

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
			<div class="videos-cards-more">
				<a href="<?php echo get_permalink( get_page_by_path( 'videos' ) ); ?>" class="btn-blue">Ver arquivos de vídeos</a>
			</div>
		</div>
	</section>
	<?php endif;?>


	<!-- NEWSLETTER -->
	<!-- <section class="section-newsletter">
		<form action="#">
			<h2 class="title-newsletter">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>
			<input type="text" name="" placeholder="email@domain.com.br ou (xx) xxxxx-xxxx">
			<input type="submit" value="Cadastrar" class="btn-orange">
		</form>
	</section> -->


	<!-- NOTÍCIAS -->
	<section class="section-news">
		<div class="container">
			<div class="container">
				<h1 class="section-title">Notícias</h1>
			</div>

			<div class="news-cards">
				<?php get_template_part( 'loop', 'archive' ); ?>
			</div>

			<div class="news-cards-more">
				<a href="<?php echo esc_url( site_url() ); ?>/noticias/" class="btn-blue">Ver arquivos de notícias</a>
			</div>

		</div>
	</section>


	<!-- PROPOSTAS -->
	<?php
	$proposta = new WP_Query(array(
		'post_type'      => 'proposta',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'posts_per_page' => -1,
		'no_found_rows'  => true
	));
	?>
	<?php if ( $proposta->have_posts() ) : ?>
		<section class="section-proposals">
			<div class="container">
				<div class="proposals">
					<?php while ( $proposta->have_posts() ) : $proposta->the_post(); ?>
						<div class="proposals-item">
							<a id="btn-<?php the_id(); ?>" href="#modal-<?php the_id(); ?>"><?php the_title(); ?></a>
							<div id="modal-<?php the_id(); ?>" class="proposals-modal">
								<span class="icon-close"></span>
							    <div class="modal-content">
								    <div class="modal-content-inner">
								    	<h1><?php the_title(); ?></h1>
								    	<?php the_content(); ?>
								    </div>
							    </div>
							</div>
						</div>
					<?php endwhile;?>
				</div>
			</div>
		</section>
	<?php endif;?>

</main>

<?php get_footer(); ?>
