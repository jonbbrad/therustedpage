<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<header class="page-header">
			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Results for: %s', 'therustedpage' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry' ); ?>>
					<header class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h2>
						<div class="entry-meta">
							<?php trp_posted_on(); ?>
							<?php trp_posted_by(); ?>
						</div>
					</header>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
						<a class="read-more-link" href="<?php the_permalink(); ?>">
							<?php esc_html_e( 'Read More', 'therustedpage' ); ?> &rarr;
						</a>
					</div>
				</article>

			<?php endwhile; ?>

			<nav class="pagination" aria-label="<?php esc_attr_e( 'Search results navigation', 'therustedpage' ); ?>">
				<?php the_posts_pagination( array(
					'prev_text' => '&larr; ' . esc_html__( 'Older', 'therustedpage' ),
					'next_text' => esc_html__( 'Newer', 'therustedpage' ) . ' &rarr;',
				) ); ?>
			</nav>

		<?php else : ?>

			<article class="no-results not-found hentry">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'No results', 'therustedpage' ); ?></h1>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'Nothing matched your search. Try different keywords.', 'therustedpage' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</article>

		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
