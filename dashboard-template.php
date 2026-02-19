<?php
/**
 * Template Name: Admin Dashboard
 * Description: Master dashboard for foundation administrators
 */

// Restrict to administrators only
if ( ! current_user_can( 'manage_options' ) ) {
    wp_redirect( home_url() );
    exit;
}

get_header();
?>

<div class="container-fluid dashboard-wrapper">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 col-md-4 sidebar-nav bg-light">
            <div class="sidebar-header mb-4">
                <h3 class="text-primary">
                    <i class="fas fa-spa"></i> Pranic Admin
                </h3>
            </div>
            
            <nav class="nav flex-column">
                <a class="nav-link active" href="#overview">
                    <i class="fas fa-chart-line"></i> Dashboard Overview
                </a>
                <a class="nav-link" href="#roi-reports">
                    <i class="fas fa-chart-pie"></i> ROI Reports
                </a>
                <a class="nav-link" href="#centers">
                    <i class="fas fa-map-marker-alt"></i> Centers
                </a>
                <a class="nav-link" href="#courses">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a class="nav-link" href="#registrations">
                    <i class="fas fa-clipboard-list"></i> Registrations
                </a>
                <a class="nav-link" href="#healings">
                    <i class="fas fa-heart"></i> Healing Sessions
                </a>
                <a class="nav-link" href="#events">
                    <i class="fas fa-calendar-alt"></i> Events
                </a>
                <a class="nav-link" href="#payments">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
                <a class="nav-link" href="#feedback">
                    <i class="fas fa-comments"></i> Feedback & Complaints
                </a>
                <a class="nav-link" href="#trainers">
                    <i class="fas fa-users"></i> Trainers & Students
                </a>
                <a class="nav-link" href="#meditation">
                    <i class="fas fa-brain"></i> Meditation Scheduler
                </a>
            </nav>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-lg-9 col-md-8 main-content p-4">
            <!-- ROI Overview Cards -->
            <section id="overview" class="mb-5">
                <h2 class="mb-4">Dashboard Overview</h2>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-left-primary">
                            <div class="card-body">
                                <div class="text-primary font-weight-bold text-uppercase mb-2">
                                    Total Revenue
                                </div>
                                <div class="h3 mb-0">₹<span class="revenue-total">0</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-left-success">
                            <div class="card-body">
                                <div class="text-success font-weight-bold text-uppercase mb-2">
                                    Active Students
                                </div>
                                <div class="h3 mb-0"><span class="active-students">0</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-left-info">
                            <div class="card-body">
                                <div class="text-info font-weight-bold text-uppercase mb-2">
                                    Courses
                                </div>
                                <div class="h3 mb-0"><span class="total-courses">0</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card border-left-warning">
                            <div class="card-body">
                                <div class="text-warning font-weight-bold text-uppercase mb-2">
                                    Healing Sessions
                                </div>
                                <div class="h3 mb-0"><span class="healing-count">0</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- ROI Reports by Center -->
            <section id="roi-reports" class="mb-5">
                <h2 class="mb-4">ROI Reports by Center</h2>
                <div class="card">
                    <div class="card-body">
                        <canvas id="roiChart"></canvas>
                    </div>
                </div>
                
                <div class="table-responsive mt-4">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Center Name</th>
                                <th>Total Revenue</th>
                                <th>Expenses</th>
                                <th>ROI %</th>
                                <th>Active Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="roi-table-body">
                            <!-- Populated by AJAX -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .dashboard-wrapper {
        display: flex;
        min-height: calc(100vh - 200px);
    }
    
    .sidebar-nav {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        border-right: 1px solid #dee2e6;
    }
    
    .stats-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .border-left-primary {
        border-left-color: #4e73df !important;
    }
    
    .border-left-success {
        border-left-color: #1cc88a !important;
    }
    
    .border-left-info {
        border-left-color: #36b9cc !important;
    }
    
    .border-left-warning {
        border-left-color: #f6c23e !important;
    }
</style>

<?php get_footer(); ?>