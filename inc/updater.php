<?php
/**
 * The Rusted Page — GitHub-based theme updater
 *
 * Checks GitHub releases for new versions and surfaces them
 * in Dashboard → Updates, just like any official theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRP_GITHUB_REPO', 'jonbbrad/therustedpage' );

function trp_check_github_updates( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . TRP_GITHUB_REPO . '/releases/latest',
		array(
			'headers' => array( 'Accept' => 'application/vnd.github.v3+json' ),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return $transient;
	}

	$release = json_decode( wp_remote_retrieve_body( $response ) );
	if ( ! $release || empty( $release->tag_name ) ) {
		return $transient;
	}

	$latest  = ltrim( $release->tag_name, 'v' );
	$current = wp_get_theme( 'therustedpage' )->get( 'Version' );

	if ( version_compare( $latest, $current, '>' ) ) {
		$package = '';
		if ( ! empty( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( substr( $asset->name, -4 ) === '.zip' ) {
					$package = $asset->browser_download_url;
					break;
				}
			}
		}

		$transient->response['therustedpage'] = array(
			'theme'       => 'therustedpage',
			'new_version' => $latest,
			'url'         => $release->html_url,
			'package'     => $package,
		);
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'trp_check_github_updates' );

function trp_theme_update_details( $result, $action, $args ) {
	if ( 'theme_information' !== $action || 'therustedpage' !== ( $args->slug ?? '' ) ) {
		return $result;
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . TRP_GITHUB_REPO . '/releases/latest',
		array(
			'headers' => array( 'Accept' => 'application/vnd.github.v3+json' ),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return $result;
	}

	$release = json_decode( wp_remote_retrieve_body( $response ) );
	if ( ! $release ) {
		return $result;
	}

	return (object) array(
		'name'          => 'The Rusted Page',
		'slug'          => 'therustedpage',
		'version'       => ltrim( $release->tag_name, 'v' ),
		'author'        => 'jonbbrad',
		'homepage'      => 'https://github.com/' . TRP_GITHUB_REPO,
		'sections'      => array(
			'description' => 'Industrial Gothic Punk Rock WordPress theme.',
			'changelog'   => nl2br( esc_html( $release->body ?? 'See GitHub for details.' ) ),
		),
	);
}
add_filter( 'themes_api', 'trp_theme_update_details', 10, 3 );

function trp_fix_theme_directory_name( $source, $remote_source, $upgrader, $hook_extra ) {
	if ( ! isset( $hook_extra['theme'] ) || 'therustedpage' !== $hook_extra['theme'] ) {
		return $source;
	}
	return $source;
}
add_filter( 'upgrader_source_selection', 'trp_fix_theme_directory_name', 10, 4 );
