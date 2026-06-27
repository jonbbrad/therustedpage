<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<div class="post-cards">
				<?php while ( have_posts() ) : the_post();
					$thumb_url = '';
					if ( has_post_thumbnail() ) {
						$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'trp-card' );
						if ( $img ) {
							$thumb_url = $img[0];
						}
					}
				?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
						<a href="<?php the_permalink(); ?>" class="post-card-link"
						   <?php if ( $thumb_url ) : ?>style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');"<?php endif; ?>>
							<div class="post-card-overlay">
								<h2 class="post-card-title"><?php the_title(); ?></h2>
								<div class="post-card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></div>
								<span class="post-card-meta">
									<?php echo esc_html( get_the_date() ); ?>
									<?php if ( get_the_author() ) : ?>
										&middot; <?php echo esc_html( get_the_author() ); ?>
									<?php endif; ?>
								</span>
							</div>
						</a>
					</article>

				<?php endwhile; ?>
			</div>

			<nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'therustedpage' ); ?>">
				<?php the_posts_pagination( array(
					'prev_text' => '&larr; ' . esc_html__( 'Older posts', 'therustedpage' ),
					'next_text' => esc_html__( 'Newer posts', 'therustedpage' ) . ' &rarr;',
				) ); ?>
			</nav>

		<?php else : ?>

			<article class="no-results not-found hentry">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Nothing Here', 'therustedpage' ); ?></h1>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location.', 'therustedpage' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</article>

		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
