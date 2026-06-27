<?php
/**
 * The Rusted Page — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRP_VERSION', '1.6.1' );
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
	// Google Fonts — Bebas Neue (headings), Oswald (sub/nav), body font from Customizer
	$body_font = get_theme_mod( 'trp_body_font', 'Chakra Petch' );
	$body_slug = str_replace( ' ', '+', $body_font );
	wp_enqueue_style(
		'trp-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;600&family=' . $body_slug . ':ital,wght@0,400;0,700;1,400&display=swap',
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
   Social media icons (inline SVG)
   -------------------------------------------------------------------------- */
function trp_social_icon( $network ) {
	$icons = array(
		'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
		'x'         => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
		'tiktok'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
		'spotify'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>',
		'discord'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189z"/></svg>',
		'reddit'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 01-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 01.042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 014.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 01.14-.197.35.35 0 01.238-.042l2.906.617a1.214 1.214 0 011.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 00-.231.094.33.33 0 000 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 00.029-.463.33.33 0 00-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 00-.232-.095z"/></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
		'pinterest' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641 0 12.017 0z"/></svg>',
		'email'     => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"/><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"/></svg>',
	);
	return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}

function trp_get_social_links() {
	$networks = array(
		'facebook', 'instagram', 'x', 'youtube', 'tiktok',
		'spotify', 'discord', 'reddit', 'linkedin', 'pinterest', 'email',
	);
	$links = array();
	foreach ( $networks as $slug ) {
		$val = get_theme_mod( 'trp_social_' . $slug, '' );
		if ( $val ) {
			$links[ $slug ] = 'email' === $slug ? 'mailto:' . $val : $val;
		}
	}
	return $links;
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
