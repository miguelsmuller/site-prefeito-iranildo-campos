<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>


<main role="main">
	<div class="page-title">
		<div class="container">
			<div class="col-12">
				<h1>Materiais de campanha</h1>
			</div>
		</div>
	</div>
	<article class="page-article">
		<div class="container">

			<div class="col-12">

				<?php
					$query = new WP_Query(array(
						'post_type'      => 'arquivo',
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => 'file_ext',
								'value'   => array( 'png', 'jpeg', 'jpg' ),
								'compare' => 'IN',
							)
						)
					));

					if ( $query->have_posts() ) :
						echo '<h1 class="title-download title-download-imagem">Imagens</h1>';
						echo '<ul class="list-download">';
						while ( $query->have_posts() ) : $query->the_post();
							echo '<li class="">';
							echo '<a href="' . get_permalink() . 'action/down" target="_blank">'.get_the_title().'</a>';
							echo '</li>';
						endwhile;
						echo '</ul>';
					endif;
				?>


				<?php
					$query = new WP_Query(array(
						'post_type'      => 'arquivo',
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => 'file_ext',
								'value'   => array( 'ai', 'eps', 'cdr' ),
								'compare' => 'IN',
							)
						)
					));

					if ( $query->have_posts() ) :
						echo '<h1 class="title-download title-download-vetor">Imagens Vetorizadas</h1>';
						echo '<ul class="list-download">';
						while ( $query->have_posts() ) : $query->the_post();
							echo '<li class="">';
							echo '<a href="' . get_permalink() . 'action/down" target="_blank">'.get_the_title().'</a>';
							echo '</li>';
						endwhile;
						echo '</ul>';
					endif;
				?>


				<?php
					$query = new WP_Query(array(
						'post_type'      => 'arquivo',
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => 'file_ext',
								'value'   => array( 'pdf', 'doc', 'docx' ),
								'compare' => 'IN',
							)
						)
					));

					if ( $query->have_posts() ) :
						echo '<h1 class="title-download title-download-documento">Documentos</h1>';
						echo '<ul class="list-download">';
						while ( $query->have_posts() ) : $query->the_post();
							echo '<li class="">';
							echo '<a href="' . get_permalink() . 'action/down" target="_blank">'.get_the_title().'</a>';
							echo '</li>';
						endwhile;
						echo '</ul>';
					endif;
				?>


				<?php
					$query = new WP_Query(array(
						'post_type'      => 'arquivo',
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => 'file_ext',
								'value'   => array( 'mpeg', 'mp3', 'wav' ),
								'compare' => 'IN',
							)
						)
					));

					if ( $query->have_posts() ) :
						echo '<h1 class="title-download title-download-audio">Áudios</h1>';
						echo '<ul class="list-download">';
						while ( $query->have_posts() ) : $query->the_post();
							echo '<li class="">';
							echo '<a href="' . get_permalink() . 'action/down" target="_blank">'.get_the_title().'</a>';
							echo '</li>';
						endwhile;
						echo '</ul>';
					endif;
				?>
			</div>

		</div>
	</article>
</main>

<?php get_footer(); ?>
