<?php

/**
 * Plugin Name: MY Questionnaire
 * Plugin URI: http//my-dev.pro
 * Author: MY-Dev | Mohamed Youssef
 * Author URI: http://my-dev.pro
 * Description: Allaws visitors to take questionnaire and shows results in your dashboard
 * Version: 1.0.0
 * License: GPLv2 or later
 * Requires at least: 6.0
 * Requires PHP: 7.3
 * Text Domain: my-dev-questionnaire
 * Domain Path: /languages
 */

 /** Copyrights © 2022 Mohamed Youssef */

if ( !defined( 'ABSPATH' ) ) exit();

function my_dev_register_ques_main_menu() {
    add_menu_page( __( 'MY Questionnaire', 'my-dev-questionnaire' ), __( 'MY Questionnaire', 'my-dev-questionnaire' ), 'manage_options', 'my_questionnaire_Dashboard', 'my_dev_main_menu_page', 'dashicons-chart-bar', 100 );
	add_submenu_page( 'my_questionnaire_Dashboard', __( 'About', 'my-dev-questionnaire' ), __( 'About', 'my-dev-questionnaire' ), 'manage_options', 'about', 'my_dev_register_about_ques_page', 1 );
}

function my_dev_main_menu_page() {

    echo "Hello World";

}

function my_dev_register_about_ques_page() {

	echo "about";
    echo __FILE__;

}

/** questionnaire page control  */

function my_dev_activate_ques_plugin() {

    global $wpdb;

    $page_title = wp_strip_all_tags( 'My Questionnaire' );

    $var_query = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_title = '{$page_title}'";

    $is_created = $wpdb->get_var( $var_query );

    if ( $is_created == '0' ) {

        $my_dev_post = array(
            'post_author'       => get_current_user_id(),
            'post_title'        => esc_sql( $page_title ),
            'post_content'      => 'My Questionnaire',
            'post_status'       => 'publish',
            'comment_status'    => 'closed',
            'post_type'         => 'page',
        );
    
        wp_insert_post( $my_dev_post, true );
    }

    if ( $is_created > 0 ) {

        $data_query = array(
            'order' => [ 'post_status' => 'publish', ],
            'where' => [ 'post_title' => $page_title, ],
        );

        $wpdb->update( $wpdb->posts, $data_query['order'], $data_query['where'] );

    }

}

function my_dev_deactivate_ques_plugin() {

    global $wpdb;

    $data_query = array(
        'order'     => [ 'post_status' => 'trash', ],
        'where'     => [ 'post_title' => 'My Questionnaire', ],
    );

    $wpdb->update( $wpdb->posts, $data_query['order'], $data_query['where'] );
}

function my_dev_uninstall_ques_plugin() {
    
    global $wpdb;

    $page_title = esc_sql('My Questionnaire');

    $query_data = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_title = '{$page_title}'";

    $is_page_exests = $wpdb->get_var( $query_data );

    if ( $is_page_exests != '0' ) {
        $wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_title = '{$page_title}'" );
    }

}


// Register menu list
add_action( 'admin_menu', 'my_dev_register_ques_main_menu' );

register_activation_hook( __FILE__, 'my_dev_activate_ques_plugin' );

register_deactivation_hook( __FILE__, 'my_dev_deactivate_ques_plugin' );

register_uninstall_hook( __FILE__, 'my_dev_uninstall_ques_plugin' );