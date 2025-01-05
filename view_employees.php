<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
include 'connection.php';

// Handle Delete Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    
    // Start transaction
    $connection->begin_transaction();
    
    try {
        // First delete attendance records
        $stmt = $connection->prepare("DELETE FROM attendance WHERE employee_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Then delete leave records
        $stmt = $connection->prepare("DELETE FROM leaves WHERE employee_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Finally delete the employee
        $stmt = $connection->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // If everything is successful, commit the transaction
        $connection->commit();
        header('Location: view_employees.php?msg=deleted');
        exit();
        
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        $connection->rollback();
        $error = "Error deleting employee: " . $e->getMessage();
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Employees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Employee deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Employee List</h2>
                    <a href="new_employee.php" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Add New Employee
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-3">
                    <input type="text" id="searchEmployee" class="form-control" placeholder="Search employees...">
                </div>

                <!-- Employee Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            <?php
                            $query = "SELECT * FROM employees";
                            $result = $connection->query($query);

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                                echo "<td>
                                    <a href='new_employee.php?edit=" . $row['id'] . "' class='btn btn-sm btn-primary me-2'>
                                        <i class='bi bi-pencil'></i>
                                    </a>
                                    <button onclick='confirmDelete(" . $row['id'] . ")' class='btn btn-sm btn-danger'>
                                        <i class='bi bi-trash'></i>
                                    </button>
                                    
                                    <!-- Hidden form for delete operation -->
                                    <form id='deleteForm" . $row['id'] . "' method='POST' style='display: none;'>
                                        <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchEmployee').addEventListener('keyup', function() {
            let searchText = this.value.toLowerCase();
            let tableBody = document.getElementById('employeeTableBody');
            let rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                let name = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                row.style.display = name.includes(searchText) ? '' : 'none';
            }
        });

        // Delete confirmation
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }

        // Auto-hide alerts after 3 seconds
        window.setTimeout(function() {
            const alerts = document.getElementsByClassName('alert');
            for (let alert of alerts) {
                alert.classList.remove('show');
            }
        }, 3000);
    </script>
</body>
</html> 