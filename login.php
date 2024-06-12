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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h1 class="text-center my-4">Login</h1>

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
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div><p>Not registered yet? <a href="registration.php">Register Here</a></p></div>
    </div>
</body>
</html>
