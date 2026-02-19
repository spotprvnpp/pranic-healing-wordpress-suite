<?php
/**
 * Admin Dashboard & Settings
 */

class Pranic_Admin {
    
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'wp_ajax_pranic_get_roi_data', [ $this, 'ajax_get_roi_data' ] );
        add_action( 'wp_ajax_pranic_add_center', [ $this, 'ajax_add_center' ] );
        add_action( 'wp_ajax_pranic_add_course', [ $this, 'ajax_add_course' ] );
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Pranic CRM',
            'Pranic CRM',
            'manage_options',
            'pranic-dashboard',
            [ $this, 'render_dashboard' ],
            'dashicons-heart',
            20
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Centers',
            'Centers',
            'manage_options',
            'pranic-centers',
            [ $this, 'render_centers' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Courses',
            'Courses',
            'manage_options',
            'pranic-courses',
            [ $this, 'render_courses' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Registrations',
            'Registrations',
            'manage_options',
            'pranic-registrations',
            [ $this, 'render_registrations' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Payments & ROI',
            'Payments & ROI',
            'manage_options',
            'pranic-payments',
            [ $this, 'render_payments' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Healing Sessions',
            'Healing Sessions',
            'manage_options',
            'pranic-healings',
            [ $this, 'render_healings' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Events & Marketing',
            'Events & Marketing',
            'manage_options',
            'pranic-events',
            [ $this, 'render_events' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Feedback & Complaints',
            'Feedback & Complaints',
            'manage_options',
            'pranic-feedback',
            [ $this, 'render_feedback' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Staff Management',
            'Staff Management',
            'manage_options',
            'pranic-staff',
            [ $this, 'render_staff' ]
        );
        
        add_submenu_page(
            'pranic-dashboard',
            'Meditation Scheduler',
            'Meditation Scheduler',
            'manage_options',
            'pranic-meditation',
            [ $this, 'render_meditation' ]
        );
    }
    
    public function render_dashboard() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">
                <i class="fas fa-spa"></i> Pranic CRM Dashboard
            </h1>
            <div id="pranic-dashboard-content">
                <!-- Dashboard content loads here via AJAX -->
            </div>
        </div>
        <script>
            jQuery(function($) {
                $.ajax({
                    url: pranicAjax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'pranic_get_roi_data',
                        nonce: pranicAjax.nonce,
                    },
                    success: function(response) {
                        $('#pranic-dashboard-content').html(response.html);
                    }
                });
            });
        </script>
        <?php
    }
    
    public function render_centers() {
        echo '<div class="wrap"><h1>Centers Management</h1>';
        echo '<p>Centers management interface will be rendered here.</p></div>';
    }
    
    public function render_courses() {
        echo '<div class="wrap"><h1>Courses Management</h1>';
        echo '<p>Courses management interface will be rendered here.</p></div>';
    }
    
    public function render_registrations() {
        echo '<div class="wrap"><h1>Registrations</h1>';
        echo '<p>Registrations management interface will be rendered here.</p></div>';
    }
    
    public function render_payments() {
        echo '<div class="wrap"><h1>Payments & ROI Reports</h1>';
        echo '<p>Payment and ROI tracking interface will be rendered here.</p></div>';
    }
    
    public function render_healings() {
        echo '<div class="wrap"><h1>Healing Sessions</h1>';
        echo '<p>Healing sessions management interface will be rendered here.</p></div>';
    }
    
    public function render_events() {
        echo '<div class="wrap"><h1>Events & Marketing Campaigns</h1>';
        echo '<p>Events and marketing management interface will be rendered here.</p></div>';
    }
    
    public function render_feedback() {
        echo '<div class="wrap"><h1>Feedback & Complaints</h1>';
        echo '<p>Feedback and complaints management interface will be rendered here.</p></div>';
    }
    
    public function render_staff() {
        echo '<div class="wrap"><h1>Staff Management</h1>';
        echo '<p>Staff and trainer management interface will be rendered here.</p></div>';
    }
    
    public function render_meditation() {
        echo '<div class="wrap"><h1>Meditation Scheduler</h1>';
        echo '<p>Meditation sessions scheduling interface will be rendered here.</p></div>';
    }
    
    public function ajax_get_roi_data() {
        check_ajax_referer( 'pranic_crm_nonce' );
        
        global $wpdb;
        
        $html = '<div class="pranic-admin-dashboard">';
        $html .= '<div class="stats-grid">';
        
        // Stats cards
        $total_revenue = $wpdb->get_var( "SELECT SUM(amount) FROM {$wpdb->prefix}pranic_payments WHERE status = 'success'" );
        $html .= '<div class="stat-card"><h3>₹' . number_format( $total_revenue ?: 0, 2 ) . '</h3><p>Total Revenue</p></div>';
        
        $total_students = $wpdb->get_var( "SELECT COUNT(DISTINCT student_id) FROM {$wpdb->prefix}pranic_registrations" );
        $html .= '<div class="stat-card"><h3>' . $total_students . '</h3><p>Total Students</p></div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        wp_send_json_success( [ 'html' => $html ] );
    }
    
    public function ajax_add_center() {
        check_ajax_referer( 'pranic_crm_nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        global $wpdb;
        
        $inserted = $wpdb->insert(
            "{$wpdb->prefix}pranic_centers",
            [
                'center_name'   => sanitize_text_field( $_POST['center_name'] ),
                'location'      => sanitize_text_field( $_POST['location'] ),
                'email'         => sanitize_email( $_POST['email'] ),
                'phone'         => sanitize_text_field( $_POST['phone'] ),
            ]
        );
        
        if ( $inserted ) {
            wp_send_json_success( [ 'center_id' => $wpdb->insert_id ] );
        } else {
            wp_send_json_error( 'Failed to add center' );
        }
    }
    
    public function ajax_add_course() {
        check_ajax_referer( 'pranic_crm_nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Insufficient permissions' );
        }
        
        global $wpdb;
        
        $inserted = $wpdb->insert(
            "{$wpdb->prefix}pranic_courses",
            [
                'center_id'      => intval( $_POST['center_id'] ),
                'course_name'    => sanitize_text_field( $_POST['course_name'] ),
                'price'          => floatval( $_POST['price'] ),
                'duration_hours' => intval( $_POST['duration_hours'] ),
            ]
        );
        
        if ( $inserted ) {
            wp_send_json_success( [ 'course_id' => $wpdb->insert_id ] );
        } else {
            wp_send_json_error( 'Failed to add course' );
        }
    }
}
?>