<?php
/**
 * REST API for Dashboard & Frontend
 */

class Pranic_API {
    
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }
    
    public function register_routes() {
        // ROI Dashboard data endpoint
        register_rest_route( 'pranic/v1', '/roi-dashboard/(?P<center_id>\d+)', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_roi_dashboard' ],
            'permission_callback' => [ $this, 'check_permission' ],
        ] );
        
        // Center statistics
        register_rest_route( 'pranic/v1', '/centers/(?P<center_id>\d+)/stats', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_center_stats' ],
            'permission_callback' => [ $this, 'check_permission' ],
        ] );
        
        // Real-time notifications
        register_rest_route( 'pranic/v1', '/notifications', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_notifications' ],
            'permission_callback' => [ $this, 'check_permission' ],
        ] );
    }
    
    public function get_roi_dashboard( $request ) {
        global $wpdb;
        $center_id = $request['center_id'];
        
        // Revenue calculation
        $revenue = $wpdb->get_var( "
            SELECT SUM(amount) FROM {$wpdb->prefix}pranic_payments 
            WHERE center_id = $center_id AND payment_type = 'credit' AND status = 'success'
        " );
        
        // Course performance
        $courses = $wpdb->get_results( "
            SELECT 
                pc.id, pc.course_name, 
                COUNT(pr.id) as total_students,
                SUM(pp.amount) as course_revenue
            FROM {$wpdb->prefix}pranic_courses pc
            LEFT JOIN {$wpdb->prefix}pranic_registrations pr ON pc.id = pr.course_id
            LEFT JOIN {$wpdb->prefix}pranic_payments pp ON pr.course_id = pp.course_id
            WHERE pc.center_id = $center_id
            GROUP BY pc.id
        " );
        
        return [
            'success'  => true,
            'revenue'  => $revenue ?: 0,
            'courses'  => $courses,
            'timestamp' => current_time( 'mysql' ),
        ];
    }
    
    public function get_center_stats( $request ) {
        global $wpdb;
        $center_id = $request['center_id'];
        
        $stats = [
            'total_students'     => $wpdb->get_var( "SELECT COUNT(DISTINCT student_id) FROM {$wpdb->prefix}pranic_registrations WHERE center_id = $center_id" ),
            'active_courses'     => $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}pranic_courses WHERE center_id = $center_id AND status = 'published'" ),
            'healing_sessions'   => $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}pranic_healings WHERE center_id = $center_id AND status = 'completed'" ),
            'total_revenue'      => $wpdb->get_var( "SELECT SUM(amount) FROM {$wpdb->prefix}pranic_payments WHERE center_id = $center_id AND status = 'success'" ),
            'pending_feedback'   => $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}pranic_feedback WHERE center_id = $center_id AND status = 'open'" ),
        ];
        
        return $stats;
    }
    
    public function get_notifications( $request ) {
        global $wpdb;
        $user_id = get_current_user_id();
        
        $notifications = $wpdb->get_results( "
            SELECT * FROM {$wpdb->prefix}pranic_feedback 
            WHERE assigned_to = $user_id AND status = 'open'
            ORDER BY created_at DESC
            LIMIT 10
        " );
        
        return [
            'unread_count' => count( $notifications ),
            'notifications' => $notifications,
        ];
    }
    
    public function check_permission() {
        return current_user_can( 'manage_options' );
    }
}
?>