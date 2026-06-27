<?php
/**
 * The Rusted Page — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRP_VERSION', '1.3.3' );
define( 'TRP_DIR', get_template_directory() );
define( 'TRP_URI', get_template_directory_uri() );

/* --------------------------------------------------------------------------
   Theme Setup
   -------------------------------------------------------------------------- */
function trp_setup() {
	load_theme_textdomain( 'therustedpage', TRP_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
		'gallery', 'caption', 'style', 'script',
	) );
	add_theme_support( 'custom-background', array(
		'default-color'      => '0a0a0a',
		'default-image'      => get_template_directory_uri() . '/assets/images/bg-texture.jpg',
		'default-size'       => 'cover',
		'default-attachment' => 'fixed',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 104,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-color-palette', array(
		array( 'name' => __( 'Dark Teal',    'therustedpage' ), 'slug' => 'rust',        'color' => '#00838f' ),
		array( 'name' => __( 'Mid Teal',    'therustedpage' ), 'slug' => 'rust-light',  'color' => '#26c6da' ),
		array( 'name' => __( 'Deep Teal',   'therustedpage' ), 'slug' => 'rust-dark',   'color' => '#006064' ),
		array( 'name' => __( 'Light Teal',  'therustedpage' ), 'slug' => 'punk-teal',   'color' => '#4dd0e1' ),
		array( 'name' => __( 'Blood Red',   'therustedpage' ), 'slug' => 'blood-red',   'color' => '#8b0000' ),
		array( 'name' => __( 'Punk Purple', 'therustedpage' ), 'slug' => 'punk-purple', 'color' => '#ba68c8' ),
		array( 'name' => __( 'Steel',       'therustedpage' ), 'slug' => 'steel',       'color' => '#4a5058' ),
		array( 'name' => __( 'Site Black',  'therustedpage' ), 'slug' => 'site-black',  'color' => '#0a0a0a' ),
		array( 'name' => __( 'Surface',     'therustedpage' ), 'slug' => 'surface',     'color' => '#131313' ),
		array( 'name' => __( 'Light Text',  'therustedpage' ), 'slug' => 'light-text',  'color' => '#d4d4d4' ),
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu',    'therustedpage' ),
		'footer'  => __( 'Footer Nav Menu', 'therustedpage' ),
	) );

	set_post_thumbnail_size( 860, 480, true );
	add_image_size( 'trp-hero', 1920, 720, true );
	add_image_size( 'trp-card', 600, 360, true );
}
add_action( 'after_setup_theme', 'trp_setup' );

/* --------------------------------------------------------------------------
   Content Width
   -------------------------------------------------------------------------- */
function trp_content_width() {
	$GLOBALS['content_width'] = 1150;
}
add_action( 'after_setup_theme', 'trp_content_width', 0 );

/* --------------------------------------------------------------------------
   Widget Areas
   -------------------------------------------------------------------------- */
function trp_widgets_init() {
	$args = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar( array_merge( $args, array(
			'name'        => sprintf( __( 'Footer Column %d', 'therustedpage' ), $i ),
			'id'          => 'footer-' . $i,
			'description' => sprintf( __( 'Widgets in footer column %d.', 'therustedpage' ), $i ),
		) ) );
	}
}
add_action( 'widgets_init', 'trp_widgets_init' );

/* --------------------------------------------------------------------------
   Scripts & Styles
   -------------------------------------------------------------------------- */
