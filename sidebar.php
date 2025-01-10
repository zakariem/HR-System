<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usertype = $_SESSION['usertype'];
?>

<!-- Sidebar -->
<div class="col-md-3 col-lg-2 sidebar">
    <h4 class="text-white text-center mb-4">HRM System</h4>
    <nav class="nav flex-column">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <?php if ($usertype === 'admin'): ?>
        <div class="nav-item dropend">
            <a class="nav-link dropdown-toggle" href="#" id="employeeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-people"></i> Employees
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="employeeDropdown">
                <li><a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'new_employee.php' ? 'active' : ''; ?>" href="new_employee.php">
                    <i class="bi bi-person-plus"></i> New Employee
                </a></li>
                <li><a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'view_employees.php' ? 'active' : ''; ?>" href="view_employees.php">
                    <i class="bi bi-table"></i> View All Employees
                </a></li>
            </ul>
        </div>
        <?php endif; ?>
        
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : ''; ?>" href="attendance.php">
            <i class="bi bi-calendar-check"></i> Attendance
        </a>
        
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'leaves.php' ? 'active' : ''; ?>" href="leaves.php">
            <i class="bi bi-calendar-x"></i> Leaves
        </a>
        
        <a class="nav-link text-danger" href="?logout=1">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</div>

<style>
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1rem;
        margin: 0.2rem 0;
    }
    
    .sidebar .nav-link:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    
    .sidebar .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }
    
    .sidebar .dropdown-menu {
        margin-top: 0;
        margin-left: 0.5rem;
    }
    
    .sidebar .dropdown-item {
        padding: 0.5rem 1rem;
    }
    
    .sidebar .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .dropdown-item.active {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    .sidebar i {
        margin-right: 0.5rem;
    }

    /* Position dropdown to the right */
    .dropend .dropdown-menu {
        top: 0;
        left: 100%;
        margin-left: 0.125rem;
    }
</style> 