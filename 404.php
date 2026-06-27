<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<article class="error-404 not-found hentry">
			<header class="page-header">
				<h1 class="four-oh-four">404</h1>
				<p><?php esc_html_e( 'This page got lost in the underground.', 'therustedpage' ); ?></p>
			</header>
			<div class="page-content">
				<p><?php esc_html_e( 'Try searching for what you need:', 'therustedpage' ); ?></p>
				<?php get_search_form(); ?>
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					&larr; <?php esc_html_e( 'Back to Home', 'therustedpage' ); ?>
				</a>
			</div>
		</article>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
