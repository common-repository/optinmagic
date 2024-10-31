<?php
/*
Plugin Name: Popup Builder for Lead Generation by OptinMagic - Generate Leads, Increase Sale and Grow Mailing List
Plugin URI: http://optinmagic.io
Description: Convert Visitors Into Customers. Grow your mailing list. Turn traffics into sales and keep them coming back to you with new offers and promotional emails.
Text Domain: optinmagic
Domain Path: /languages
Version: 0.2
Author: OptinMagic
Author URI: https://optinmagic.io
Tested up to: 6.2
License: GPLv2 or later
*/

add_action( 'admin_menu', 'opm_menu' );
add_action( 'admin_enqueue_scripts', 'opm_admin_script' );
add_action( 'wp_ajax_opm_save_user', 'opm_save_user' );
add_action( 'wp_ajax_opm_search_posts', 'opm_search_posts_by_type_and_title' );
add_action( 'wp_ajax_opm_save_campaign_assignment', 'opm_save_campaign_assignment' );
add_action( 'wp_ajax_opm_get_campaign_assignment', 'opm_get_campaign_assignment' );
add_action( 'wp_footer', 'opm_insert_campaign_code', 1 );
add_action( 'wp_loaded', 'opm_get_license_info' );

global $opm_admin_slug;
function opm_insert_campaign_code() {
	if ( is_single() || is_page() ) {
		// Get the current post ID
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return;
		}
		$opm_user    = maybe_unserialize( get_option( 'opm_user', false ) );
		$campaign_id = maybe_unserialize( get_post_meta( $post_id, 'opm_assigned_campaign', true ) );
		$mapping     = maybe_unserialize( get_option( 'opm_mapping', [] ) );
		if ( ! $opm_user || ! $campaign_id ) {
			return;
		}

		$client_id = $opm_user['client_id'];
		if ( is_array( $mapping[ $campaign_id ] ) && in_array( $post_id, $mapping[ $campaign_id ] ) ) {
			echo '<script>var op_magic_client_id = "' . esc_attr( $client_id ) . '";var op_magic_campaign_id ="' . esc_attr( $campaign_id ) . '" ;</script>
                <script async src="https://api.optinmagic.io/op_magic.js"></script>';
		}
	}


}

function opm_get_license_info() {
	if ( function_exists( 'is_plugin_active' ) && is_plugin_active( plugin_dir_path( __FILE__ ) . 'optinmagic.php' ) ) {
		die( plugin_dir_path( __FILE__ ) . 'optinmagic.php' );

	} else {
		// Plugin is not loaded, do something else
	}
}

function opm_search_posts_by_type_and_title() {
	global $wpdb;
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID, post_title, post_type FROM $wpdb->posts WHERE post_title LIKE %s AND post_status = 'publish'",
			'%' . sanitize_text_field( $_POST['post_title'] ) . '%'
		)
	);
	$posts   = [];
	if ( $results ) {
		foreach ( $results as $result ) {
			$post_id    = $result->ID;
			$post_title = $result->post_title;
			$posts[]    = [
				'id'    => (int) $post_id,
				'title' => sanitize_text_field( $post_title ),
				'type'  => sanitize_text_field( $_POST['post_type'] )
			];
		}
		die( json_encode( $posts ) );
	} else {
		die( 'No posts' );
	}
}


function opm_save_campaign_assignment() {
	$opm_mapping = maybe_unserialize( get_option( 'opm_mapping', [] ) );
	$campaign_id = (int) $_POST['campaign_id'];
	$post_ids    = opm_sanitize_array( $_POST['posts_ids'] );

	if ( $post_ids ) {
		foreach ( $post_ids as $post_id ) {
			//	remove selected post ids from existing mapping
			$opm_mapping = opm_remove_post_id_from_mapping( $post_id, $opm_mapping );
			update_post_meta( $post_id, 'opm_assigned_campaign', $campaign_id );
		}
	}
	//	insert selected post ids in the new mapping
	$opm_mapping[ $campaign_id ] = opm_sanitize_array( $_POST['posts_ids'] );

	update_option( 'opm_mapping', $opm_mapping );
	die( json_encode( $_POST ) );
}

function opm_remove_post_id_from_mapping( $post_id, $opm_mapping ) {

	foreach ( $opm_mapping as $campaign_id => $post_ids ) {
		$key = array_search( $post_id, $post_ids );
		array_splice( $post_ids, $key, 1 );
		$opm_mapping[ $campaign_id ] = $post_ids;
	}

	return $opm_mapping;
}

function opm_get_campaign_assignment() {
	$opm_mapping = maybe_unserialize( get_option( 'opm_mapping', [] ) );
	die( json_encode( $opm_mapping ) );
}


function opm_menu() {
	$menu = add_menu_page( 'OptinMagic', 'OptinMagic', 'manage_options', 'optinmagic', 'opm_admin_page', 'dashicons-format-status' );
}

function opm_admin_page() {
	include plugin_dir_path( __FILE__ ) . 'admin-page.php';
}


function opm_admin_script( $hook ) {
	global $opm_admin_slug;
	$opm_admin_slug = $hook;
	if ( 'toplevel_page_optinmagic' === $hook ) {
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/js/bootstrap.bundle.js' );
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'libs/select2.min.js' );
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'libs/select2.min.css' );
		wp_enqueue_script( 'vue3', plugin_dir_url( __FILE__ ) . 'libs/vue.global.js' );
		wp_enqueue_style( 'opm', plugin_dir_url( __FILE__ ) . '/style.css' );
	}
}

function opm_save_user() {
	$data              = [];
	$data['id']        = (int) $_POST['id'];
	$data['api_key']   = sanitize_text_field( $_POST['api_key'] );
	$data['email']     = sanitize_email( $_POST['email'] );
	$data['name']      = sanitize_text_field( $_POST['name'] );
	$data['client_id'] = sanitize_text_field( $_POST['client_id'] );
	update_option( 'opm_user', $data );
	die( json_encode( get_option( 'opm_user' ) ) );
}

function opm_sanitize_array( $input ) {

	$new_input = array();

	// Loop through the input and sanitize each of the values
	foreach ( $input as $key => $val ) {

		$new_input[ $key ] = ( isset( $input[ $key ] ) ) ?
			sanitize_text_field( $val ) :
			'';
	}

	return $new_input;
}
