<?php

$connection = new mysqli("localhost", "root", "", "hrm_db");


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// function executeQuery($connection, $query, $successMessage)
// {
//     if ($connection->query($query) === TRUE) {
//         echo $successMessage . "<br>";
//     } else {
//         echo "Error: " . $connection->error . "<br>";
//     }
// }


// executeQuery(
//     $connection,
//     "CREATE TABLE IF NOT EXISTS users (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         username VARCHAR(50) NOT NULL,
//         password VARCHAR(255) NOT NULL,
//         usertype VARCHAR(50) NOT NULL
//     )",
//     "Table 'users' created successfully."
// );


// executeQuery(
//     $connection,
//     "CREATE TABLE IF NOT EXISTS employees (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         name VARCHAR(100),
//         email VARCHAR(100),
//         department VARCHAR(50),
//         position VARCHAR(50)
//     )",
//     "Table 'employees' created successfully."
// );


// executeQuery(
//     $connection,
//     "CREATE TABLE IF NOT EXISTS attendance (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         employee_id INT,
//         date DATE,
//         status ENUM('Present', 'Absent') NOT NULL,
//         FOREIGN KEY (employee_id) REFERENCES employees(id)
//     )",
//     "Table 'attendance' created successfully."
// );


// executeQuery(
//     $connection,
//     "CREATE TABLE IF NOT EXISTS leaves (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         employee_id INT,
//         reason TEXT,
//         status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
//         FOREIGN KEY (employee_id) REFERENCES employees(id)
//     )",
//     "Table 'leaves' created successfully."
// );


// function insertData($connection, $query, $successMessage)
// {
//     if ($connection->query($query) === TRUE) {
//         echo $successMessage . "<br>";
//     } else {
//         echo "Error inserting data: " . $connection->error . "<br>";
//     }
// }


// $username = "admin";
// $password = "12366";
// $usertype = "admin";
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// insertData(
//     $connection,
//     "INSERT INTO users (username, password, usertype) VALUES ('$username', '$hashed_password', '$usertype')",
//     "Admin user created successfully."
// );


// $username = "employee";
// $password = "12366";
// $usertype = "employee";
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// insertData(
//     $connection,
//     "INSERT INTO users (username, password, usertype) VALUES ('$username', '$hashed_password', '$usertype')",
//     "Employee user created successfully."
// );


// insertData(
//     $connection,
//     "INSERT INTO employees (name, email, department, position) VALUES 
//         ('John Doe', 'john.doe@example.com', 'IT', 'Developer')",
//     "Employee added successfully."
// );


// insertData(
//     $connection,
//     "INSERT INTO attendance (employee_id, date, status) VALUES 
//         (1, CURDATE(), 'Present')",
//     "Attendance record added successfully."
// );


// insertData(
//     $connection,
//     "INSERT INTO leaves (employee_id, reason, status) VALUES 
//         (1, 'Medical leave', 'Pending')",
//     "Leave record added successfully."
// );


function loginUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password, usertype FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPassword, $usertype);
    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['usertype'] = $usertype;
        if ($usertype === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: employee_dashboard.php");
        }
        exit();
    } else {
        // Handle login failure
    }
    $stmt->close();
}
