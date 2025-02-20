<?php
/*
Plugin Name: BB Delete Cache (Updated)
Description: Adds a button in the WordPress admin bar to clear Beaver Builder cache for the current post or the entire site.
Plugin URI: https://github.com/weavedigitalstudio/bb-delete-cache/
Version: 1.1.0
Author: Gareth Bissland
Author URI: https://weave.co.nz
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Primary Branch:    main
GitHub Plugin URI: weavedigitalstudio/bb-delete-cache/
Text Domain: bb-delete-cache
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

namespace Weave\BBDeleteCache;

class BB_Delete_Cache_Admin_Bar {

    public function __construct() {
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'admin_bar_menu', array( $this, 'add_item' ), 100 );
        add_action( 'admin_post_purge_cache', array( $this, 'clear_cache' ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'bb-delete-cache', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    public function add_item() {
        if ( ! is_admin_bar_showing() ) {
            return;
        }

        global $post;

        $referer = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
        $action  = 'purge_cache';

        if ( ! is_admin() && isset( $post->ID ) ) {
            $this->add_sub_menu(
                'bb-delete-url-cache',
                'fl-builder-frontend-edit-link',
                __( 'Clear This Page Cache', 'bb-delete-cache' ),
                wp_nonce_url( admin_url( 'admin-post.php?action=' . $action . '&type=post-' . $post->ID . $referer ), $action . '_post-' . $post->ID )
            );
        }

        $this->add_sub_menu(
            'bb-delete-all-cache',
            'fl-builder-frontend-edit-link',
            __( 'Clear Beaver Builder Cache', 'bb-delete-cache' ),
            wp_nonce_url( admin_url( 'admin-post.php?action=' . $action . '&type=all' . $referer ), $action . '_all' )
        );
    }

    private function add_sub_menu( $id, $parent, $title, $href, $meta = false ) {
        global $wp_admin_bar;

        if ( ! is_admin_bar_showing() ) {
            return;
        }

        $wp_admin_bar->add_menu( array(
            'id'     => $id,
            'parent' => $parent,
            'title'  => $title,
            'href'   => $href,
            'meta'   => $meta,
        ));
    }

    public function clear_cache() {
        if ( isset( $_GET['type'], $_GET['_wpnonce'] ) ) {
            
            if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'purge_cache_' . $_GET['type'] ) ) {
                wp_nonce_ays( 'purge_cache' );
            }

            $type = explode( '-', $_GET['type'] );
            $type = reset( $type );
            $id   = explode( '-', $_GET['type'] );
            $id   = end( $id );

            if ( class_exists( 'FLBuilderModel' ) ) {
                switch ( $type ) {
                    case 'all':
                        FLBuilderModel::delete_asset_cache_for_all_posts();
                        break;
                    case 'post':
                        FLBuilderModel::delete_all_asset_cache( $id );
                        break;
                }
            }

            $redirect_url = wp_get_referer() ? wp_get_referer() : admin_url();
            wp_redirect( $redirect_url );
            exit;
        }
    }
}

add_action( 'init', function() {
    if ( class_exists( 'FLBuilder' ) ) {
        new BB_Delete_Cache_Admin_Bar();
    }
});
