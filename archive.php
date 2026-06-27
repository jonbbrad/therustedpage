<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<header class="page-header">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>

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

			<nav class="pagination" aria-label="<?php esc_attr_e( 'Archive navigation', 'therustedpage' ); ?>">
				<?php the_posts_pagination( array(
					'prev_text' => '&larr; ' . esc_html__( 'Older', 'therustedpage' ),
					'next_text' => esc_html__( 'Newer', 'therustedpage' ) . ' &rarr;',
				) ); ?>
			</nav>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts found.', 'therustedpage' ); ?></p>

		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
