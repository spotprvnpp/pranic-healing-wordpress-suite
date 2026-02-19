<?php
/**
 * Database Management Class
 */

class Pranic_Database {
    
    private $wpdb;
    private $charset_collate;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->charset_collate = $wpdb->get_charset_collate();
    }
    
    public function create_tables() {
        // Centers table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_centers (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_name VARCHAR(255) NOT NULL,
                location VARCHAR(255),
                coordinator_name VARCHAR(255),
                email VARCHAR(255),
                phone VARCHAR(20),
                opening_date DATE,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_center_name (center_name),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Courses table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_courses (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                course_name VARCHAR(255) NOT NULL,
                course_description LONGTEXT,
                duration_hours INT,
                price DECIMAL(10, 2),
                trainer_id BIGINT(20) UNSIGNED,
                start_date DATE,
                end_date DATE,
                max_students INT DEFAULT 30,
                status ENUM('draft', 'published', 'completed') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_course_name (course_name),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Registrations table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_registrations (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                course_id BIGINT(20) UNSIGNED,
                student_id BIGINT(20) UNSIGNED,
                center_id BIGINT(20) UNSIGNED,
                registration_date DATE,
                status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
                payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
                progress_percentage INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (course_id) REFERENCES {$this->wpdb->prefix}pranic_courses(id),
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_student (student_id),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Healing Sessions table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_healings (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                facilitator_id BIGINT(20) UNSIGNED,
                patient_id BIGINT(20) UNSIGNED,
                healing_type VARCHAR(255),
                session_date DATETIME,
                duration_minutes INT,
                price DECIMAL(10, 2),
                result VARCHAR(255),
                feedback_received TINYINT(1) DEFAULT 0,
                status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_session_date (session_date),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Events table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_events (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                event_title VARCHAR(255) NOT NULL,
                event_description LONGTEXT,
                event_type ENUM('course', 'workshop', 'seminar', 'meditation', 'other') DEFAULT 'workshop',
                start_date DATETIME,
                end_date DATETIME,
                location VARCHAR(255),
                event_coordinator_id BIGINT(20) UNSIGNED,
                max_attendees INT DEFAULT 100,
                registration_status ENUM('open', 'closed') DEFAULT 'open',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_event_type (event_type),
                INDEX idx_start_date (start_date)
            ) $this->charset_collate;
        " );
        
        // Payments table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_payments (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                transaction_id VARCHAR(255) UNIQUE,
                student_id BIGINT(20) UNSIGNED,
                course_id BIGINT(20) UNSIGNED,
                amount DECIMAL(10, 2) NOT NULL,
                payment_type ENUM('debit', 'credit') NOT NULL,
                payment_method VARCHAR(255),
                payment_date DATETIME,
                status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
                reference_notes LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_transaction_id (transaction_id),
                INDEX idx_payment_date (payment_date),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Feedback & Complaints table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_feedback (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                submitter_id BIGINT(20) UNSIGNED,
                subject VARCHAR(255) NOT NULL,
                message LONGTEXT NOT NULL,
                rating INT DEFAULT 5,
                feedback_type ENUM('feedback', 'complaint', 'suggestion') DEFAULT 'feedback',
                status ENUM('open', 'in-progress', 'resolved') DEFAULT 'open',
                assigned_to BIGINT(20) UNSIGNED,
                resolution_notes LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_status (status),
                INDEX idx_feedback_type (feedback_type)
            ) $this->charset_collate;
        " );
        
        // Meditation Scheduler table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_meditation_sessions (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                facilitator_id BIGINT(20) UNSIGNED,
                session_title VARCHAR(255) NOT NULL,
                session_date DATETIME,
                duration_minutes INT,
                meditation_type VARCHAR(255),
                max_participants INT DEFAULT 50,
                registered_count INT DEFAULT 0,
                status ENUM('scheduled', 'ongoing', 'completed', 'cancelled') DEFAULT 'scheduled',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_session_date (session_date),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
        
        // Marketing Campaigns table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_marketing_campaigns (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                campaign_name VARCHAR(255) NOT NULL,
                campaign_type ENUM('social_media', 'email', 'event', 'other') DEFAULT 'social_media',
                start_date DATE,
                end_date DATE,
                budget DECIMAL(10, 2),
                reach INT,
                engagement INT,
                conversions INT,
                roi_percentage DECIMAL(5, 2),
                status ENUM('planned', 'active', 'completed') DEFAULT 'planned',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_campaign_type (campaign_type)
            ) $this->charset_collate;
        " );
        
        // Staff/Trainers table
        $this->wpdb->query( "
            CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}pranic_staff (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                center_id BIGINT(20) UNSIGNED,
                user_id BIGINT(20) UNSIGNED,
                staff_name VARCHAR(255) NOT NULL,
                role ENUM('trainer', 'facilitator', 'coordinator', 'admin') DEFAULT 'trainer',
                specialization VARCHAR(255),
                email VARCHAR(255),
                phone VARCHAR(20),
                hire_date DATE,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (center_id) REFERENCES {$this->wpdb->prefix}pranic_centers(id),
                INDEX idx_role (role),
                INDEX idx_status (status)
            ) $this->charset_collate;
        " );
    }
}
?>