function trp_scripts() {
	// Google Fonts — Bebas Neue (headings), Oswald (sub/nav), Lato (body)
	wp_enqueue_style(
		'trp-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;600&family=Lato:ital,wght@0,400;0,700;1,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'trp-style', get_stylesheet_uri(), array( 'trp-fonts' ), TRP_VERSION );

	wp_enqueue_script(
		'trp-main',
		TRP_URI . '/assets/js/therustedpage.js',
		array(),
		TRP_VERSION,
		true
	);

	$hero_images = trp_get_hero_images();
	wp_localize_script( 'trp-main', 'trpHero', array(
		'mode'   => get_theme_mod( 'trp_hero_mode', 'slider' ),
		'images' => $hero_images,
		'speed'  => absint( get_theme_mod( 'trp_hero_speed', 5000 ) ),
	) );

	wp_localize_script( 'trp-main', 'trpBottomHero', array(
		'mode'   => get_theme_mod( 'trp_bottom_hero_mode', 'none' ),
		'images' => trp_get_bottom_hero_images(),
		'speed'  => absint( get_theme_mod( 'trp_bottom_hero_speed', 5000 ) ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'trp_scripts' );

/* --------------------------------------------------------------------------
   Hero Image Helpers
   -------------------------------------------------------------------------- */
function trp_get_hero_images() {
	$images = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$url = get_theme_mod( 'trp_hero_image_' . $i, '' );
		if ( $url ) {
			$images[] = esc_url( $url );
		}
	}
	return $images;
}

function trp_get_bottom_hero_images() {
	$images = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$url = get_theme_mod( 'trp_bottom_hero_image_' . $i, '' );
		if ( $url ) {
			$images[] = esc_url( $url );
		}
	}
	return $images;
}

/* --------------------------------------------------------------------------
   Social / Open Graph meta tags
   -------------------------------------------------------------------------- */
function trp_social_meta() {
	// Step aside if a dedicated SEO plugin is handling this
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'The_SEO_Framework\Load' ) ) {
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$def_image = get_theme_mod( 'trp_seo_default_image', '' );
	$def_desc  = get_theme_mod( 'trp_seo_default_description', '' );
	$tw_handle = get_theme_mod( 'trp_seo_twitter_handle', '' );
	$fb_app_id = get_theme_mod( 'trp_seo_fb_app_id', '' );

	if ( is_singular() ) {
		$post    = get_queried_object();
		$title   = get_the_title( $post );
		$url     = get_permalink( $post );
		$og_type = 'article';

		if ( $post->post_excerpt ) {
			$desc = wp_strip_all_tags( $post->post_excerpt );
		} elseif ( $post->post_content ) {
			$desc = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
		} else {
			$desc = $def_desc;
		}

		$image = '';
		if ( has_post_thumbnail( $post->ID ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			if ( $src ) {
				$image = $src[0];
			}
		}
		if ( ! $image ) {
			$image = $def_image;
		}

	} elseif ( is_home() || is_front_page() ) {
		$title   = $site_name;
		$url     = home_url( '/' );
		$og_type = 'website';
		$desc    = get_bloginfo( 'description' ) ?: $def_desc;
		$image   = $def_image;

	} else {
		global $wp;
		$title   = wp_title( '', false ) . ' — ' . $site_name;
		$url     = home_url( $wp->request );
		$og_type = 'website';
		$desc    = $def_desc;
		$image   = $def_image;
	}

	if ( strlen( $desc ) > 160 ) {
		$desc = substr( $desc, 0, 157 ) . '…';
	}

	echo "\n<!-- Open Graph / Social -->\n";
	printf( "<meta property=\"og:type\"        content=\"%s\">\n", esc_attr( $og_type ) );
	printf( "<meta property=\"og:url\"         content=\"%s\">\n", esc_url( $url ) );
	printf( "<meta property=\"og:site_name\"   content=\"%s\">\n", esc_attr( $site_name ) );
	printf( "<meta property=\"og:title\"       content=\"%s\">\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $desc ) );
		printf( "<meta name=\"description\"        content=\"%s\">\n", esc_attr( $desc ) );
	}
	if ( $image ) {
		printf( "<meta property=\"og:image\"       content=\"%s\">\n", esc_url( $image ) );
	}
	if ( $fb_app_id ) {
		printf( "<meta property=\"fb:app_id\"      content=\"%s\">\n", esc_attr( $fb_app_id ) );
	}

	// Twitter Card
	printf( "<meta name=\"twitter:card\"        content=\"summary_large_image\">\n" );
	printf( "<meta name=\"twitter:title\"       content=\"%s\">\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( "<meta name=\"twitter:description\" content=\"%s\">\n", esc_attr( $desc ) );
	}
	if ( $image ) {
		printf( "<meta name=\"twitter:image\"      content=\"%s\">\n", esc_url( $image ) );
	}
	if ( $tw_handle ) {
		$handle = ( '@' === $tw_handle[0] ) ? $tw_handle : '@' . $tw_handle;
		printf( "<meta name=\"twitter:site\"       content=\"%s\">\n", esc_attr( $handle ) );
	}
	echo "\n";
}
add_action( 'wp_head', 'trp_social_meta', 5 );

/* --------------------------------------------------------------------------
   Hide Title — meta box for pages & posts
   -------------------------------------------------------------------------- */
function trp_add_display_options_meta_box() {
	add_meta_box(
		'trp_display_options',
		__( 'Display Options', 'therustedpage' ),
		'trp_display_options_callback',
		array( 'page', 'post' ),
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'trp_add_display_options_meta_box' );

function trp_display_options_callback( $post ) {
	wp_nonce_field( 'trp_display_options', 'trp_display_options_nonce' );
	$hide_title = get_post_meta( $post->ID, '_trp_hide_title', true );
	$hide_thumb = get_post_meta( $post->ID, '_trp_hide_thumbnail', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="trp_hide_title" value="1" <?php checked( $hide_title, '1' ); ?>>
			<?php esc_html_e( 'Hide title on front end', 'therustedpage' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="trp_hide_thumbnail" value="1" <?php checked( $hide_thumb, '1' ); ?>>
			<?php esc_html_e( 'Hide featured image on front end', 'therustedpage' ); ?>
		</label>
	</p>
	<?php
}

function trp_save_display_options_meta( $post_id ) {
	if ( ! isset( $_POST['trp_display_options_nonce'] ) || ! wp_verify_nonce( $_POST['trp_display_options_nonce'], 'trp_display_options' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'trp_hide_title', 'trp_hide_thumbnail' );
	foreach ( $fields as $field ) {
		$meta_key = '_' . $field;
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta_key, '1' );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}
add_action( 'save_post', 'trp_save_display_options_meta' );

/* --------------------------------------------------------------------------
   Customizer
   -------------------------------------------------------------------------- */
require TRP_DIR . '/inc/customizer.php';

/* --------------------------------------------------------------------------
   GitHub-based auto-updater
   -------------------------------------------------------------------------- */
require TRP_DIR . '/inc/updater.php';

/* --------------------------------------------------------------------------
   Template Tag: posted-on meta
   -------------------------------------------------------------------------- */
function trp_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	echo '<span class="posted-on">' . sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	) . '</span>';
}

/* --------------------------------------------------------------------------
   Template Tag: author byline
   -------------------------------------------------------------------------- */
function trp_posted_by() {
	echo '<span class="byline"> ' . esc_html__( 'by', 'therustedpage' ) . ' '
		. '<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">'
		. esc_html( get_the_author() ) . '</a></span></span>';
}

/* --------------------------------------------------------------------------
   Template Tag: entry footer (cats + tags)
   -------------------------------------------------------------------------- */
function trp_entry_footer() {
	$cats = get_the_category_list( esc_html__( ', ', 'therustedpage' ) );
	$tags = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'therustedpage' ) );

	$parts = array();
	if ( $cats ) {
		$parts[] = '<span class="cat-links">' . esc_html__( 'Filed under: ', 'therustedpage' ) . $cats . '</span>';
	}
	if ( $tags ) {
		$parts[] = '<span class="tags-links">' . esc_html__( 'Tagged: ', 'therustedpage' ) . $tags . '</span>';
	}

	if ( $parts ) {
		echo '<footer class="entry-footer">' . implode( '<span class="sep"> &bull; </span>', $parts ) . '</footer>'; // phpcs:ignore
	}
}

/* --------------------------------------------------------------------------
   Fallback menu (shown when no menu assigned)
   -------------------------------------------------------------------------- */
function trp_fallback_menu() {
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'therustedpage' ) . '</a></li>';
	echo '</ul>';
}

/* --------------------------------------------------------------------------
   Excerpt more string
   -------------------------------------------------------------------------- */
function trp_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'trp_excerpt_more' );

/* --------------------------------------------------------------------------
   Body classes
   -------------------------------------------------------------------------- */
function trp_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	$classes[] = 'footer-cols-' . get_theme_mod( 'trp_footer_columns', '3' );
	return $classes;
}
add_filter( 'body_class', 'trp_body_classes' );
