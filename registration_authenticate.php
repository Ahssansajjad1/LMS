<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollno = $_POST["rollno"];
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $branch = $_POST["branch"];
    $sem = $_POST["sem"];
    $dob = $_POST["dob"];
    $semail = $_POST["semail"];
    $gender = $_POST["gender"];
    $bg = $_POST["bg"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];
    $pass = $_POST["pass"];
    $cpass = $_POST["cpass"];

    // Insert into student_table
    $query1 = "INSERT INTO student_table (student_rollno, student_firstname, student_middlename, student_lastname, student_date_of_birth, student_gender, student_bloodgroup, student_branch, student_semester, student_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $query1)) {
        mysqli_stmt_bind_param($stmt, "ssssssssss", $rollno, $fname, $mname, $lname, $dob, $gender, $bg, $branch, $sem, $address);

        if (mysqli_stmt_execute($stmt)) {
            // Insert into student_login table
            $query2 = "INSERT INTO student_login (student_rollno, student_password, student_emailid, student_contact) VALUES (?, ?, ?, ?)";

            if ($stmt2 = mysqli_prepare($conn, $query2)) {
                // Security Requirement: Use a stronger password hashing method instead of md5
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt2, "ssss", $rollno, $hashed_password, $semail, $contact);

                if (mysqli_stmt_execute($stmt2)) {
                    header("location: index.php");
                    exit();
                } else {
                    echo "Error inserting into student_login.";
                }

                mysqli_stmt_close($stmt2);
            }
        } else {
            echo "Error inserting into student_table.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>
