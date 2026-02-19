<?php
/**
 * Plugin Name: Pranic Healing CRM
 * Plugin URI: https://pranichealing.ae
 * Description: Comprehensive CRM for Pranic Healing centers with ROI tracking, courses, registrations, healings, and analytics
 * Version: 1.0.0
 * Author: Pranic Development Team
 * Author URI: https://pranichealing.ae
 * License: GPL v3
 * Domain Path: /languages
 * Text Domain: pranic-crm
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'PRANIC_CRM_VERSION', '1.0.0' );
define( 'PRANIC_CRM_FILE', __FILE__ );
define( 'PRANIC_CRM_DIR', dirname( __FILE__ ) );
define( 'PRANIC_CRM_URL', plugins_url( '/', __FILE__ ) );

// Require files
require_once PRANIC_CRM_DIR . '/includes/class-database.php';
require_once PRANIC_CRM_DIR . '/includes/class-api.php';
require_once PRANIC_CRM_DIR . '/admin/class-admin.php';

// Activation hook
register_activation_hook( __FILE__, 'pranic_crm_activate' );

function pranic_crm_activate() {
    // Create database tables
    $db = new Pranic_Database();
    $db->create_tables();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'pranic_crm_deactivate' );

function pranic_crm_deactivate() {
    flush_rewrite_rules();
}

// Plugin initialization
function pranic_crm_init() {
    new Pranic_Admin();
}
add_action( 'plugins_loaded', 'pranic_crm_init' );

// Enqueue admin assets
function pranic_crm_admin_assets() {
    wp_enqueue_style( 'pranic-crm-admin', PRANIC_CRM_URL . 'assets/css/admin.css' );
    wp_enqueue_script( 'pranic-crm-admin', PRANIC_CRM_URL . 'assets/js/admin.js', [ 'jquery', 'chartjs' ], PRANIC_CRM_VERSION, true );
    
    wp_localize_script( 'pranic-crm-admin', 'pranicAjax', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'pranic_crm_nonce' ),
    ] );
}
add_action( 'admin_enqueue_scripts', 'pranic_crm_admin_assets' );
?>