<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php wp_title( '|', true, 'right' ); ?></title>

<?php wp_head();?>
</head>
<body <?php body_class(); ?>>

<?php do_action( 'after_body' ); ?>

<div class="wraper">

	<header class="navbar">
		<div class="container">
			<div class="navbar-header">
				<a href="<?php echo esc_url( site_url() ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/dist/logo-campanha-iranildo.png" class="navbar-logo">
				</a>
				<button type="button" class="navbar-toggle" data-target="#navbar-content" data-toggle="collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<nav id="navbar-content" class="navbar-content collapse">
				<?php
				wp_nav_menu(array(
					'theme_location' => 'menu-navigation',
					'container'      => false,
					'menu_id'        => 'navbar-menu',
					'menu_class'     => 'navbar-menu',
					'fallback_cb'    => 'fallbackNoMenu'
				));
				?>
				<?php
				$social_facebook_url = get_option('social_facebook_url');
				if (!empty($social_facebook_url)) {
					echo '<div class="cta-facebook"><a href="'.$social_facebook_url.'">Curta nossa página no facebook</a></div>';
				}
				?>

				<?php
				$social_twibbon_url = get_option('social_twibbon_url');
				if (!empty($social_twibbon_url)) {
					echo '<div class="cta-twibbon"><a href="'.$social_twibbon_url.'">Coloque a marca da campanha em sua foto de perfil</a></div>';
				}
				?>
			</nav>
		</div>
	</header>


