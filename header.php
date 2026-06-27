<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary">
	<?php esc_html_e( 'Skip to content', 'therustedpage' ); ?>
</a>

<div id="page" class="site">

	<!-- ======================================================
	     SITE HEADER
	     ====================================================== -->
	<header id="masthead" class="site-header">
		<div class="header-inner">

			<!-- Logo / Branding — left -->
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<p class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</p>
					<?php
					$desc = get_bloginfo( 'description', 'display' );
					if ( $desc ) :
					?>
						<p class="site-description"><?php echo esc_html( $desc ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<!-- Hamburger (mobile only) — sits outside nav so it's always visible -->
			<button
				class="menu-toggle"
				aria-controls="primary-menu"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Toggle navigation', 'therustedpage' ); ?>"
			>
				<span class="icon-open" aria-hidden="true">&#9776;</span>
				<span class="icon-close" aria-hidden="true">&#10005;</span>
			</button>

			<!-- Primary Navigation — right -->
			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'therustedpage' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => 'trp_fallback_menu',
				) );
				?>
			</nav>

		</div><!-- .header-inner -->
	</header><!-- #masthead -->

	<?php
	/* -----------------------------------------------------------------------
	   HERO / HEADER IMAGE
	   ----------------------------------------------------------------------- */
	$hero_mode   = get_theme_mod( 'trp_hero_mode', 'slider' );
	$hero_pages  = get_theme_mod( 'trp_hero_pages', 'all' );
	$hero_images = trp_get_hero_images();
	$show_hero   = ( 'none' !== $hero_mode )
	               && ! empty( $hero_images )
	               && ( 'all' === $hero_pages || ( 'front' === $hero_pages && ( is_front_page() || is_home() ) ) );

	if ( $show_hero ) :
		$hero_title  = get_theme_mod( 'trp_hero_title', '' );
		$hero_tagline = get_theme_mod( 'trp_hero_tagline', '' );
	?>
	<div id="site-hero" class="site-hero" role="img" aria-label="<?php esc_attr_e( 'Header image', 'therustedpage' ); ?>">

		<?php foreach ( $hero_images as $i => $img_url ) :
			$pos = get_theme_mod( 'trp_hero_position_' . ( $i + 1 ), 'center center' );
		?>
			<div
				class="hero-slide<?php echo 0 === $i ? ' active' : ''; ?>"
				style="background-image: url('<?php echo esc_url( $img_url ); ?>'); background-position: <?php echo esc_attr( $pos ); ?>;"
				aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>"
			></div>
		<?php endforeach; ?>

		<?php if ( $hero_title || $hero_tagline ) : ?>
			<div class="hero-overlay-text">
				<?php if ( $hero_title ) : ?>
					<h2><?php echo esc_html( $hero_title ); ?></h2>
				<?php endif; ?>
				<?php if ( $hero_tagline ) : ?>
					<p><?php echo esc_html( $hero_tagline ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( count( $hero_images ) > 1 && 'slider' === $hero_mode ) : ?>
			<div class="hero-dots" aria-hidden="true">
				<?php foreach ( $hero_images as $i => $_ ) : ?>
					<button class="hero-dot<?php echo 0 === $i ? ' active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div><!-- #site-hero -->
	<div class="hero-divider" aria-hidden="true"></div>
	<?php endif; ?>

	<!-- ======================================================
	     MAIN CONTENT WRAPPER
	     ====================================================== -->
	<div id="content" class="site-content">
