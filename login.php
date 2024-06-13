<?php
session_start();
if(isset($_SESSION["user"])){
    header("location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Real Estate Site</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(to bottom right, green, blue);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            margin-bottom: 1rem;
        }
        .form-btn {
            display: flex;
            justify-content: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .alert-danger {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="login-container">
            <h1 class="text-center mb-4">Real Estate Site Login</h1>

            <?php
if (isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    require_once 'database.php';
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($user) {
        // Check if the credentials are for the admin
        if ($email === 'Admin@admin.com' && $password === 'admin123') {
            session_start();
            $_SESSION["user"] = $user["fullname"];
            $_SESSION['admin_logged_in'] = true;

            header("location: adminDashboard.php");
            die();
        } 
        // Check if the password matches the hashed password in the database
        elseif (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user"] = $user["fullname"];
            header("location: index.php");
            die();
        } 
        // If password doesn't match
        else {
            echo "<div class='alert alert-danger'>The information didn't match!</div>";
        }
    } 
    // If no user found with the provided email
    else {
        echo "<div class='alert alert-danger'>The information didn't match!</div>";
    }
}
?>

            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter your email address" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter your password" name="password" class="form-control" required>
                </div>
                <div class="form-btn">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>
            </form>
            <div class="text-center mt-3">
                <p>Not registered yet? <a href="registration.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>