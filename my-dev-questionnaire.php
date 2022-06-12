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

/** Menu Lists */

function my_dev_register_ques_main_menu() {
    add_menu_page( __( 'MY Questionnaire', 'my-dev-questionnaire' ), __( 'MY Questionnaire', 'my-dev-questionnaire' ), 'manage_options', 'my_questionnaire_Dashboard', 'my_dev_main_menu_page', 'dashicons-chart-bar', 100 );
	add_submenu_page( 'my_questionnaire_Dashboard', __( 'About', 'my-dev-questionnaire' ), __( 'About', 'my-dev-questionnaire' ), 'manage_options', 'about', 'my_dev_register_about_ques_page', 1 );
}

/**  */

function my_dev_main_menu_page() {

    echo "Hello World <br />";


    global $wpdb;

}

function my_dev_register_about_ques_page() {

	echo "about";
    echo __FILE__;

}

/** questionnaire page control  */

function my_dev_activate_ques_plugin() {

    global $wpdb;

    // Check if page exsits
    $page_title = wp_strip_all_tags( 'My Questionnaire' );

    $check_page_exists_query = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_title = '{$page_title}'";

    $is_exists = $wpdb->get_var( $check_page_exists_query );

    if ( $is_exists == '0' ) {

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

    // Update page to published
    if ( $is_exists > 0 ) {

        $data_query = array(
            'order' => [ 'post_status' => 'publish', ],
            'where' => [ 'post_title' => $page_title, ],
        );

        $wpdb->update( $wpdb->posts, $data_query['order'], $data_query['where'] );

    }

    // Check if Database Table exists
    $table_name = $wpdb->prefix . "questionnaire";
    
    $database_table_check_query = "SHOW TABLES LIKE '{$table_name}'";

    $database_table_is_exists = $wpdb->get_var( $database_table_check_query );

    if ( $database_table_is_exists == null ) {
        $create_database_table_query = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(64) NOT NULL,
            national_id VARCHAR(14) NOT NULL,
            mobile_no VARCHAR(11) NOT NULL,
            signture VARCHAR(60) NOT NULL,
            PRIMARY KEY (ID)
        )";

        $wpdb->query( $create_database_table_query );
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


/** MY-Dev */

add_action( 'admin_menu', 'my_dev_register_ques_main_menu' );

register_activation_hook( __FILE__, 'my_dev_activate_ques_plugin' );

register_deactivation_hook( __FILE__, 'my_dev_deactivate_ques_plugin' );

register_uninstall_hook( __FILE__, 'my_dev_uninstall_ques_plugin' );