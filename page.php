<?php get_header(); ?>

<main id="primary" class="site-main">
	<div class="container">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry page' ); ?>>

				<?php if ( ! get_post_meta( get_the_ID(), '_trp_hide_title', true ) ) : ?>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
				<?php endif; ?>

				<?php if ( has_post_thumbnail() && ! get_post_meta( get_the_ID(), '_trp_hide_thumbnail', true ) ) : ?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'therustedpage' ),
						'after'  => '</div>',
					) );
					?>
				</div>

			</article>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		<?php endwhile; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
