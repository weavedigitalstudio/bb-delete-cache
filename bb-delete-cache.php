<?php
/*
Plugin Name: BB Delete Cache (Updated)
Description: Adds a button in the WordPress admin bar to clear Beaver Builder cache for the current post or the entire site.
Plugin URI: https://github.com/weavedigitalstudio/bb-delete-cache/
Version: 1.2.0
Author: Gareth Bissland, Weave Digital Studio
Author URI: https://weave.co.nz
License: GPLv2
Primary Branch: main
GitHub Plugin URI: weavedigitalstudio/bb-delete-cache/
Text Domain: bb-delete-cache
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Weave_BB_Delete_Cache {

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
    
        // Restrict cache clearing to Editors and Admins
        if ( ! current_user_can( 'edit_pages' ) ) {
            return;
        }
    
        global $post;
        global $wp_admin_bar;
    
        $referer = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
        $action  = 'purge_cache';
    
        // **Check if the user has Beaver Builder frontend editing access**
        $has_bb_access = class_exists( 'FLBuilderUserAccess' ) && FLBuilderUserAccess::current_user_can('builder_access');
    
        // **Set parent menu ID based on BB access**
        $parent_menu_id = $has_bb_access ? 'fl-builder-frontend-edit-link' : 'bb-delete-cache';
    
        // **If no BB access, create a new top-level admin bar menu**
        if ( ! $has_bb_access ) {
            $wp_admin_bar->add_menu( array(
                'id'    => 'bb-delete-cache',
                'title' => __( 'Clear BB Cache', 'bb-delete-cache' ),
                'href'  => '#',
            ));
        }
    
        // **Add "Clear This Page Cache" if on a post**
        if ( ! is_admin() && isset( $post->ID ) ) {
            $this->add_sub_menu(
                'bb-delete-url-cache',
                $parent_menu_id,
                __( 'Clear This Page Cache', 'bb-delete-cache' ),
                wp_nonce_url( admin_url( 'admin-post.php?action=' . $action . '&type=post-' . $post->ID . $referer ), $action . '_post-' . $post->ID )
            );
        }
    
        // **Add "Clear All Cache" option**
        $this->add_sub_menu(
            'bb-delete-all-cache',
            $parent_menu_id,
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
        // Restrict access to Editors and Admins
        if ( ! current_user_can( 'edit_pages' ) ) {
            wp_die( __( 'You do not have permission to clear the cache.', 'bb-delete-cache' ), 403 );
        }

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

// Initialize the plugin
add_action( 'init', function() {
    if ( class_exists( 'FLBuilder' ) ) {
        new Weave_BB_Delete_Cache();
    }
});
