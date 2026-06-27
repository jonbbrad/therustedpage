	</div><!-- #content -->

	<?php
	/* Bottom Hero — optional slider before footer */
	$b_mode   = get_theme_mod( 'trp_bottom_hero_mode', 'none' );
	$b_images = trp_get_bottom_hero_images();
	if ( 'none' !== $b_mode && ! empty( $b_images ) ) :
	?>
	<div class="hero-divider" aria-hidden="true"></div>
	<div id="bottom-hero" class="site-hero" role="img" aria-label="<?php esc_attr_e( 'Bottom image', 'therustedpage' ); ?>">

		<?php foreach ( $b_images as $i => $img_url ) :
			$pos = get_theme_mod( 'trp_bottom_hero_position_' . ( $i + 1 ), 'center center' );
		?>
			<div
				class="hero-slide<?php echo 0 === $i ? ' active' : ''; ?>"
				style="background-image: url('<?php echo esc_url( $img_url ); ?>'); background-position: <?php echo esc_attr( $pos ); ?>;"
				aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>"
			></div>
		<?php endforeach; ?>

		<?php if ( count( $b_images ) > 1 && 'slider' === $b_mode ) : ?>
			<div class="hero-dots" aria-hidden="true">
				<?php foreach ( $b_images as $i => $_ ) : ?>
					<button class="hero-dot<?php echo 0 === $i ? ' active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div><!-- #bottom-hero -->
	<div class="hero-divider" aria-hidden="true"></div>
	<?php endif; ?>

	<?php
	$footer_cols = (int) get_theme_mod( 'trp_footer_columns', '3' );
	$footer_text = get_theme_mod( 'trp_footer_text', '' );

	// Count how many footer sidebars have widgets
	$active = 0;
	for ( $i = 1; $i <= $footer_cols; $i++ ) {
		if ( is_active_sidebar( 'footer-' . $i ) ) {
			$active++;
		}
	}
	?>

	<!-- ======================================================
	     SITE FOOTER
	     ====================================================== -->
	<footer id="colophon" class="site-footer">

		<?php if ( $active > 0 ) : ?>
			<div class="footer-widgets-area columns-<?php echo esc_attr( $footer_cols ); ?>">
				<?php for ( $i = 1; $i <= $footer_cols; $i++ ) : ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<div class="footer-widget-column">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
		<?php endif; ?>

		<?php $social_links = trp_get_social_links(); ?>
		<?php if ( ! empty( $social_links ) ) : ?>
			<div class="footer-social" aria-label="<?php esc_attr_e( 'Social media links', 'therustedpage' ); ?>">
				<?php foreach ( $social_links as $network => $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" class="social-icon social-icon--<?php echo esc_attr( $network ); ?>"
					   <?php echo 'email' !== $network ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					   aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
						<?php echo trp_social_icon( $network ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="site-info">
			<div class="copyright">
				<?php if ( $footer_text ) : ?>
					<?php echo wp_kses_post( $footer_text ); ?>
				<?php else : ?>
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
					<?php esc_html_e( 'All rights reserved.', 'therustedpage' ); ?>
				<?php endif; ?>
			</div>

			<button class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'therustedpage' ); ?>">
				<?php esc_html_e( 'Top', 'therustedpage' ); ?>
			</button>
		</div><!-- .site-info -->

	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
