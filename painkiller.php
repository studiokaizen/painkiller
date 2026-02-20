<?php
/**
 * Plugin Name:       Painkiller
 * Plugin URI:        https://github.com/studiokaizen/painkiller
 * Description:       Security and performance cleanup tweaks.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Vinayak Anivase
 * Author URI:        https://kaizendesignstudio.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       painkiller
 * Domain Path:       /languages
 *
 * @package Painkiller
 */

defined( 'ABSPATH' ) || exit;

/**
 * Redirect all feeds to the homepage.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_disable_feeds() {
	wp_safe_redirect( home_url() );
	exit;
}

add_action( 'do_feed', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_rdf', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_rss', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_rss2', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_rss2_comments', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_atom', 'painkiller_disable_feeds', 1 );
add_action( 'do_feed_atom_comments', 'painkiller_disable_feeds', 1 );

/**
 * Disable the language selector on the login screen.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function painkiller_disable_login_language_dropdown() {
	return false;
}

add_filter( 'login_display_language_dropdown', 'painkiller_disable_login_language_dropdown' );

/**
 * Disable XML-RPC.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function painkiller_disable_xmlrpc() {
	return false;
}

add_filter( 'xmlrpc_enabled', 'painkiller_disable_xmlrpc' );
add_filter( 'xmlrpc_methods', 'painkiller_disable_xmlrpc' );

/**
 * Remove unwanted tags and links from <head>.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
}

add_action( 'init', 'painkiller_cleanup_head' );

/**
 * Remove REST API link tag from HTML headers.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_remove_rest_header() {
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}

add_action( 'init', 'painkiller_remove_rest_header' );

/**
 * Remove shortlink HTTP header.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_remove_shortlink_header() {
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
}

add_action( 'init', 'painkiller_remove_shortlink_header' );

/**
 * Remove emoji support scripts and filters.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'init', 'painkiller_disable_emojis' );

/**
 * Disable oEmbed discovery and host JS.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_disable_oembeds() {
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}

add_action( 'init', 'painkiller_disable_oembeds' );

/**
 * Disable default users REST endpoints for unauthenticated users.
 *
 * @since 1.0.0
 *
 * @param array $endpoints REST API endpoints.
 * @return array
 */
function painkiller_disable_user_endpoints( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		if ( isset( $endpoints['/wp/v2/users'] ) ) {
			unset( $endpoints['/wp/v2/users'] );
		}

		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
	}

	return $endpoints;
}

add_filter( 'rest_endpoints', 'painkiller_disable_user_endpoints', 10, 1 );

/**
 * Set JPEG quality to 100.
 *
 * @since 1.0.0
 *
 * @return int
 */
function painkiller_set_jpeg_quality() {
	return 100;
}

add_filter( 'jpeg_quality', 'painkiller_set_jpeg_quality' );

/**
 * Update login logo link URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function painkiller_login_header_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'painkiller_login_header_url' );

/**
 * Update login logo title text.
 *
 * @since 1.0.0
 *
 * @return string
 */
function painkiller_login_header_text() {
	return get_bloginfo( 'name' );
}

add_filter( 'login_headertext', 'painkiller_login_header_text' );

/**
 * Dequeue core front-end styles.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_dequeue_core_styles() {
	wp_deregister_style( 'wp-block-library' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_enqueue_scripts', 'painkiller_dequeue_core_styles', 20 );

/**
 * Remove global styles SVG filters for FSE.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_remove_fse_svg_filters() {
	remove_action( 'wp_body_open', 'gutenberg_global_styles_render_svg_filters' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}

add_action( 'init', 'painkiller_remove_fse_svg_filters' );

/**
 * Remove the version query string from enqueued assets on the front end.
 *
 * @since 1.0.0
 *
 * @param string $url Asset URL.
 * @return string
 */
function painkiller_remove_asset_version( $url ) {
	if ( is_admin() ) {
		return $url;
	}

	if ( $url ) {
		return esc_url( remove_query_arg( 'ver', $url ) );
	}

	return $url;
}

add_filter( 'script_loader_src', 'painkiller_remove_asset_version', 15, 1 );
add_filter( 'style_loader_src', 'painkiller_remove_asset_version', 15, 1 );

/**
 * Remove the author, contributor, and subscriber roles.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_remove_roles() {
	remove_role( 'author' );
	remove_role( 'contributor' );
	remove_role( 'subscriber' );
}

add_action( 'init', 'painkiller_remove_roles' );

/**
 * Disable attachment pages and return a 404.
 *
 * @since 1.0.0
 *
 * @return void
 */
function painkiller_attachment_redirect_not_found() {
	if ( is_attachment() ) {
		global $wp_query;
		$wp_query->set_404();

		status_header( 404 );
	}
}

add_filter( 'template_redirect', 'painkiller_attachment_redirect_not_found' );

/**
 * Disable canonical redirects for attachment pages.
 *
 * @since 1.0.0
 *
 * @param string $url Canonical URL.
 * @return string
 */
function painkiller_attachment_canonical_redirect( $url ) {
	painkiller_attachment_redirect_not_found();
	return $url;
}

add_filter( 'redirect_canonical', 'painkiller_attachment_canonical_redirect', 0, 2 );

/**
 * Disable attachment permalinks; link directly to the file URL.
 *
 * @since 1.0.0
 *
 * @param string $url Attachment URL.
 * @param int    $id  Attachment post ID.
 * @return string
 */
function painkiller_attachment_link( $url, $id ) {
	$attachment_url = wp_get_attachment_url( $id );

	if ( $attachment_url ) {
		return $attachment_url;
	}

	return $url;
}

add_filter( 'attachment_link', 'painkiller_attachment_link', 10, 2 );

/**
 * Discourage indexing in non-production environments.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function painkiller_discourage_search_engine_indexing() {
	return 'production' === wp_get_environment_type();
}

add_filter( 'pre_option_blog_public', 'painkiller_discourage_search_engine_indexing' );
