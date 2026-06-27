<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry' ); ?>>

				<header class="entry-header">
					<?php if ( ! get_post_meta( get_the_ID(), '_trp_hide_title', true ) ) : ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php endif; ?>
					<div class="entry-meta">
						<?php trp_posted_on(); ?>
						<?php trp_posted_by(); ?>
						<?php if ( comments_open() ) : ?>
							<span class="comments-link">
								<?php comments_popup_link(
									esc_html__( 'Leave a comment', 'therustedpage' ),
									esc_html__( '1 comment', 'therustedpage' ),
									esc_html__( '% comments', 'therustedpage' )
								); ?>
							</span>
						<?php endif; ?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() && ! get_post_meta( get_the_ID(), '_trp_hide_thumbnail', true ) ) : ?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content( sprintf(
						esc_html__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'therustedpage' ),
						get_the_title()
					) );
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'therustedpage' ),
						'after'  => '</div>',
					) );
					?>
				</div>

				<?php trp_entry_footer(); ?>

			</article>

			<nav class="post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'therustedpage' ); ?>">
				<?php the_post_navigation( array(
					'prev_text' => '&larr; <span class="nav-subtitle">' . esc_html__( 'Previous', 'therustedpage' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'therustedpage' ) . '</span> <span class="nav-title">%title</span> &rarr;',
				) ); ?>
			</nav>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		<?php endwhile; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
