<?php
/**
 * The Rusted Page — Customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function trp_customizer_register( $wp_customize ) {

	/* -----------------------------------------------------------------------
	   Section: Typography
	   ----------------------------------------------------------------------- */
	$wp_customize->add_section( 'trp_typography_section', array(
		'title'    => __( 'Typography', 'therustedpage' ),
		'priority' => 42,
	) );

	$wp_customize->add_setting( 'trp_body_font', array(
		'default'           => 'Chakra Petch',
		'sanitize_callback' => 'trp_sanitize_body_font',
	) );
	$wp_customize->add_control( 'trp_body_font', array(
		'label'   => __( 'Body font', 'therustedpage' ),
		'section' => 'trp_typography_section',
		'type'    => 'select',
		'choices' => array(
			'Chakra Petch'   => 'Chakra Petch — angular industrial',
			'Barlow'         => 'Barlow — industrial geometric',
			'IBM Plex Sans'  => 'IBM Plex Sans — industrial heritage',
			'Titillium Web'  => 'Titillium Web — tech industrial',
			'Exo 2'          => 'Exo 2 — futuristic geometric',
			'Rajdhani'       => 'Rajdhani — angular condensed',
			'Nunito Sans'    => 'Nunito Sans — rounded humanist',
			'Archivo'        => 'Archivo — sharp grotesque',
			'Kanit'          => 'Kanit — geometric edge',
			'Jura'           => 'Jura — light futuristic',
			'Orbitron'       => 'Orbitron — bold sci-fi',
			'Inconsolata'    => 'Inconsolata — monospace punk',
			'Lato'           => 'Lato — clean neutral',
		),
	) );

	/* -----------------------------------------------------------------------
	   Panel: Hero
	   ----------------------------------------------------------------------- */
	$wp_customize->add_panel( 'trp_hero_panel', array(
		'title'    => __( 'Hero / Header Image', 'therustedpage' ),
		'priority' => 130,
	) );

	$wp_customize->add_section( 'trp_hero_section', array(
		'title' => __( 'Images & Behavior', 'therustedpage' ),
		'panel' => 'trp_hero_panel',
	) );

	// Display mode
	$wp_customize->add_setting( 'trp_hero_mode', array(
		'default'           => 'slider',
		'sanitize_callback' => 'trp_sanitize_hero_mode',
	) );
	$wp_customize->add_control( 'trp_hero_mode', array(
		'label'   => __( 'Display Mode', 'therustedpage' ),
		'section' => 'trp_hero_section',
		'type'    => 'select',
		'choices' => array(
			'static' => __( 'Static — first image only',    'therustedpage' ),
			'slider' => __( 'Slider — auto-rotate',         'therustedpage' ),
			'random' => __( 'Random — new image each visit','therustedpage' ),
			'none'   => __( 'Hidden — no hero area',        'therustedpage' ),
		),
	) );

	// Slider speed
	$wp_customize->add_setting( 'trp_hero_speed', array(
		'default'           => 5000,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'trp_hero_speed', array(
		'label'       => __( 'Slider interval (ms)', 'therustedpage' ),
		'description' => __( 'Milliseconds between slides. 5000 = 5 seconds.', 'therustedpage' ),
		'section'     => 'trp_hero_section',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1000, 'max' => 30000, 'step' => 500 ),
	) );

	// Hero images 1–5
	$position_choices = array(
		'center center' => __( 'Center (default)', 'therustedpage' ),
		'center top'    => __( 'Top — pin top edge', 'therustedpage' ),
		'center bottom' => __( 'Bottom — pin bottom edge', 'therustedpage' ),
		'left center'   => __( 'Left — pin left side', 'therustedpage' ),
		'right center'  => __( 'Right — pin right side', 'therustedpage' ),
		'left top'      => __( 'Top-left corner', 'therustedpage' ),
		'right top'     => __( 'Top-right corner', 'therustedpage' ),
		'left bottom'   => __( 'Bottom-left corner', 'therustedpage' ),
		'right bottom'  => __( 'Bottom-right corner', 'therustedpage' ),
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting( 'trp_hero_image_' . $i, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'trp_hero_image_' . $i,
			array(
				'label'   => sprintf( __( 'Hero Image %d', 'therustedpage' ), $i ),
				'section' => 'trp_hero_section',
			)
		) );

		$wp_customize->add_setting( 'trp_hero_position_' . $i, array(
			'default'           => 'center center',
			'sanitize_callback' => 'trp_sanitize_hero_position',
		) );
		$wp_customize->add_control( 'trp_hero_position_' . $i, array(
			'label'       => sprintf( __( 'Image %d crop position', 'therustedpage' ), $i ),
			'description' => __( 'Which part of the image to keep visible when cropped.', 'therustedpage' ),
			'section'     => 'trp_hero_section',
			'type'        => 'select',
			'choices'     => $position_choices,
		) );
	}

	// Optional overlay text
	$wp_customize->add_setting( 'trp_hero_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'trp_hero_title', array(
		'label'       => __( 'Overlay title (optional)', 'therustedpage' ),
		'description' => __( 'Large text shown over the hero image.', 'therustedpage' ),
		'section'     => 'trp_hero_section',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'trp_hero_tagline', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'trp_hero_tagline', array(
		'label'   => __( 'Overlay tagline (optional)', 'therustedpage' ),
		'section' => 'trp_hero_section',
		'type'    => 'text',
	) );

	// Show hero on: all pages or front page only
	$wp_customize->add_setting( 'trp_hero_pages', array(
		'default'           => 'all',
		'sanitize_callback' => 'trp_sanitize_hero_pages',
	) );
	$wp_customize->add_control( 'trp_hero_pages', array(
		'label'   => __( 'Show hero on', 'therustedpage' ),
		'section' => 'trp_hero_section',
		'type'    => 'select',
		'choices' => array(
			'all'   => __( 'Every page', 'therustedpage' ),
			'front' => __( 'Front / home page only', 'therustedpage' ),
		),
	) );

	/* -----------------------------------------------------------------------
	   Panel: Hero — Bottom Hero section
	   ----------------------------------------------------------------------- */
	$wp_customize->add_section( 'trp_bottom_hero_section', array(
		'title' => __( 'Bottom Hero (before footer)', 'therustedpage' ),
		'panel' => 'trp_hero_panel',
	) );

	$wp_customize->add_setting( 'trp_bottom_hero_mode', array(
		'default'           => 'none',
		'sanitize_callback' => 'trp_sanitize_hero_mode',
	) );
	$wp_customize->add_control( 'trp_bottom_hero_mode', array(
		'label'   => __( 'Display Mode', 'therustedpage' ),
		'section' => 'trp_bottom_hero_section',
		'type'    => 'select',
		'choices' => array(
			'none'   => __( 'Hidden (disabled)',              'therustedpage' ),
			'static' => __( 'Static — first image only',     'therustedpage' ),
			'slider' => __( 'Slider — auto-rotate',          'therustedpage' ),
			'random' => __( 'Random — new image each visit', 'therustedpage' ),
		),
	) );

	$wp_customize->add_setting( 'trp_bottom_hero_speed', array(
		'default'           => 5000,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'trp_bottom_hero_speed', array(
		'label'       => __( 'Slider interval (ms)', 'therustedpage' ),
		'description' => __( 'Milliseconds between slides. 5000 = 5 seconds.', 'therustedpage' ),
		'section'     => 'trp_bottom_hero_section',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1000, 'max' => 30000, 'step' => 500 ),
	) );

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting( 'trp_bottom_hero_image_' . $i, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'trp_bottom_hero_image_' . $i,
			array(
				'label'   => sprintf( __( 'Bottom Image %d', 'therustedpage' ), $i ),
				'section' => 'trp_bottom_hero_section',
			)
		) );

		$wp_customize->add_setting( 'trp_bottom_hero_position_' . $i, array(
			'default'           => 'center center',
			'sanitize_callback' => 'trp_sanitize_hero_position',
		) );
		$wp_customize->add_control( 'trp_bottom_hero_position_' . $i, array(
			'label'   => sprintf( __( 'Image %d crop position', 'therustedpage' ), $i ),
			'section' => 'trp_bottom_hero_section',
			'type'    => 'select',
			'choices' => $position_choices,
		) );
	}

	/* -----------------------------------------------------------------------
	   Section: SEO & Social Sharing
	   ----------------------------------------------------------------------- */
	$wp_customize->add_section( 'trp_seo_section', array(
		'title'       => __( 'SEO & Social Sharing', 'therustedpage' ),
		'description' => __( 'Controls the image and text shown when your pages are shared on Facebook, Twitter/X, LinkedIn, etc. Posts automatically use their featured image and excerpt — these settings are the site-wide fallbacks.', 'therustedpage' ),
		'priority'    => 135,
	) );

	// Default share image
	$wp_customize->add_setting( 'trp_seo_default_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'trp_seo_default_image',
		array(
			'label'       => __( 'Default share image', 'therustedpage' ),
			'description' => __( 'Used on the homepage and any post/page without a featured image. Minimum 1200 × 630 px for best results.', 'therustedpage' ),
			'section'     => 'trp_seo_section',
		)
	) );

	// Default description
	$wp_customize->add_setting( 'trp_seo_default_description', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'trp_seo_default_description', array(
		'label'       => __( 'Default share description', 'therustedpage' ),
		'description' => __( 'Fallback for pages without an excerpt. Keep under 160 characters.', 'therustedpage' ),
		'section'     => 'trp_seo_section',
		'type'        => 'textarea',
	) );

	// Twitter / X handle
	$wp_customize->add_setting( 'trp_seo_twitter_handle', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'trp_seo_twitter_handle', array(
		'label'       => __( 'Twitter / X handle', 'therustedpage' ),
		'description' => __( 'Your @username — e.g. @therustedpage', 'therustedpage' ),
		'section'     => 'trp_seo_section',
		'type'        => 'text',
	) );

	// Facebook App ID
	$wp_customize->add_setting( 'trp_seo_fb_app_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'trp_seo_fb_app_id', array(
		'label'       => __( 'Facebook App ID (optional)', 'therustedpage' ),
		'description' => __( 'Unlocks Facebook Insights for your site. Leave blank if you don\'t have one.', 'therustedpage' ),
		'section'     => 'trp_seo_section',
		'type'        => 'text',
	) );

	/* -----------------------------------------------------------------------
	   Section: Social Media Links
	   ----------------------------------------------------------------------- */
	$wp_customize->add_section( 'trp_social_section', array(
		'title'       => __( 'Social Media Links', 'therustedpage' ),
		'description' => __( 'Enter your profile URLs — icons appear automatically in the footer. Leave blank to hide.', 'therustedpage' ),
		'priority'    => 138,
	) );

	$social_networks = array(
		'facebook'  => __( 'Facebook URL', 'therustedpage' ),
		'instagram' => __( 'Instagram URL', 'therustedpage' ),
		'x'         => __( 'Twitter / X URL', 'therustedpage' ),
		'youtube'   => __( 'YouTube URL', 'therustedpage' ),
		'tiktok'    => __( 'TikTok URL', 'therustedpage' ),
		'spotify'   => __( 'Spotify URL', 'therustedpage' ),
		'discord'   => __( 'Discord URL', 'therustedpage' ),
		'reddit'    => __( 'Reddit URL', 'therustedpage' ),
		'linkedin'  => __( 'LinkedIn URL', 'therustedpage' ),
		'pinterest' => __( 'Pinterest URL', 'therustedpage' ),
		'email'     => __( 'Email address', 'therustedpage' ),
	);

	foreach ( $social_networks as $slug => $label ) {
		$wp_customize->add_setting( 'trp_social_' . $slug, array(
			'default'           => '',
			'sanitize_callback' => 'email' === $slug ? 'sanitize_email' : 'esc_url_raw',
		) );
		$wp_customize->add_control( 'trp_social_' . $slug, array(
			'label'   => $label,
			'section' => 'trp_social_section',
			'type'    => 'email' === $slug ? 'email' : 'url',
		) );
	}

	/* -----------------------------------------------------------------------
	   Section: Footer
	   ----------------------------------------------------------------------- */
	$wp_customize->add_section( 'trp_footer_section', array(
		'title'    => __( 'Footer', 'therustedpage' ),
		'priority' => 140,
	) );

	$wp_customize->add_setting( 'trp_footer_columns', array(
		'default'           => '3',
		'sanitize_callback' => 'trp_sanitize_footer_columns',
	) );
	$wp_customize->add_control( 'trp_footer_columns', array(
		'label'   => __( 'Widget columns', 'therustedpage' ),
		'section' => 'trp_footer_section',
		'type'    => 'select',
		'choices' => array(
			'1' => __( '1 column', 'therustedpage' ),
			'2' => __( '2 columns', 'therustedpage' ),
			'3' => __( '3 columns', 'therustedpage' ),
		),
	) );

	$wp_customize->add_setting( 'trp_footer_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'trp_footer_text', array(
		'label'       => __( 'Copyright / footer text', 'therustedpage' ),
		'description' => __( 'Overrides the default copyright line. HTML allowed.', 'therustedpage' ),
		'section'     => 'trp_footer_section',
		'type'        => 'textarea',
	) );

	/* -----------------------------------------------------------------------
	   Colors — accent override (added to the built-in Colors section)
	   ----------------------------------------------------------------------- */
	$wp_customize->add_setting( 'trp_accent_color', array(
		'default'           => '#00838f',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'trp_accent_color',
		array(
			'label'       => __( 'Accent / Rust color', 'therustedpage' ),
			'description' => __( 'Used for borders, links, buttons, and highlights.', 'therustedpage' ),
			'section'     => 'colors',
		)
	) );
}
add_action( 'customize_register', 'trp_customizer_register' );

