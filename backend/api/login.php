<?php
session_start();
include("../config/db.php");

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            if($user['role'] === 'admin'){
                header("Location: ../../admin/dashboard.php");
            }
            elseif($user['role'] === 'vendor'){
                header("Location: ../../vendor/dashboard.php");
            }
            else{
                header("Location: ../../index.php");
            }

            exit();

        } else {
            echo "Wrong Password";
        }

    } else {
        echo "User Not Found";
    }
}
?>