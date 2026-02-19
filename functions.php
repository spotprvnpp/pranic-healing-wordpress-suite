<?php
/**
 * Pranic Healing Foundation Theme
 * A clean, minimal dashboard-first WordPress theme
 */

// Define theme constants
define( 'PRANIC_THEME_VERSION', '1.0.0' );
define( 'PRANIC_THEME_DIR', get_template_directory() );
define( 'PRANIC_THEME_URI', get_template_directory_uri() );

// Theme setup
function pranic_theme_setup() {
    // Add title tag support
    add_theme_support( 'title-tag' );
    
    // Add featured images support
    add_theme_support( 'post-thumbnails' );
    
    // Add WooCommerce support (for payments)
    add_theme_support( 'woocommerce' );
    
    // Register menus
    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'pranic-healing' ),
        'footer'  => __( 'Footer Menu', 'pranic-healing' ),
    ] );
}
add_action( 'after_setup_theme', 'pranic_theme_setup' );

// Enqueue stylesheets & scripts
function pranic_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style( 
        'pranic-main', 
        PRANIC_THEME_URI . '/style.css', 
        [], 
        PRANIC_THEME_VERSION 
    );
    
    // Bootstrap 5 for responsive layout
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
    );
    
    // Chart.js for ROI graphs
    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js',
        [],
        '4.4.0',
        true
    );
    
    // Custom scripts
    wp_enqueue_script(
        'pranic-main',
        PRANIC_THEME_URI . '/assets/js/main.js',
        [ 'jquery', 'chartjs' ],
        PRANIC_THEME_VERSION,
        true
    );
    
    // Font Awesome icons
    wp_enqueue_style(
        'fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
    );
}
add_action( 'wp_enqueue_scripts', 'pranic_enqueue_assets' );

// Custom post types registration
function pranic_register_post_types() {
    // Courses
    register_post_type( 'ph_course', [
        'labels' => [
            'name'          => 'Courses',
            'singular_name' => 'Course'
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'    => 'dashicons-book'
    ] );
    
    // Healings
    register_post_type( 'ph_healing', [
        'labels' => [
            'name'          => 'Healing Sessions',
            'singular_name' => 'Healing'
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'    => 'dashicons-heart'
    ] );
    
    // Events
    register_post_type( 'ph_event', [
        'labels' => [
            'name'          => 'Events',
            'singular_name' => 'Event'
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'    => 'dashicons-calendar'
    ] );
}
add_action( 'init', 'pranic_register_post_types' );

// Hide admin bar for non-admin users (cleaner UX)
function pranic_hide_admin_bar() {
    if ( ! current_user_can( 'manage_options' ) && ! is_admin() ) {
        show_admin_bar( false );
    }
}
add_action( 'init', 'pranic_hide_admin_bar' );

// Custom logo support
function pranic_custom_logo_setup() {
    add_theme_support( 'custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
}
add_action( 'after_setup_theme', 'pranic_custom_logo_setup' );
?>