/* --------------------------------------------------------------------------
   Sanitize callbacks
   -------------------------------------------------------------------------- */
function trp_sanitize_body_font( $value ) {
	$valid = array(
		'Chakra Petch', 'Barlow', 'IBM Plex Sans', 'Titillium Web',
		'Exo 2', 'Rajdhani', 'Nunito Sans', 'Archivo', 'Kanit',
		'Jura', 'Orbitron', 'Inconsolata', 'Lato',
	);
	return in_array( $value, $valid, true ) ? $value : 'Chakra Petch';
}
function trp_sanitize_hero_mode( $value ) {
	return in_array( $value, array( 'static', 'slider', 'random', 'none' ), true ) ? $value : 'slider';
}
function trp_sanitize_footer_columns( $value ) {
	return in_array( (string) $value, array( '1', '2', '3' ), true ) ? (string) $value : '3';
}
function trp_sanitize_hero_pages( $value ) {
	return in_array( $value, array( 'all', 'front' ), true ) ? $value : 'all';
}
function trp_sanitize_hero_position( $value ) {
	$valid = array(
		'center center', 'center top', 'center bottom',
		'left center',   'left top',   'left bottom',
		'right center',  'right top',  'right bottom',
	);
	return in_array( $value, $valid, true ) ? $value : 'center center';
}

