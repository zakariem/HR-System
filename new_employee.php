<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include 'connection.php';

$employee = [
    'id' => '',
    'name' => '',
    'email' => '',
    'department' => '',
    'position' => ''
];
$isEdit = false;

// Check if we're editing an existing employee
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $isEdit = true;
    $id = intval($_GET['edit']);
    
    $stmt = $connection->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $employee = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);
    
    if ($isEdit) {
        // Update existing employee
        $stmt = $connection->prepare("UPDATE employees SET name = ?, email = ?, department = ?, position = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $department, $position, $employee['id']);
    } else {
        // Create new employee
        $stmt = $connection->prepare("INSERT INTO employees (name, email, department, position) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $department, $position);
    }
    
    if ($stmt->execute()) {
        header('Location: view_employees.php');
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $isEdit ? 'Edit Employee' : 'New Employee'; ?></title>
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
            <!-- Include the same sidebar as dashboard.php -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="card">
                    <div class="card-header">
                        <h4><?php echo $isEdit ? 'Edit Employee' : 'Add New Employee'; ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="IT" <?php echo $employee['department'] === 'IT' ? 'selected' : ''; ?>>IT</option>
                                    <option value="HR" <?php echo $employee['department'] === 'HR' ? 'selected' : ''; ?>>HR</option>
                                    <option value="Finance" <?php echo $employee['department'] === 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                    <option value="Marketing" <?php echo $employee['department'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                    <option value="Operations" <?php echo $employee['department'] === 'Operations' ? 'selected' : ''; ?>>Operations</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position" 
                                    value="<?php echo htmlspecialchars($employee['position']); ?>" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $isEdit ? 'Update Employee' : 'Add Employee'; ?>
                                </button>
                                <a href="view_employees.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 