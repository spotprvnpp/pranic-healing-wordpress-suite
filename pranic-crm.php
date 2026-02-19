<?php
/**
 * Plugin Name: Pranic CRM
 * Description: A CRM plugin for Pranic Healing.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://yourwebsite.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Initialize the plugin
function pranic_crm_init() {
    // Initialization code goes here...
}
add_action( 'init', 'pranic_crm_init' );
