<?php
require_once('connect.php');
session_start();
if (isset($_SESSION['key']) == 1) {
    header('Location:home.php');
}
$_SESSION['var'] = 0;

$roll = mysqli_real_escape_string($conn, $_POST['rollno']);
$pass = mysqli_real_escape_string($conn, $_POST['pass']);

// Retrieve the hashed password from the database based on the provided roll number
$sql = "SELECT student_password FROM student_login WHERE student_rollno = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $roll);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_pass = $row['student_password'];

    // Verify the password
    if (password_verify($pass, $hashed_pass)) {
        $_SESSION['var'] = 0;
        $_SESSION['key'] = $roll;
        header('Location: home.php');
        exit(); // Don't forget to exit after a successful redirect
    } else {
        $_SESSION['var'] = 1;
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['var'] = 1;
    header('Location: index.php');
    exit();
}

mysqli_stmt_close($stmt);
$conn->close();
?>