/* --------------------------------------------------------------------------
   Inline CSS — accent color override
   -------------------------------------------------------------------------- */
function trp_customizer_css() {
	$accent    = get_theme_mod( 'trp_accent_color', '#00838f' );
	$body_font = get_theme_mod( 'trp_body_font', 'Chakra Petch' );

	$has_accent = ( '#00838f' !== strtolower( $accent ) );
	$has_font   = ( 'Chakra Petch' !== $body_font );

	if ( ! $has_accent && ! $has_font ) {
		return;
	}

	$fallback = ( 'Inconsolata' === $body_font ) ? ', monospace' : ", 'Helvetica Neue', Helvetica, Arial, sans-serif";

	echo '<style id="trp-customizer-css">' . "\n:root {\n";

	if ( $has_accent ) {
		$dark  = trp_adjust_brightness( $accent, -48 );
		$light = trp_adjust_brightness( $accent, +28 );
		echo "\t--color-rust:       " . esc_attr( $accent ) . ";\n";
		echo "\t--color-rust-dark:  " . esc_attr( $dark )   . ";\n";
		echo "\t--color-rust-light: " . esc_attr( $light )  . ";\n";
	}

	if ( $has_font ) {
		echo "\t--font-body: '" . esc_attr( $body_font ) . "'" . $fallback . ";\n";
	}

	echo "}\n</style>\n";
}
add_action( 'wp_head', 'trp_customizer_css' );

/* --------------------------------------------------------------------------
   Color math helpers
   -------------------------------------------------------------------------- */
function trp_hex_to_rgb( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	return array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);
}

function trp_adjust_brightness( $hex, $amount ) {
	list( $r, $g, $b ) = trp_hex_to_rgb( $hex );
	$r = max( 0, min( 255, $r + $amount ) );
	$g = max( 0, min( 255, $g + $amount ) );
	$b = max( 0, min( 255, $b + $amount ) );
	return sprintf( '#%02x%02x%02x', $r, $g, $b );
}
