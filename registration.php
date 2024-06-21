<?php
session_start();
if (isset($_SESSION["user"])) {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Real Estate Site</title>
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
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
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
        .alert-danger, .alert-success {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="register-container">
            <h1 class="text-center my-4">Signup</h1>

            <?php
if (isset($_POST['submit'])) {
    $fullName = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat_password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();

    if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }

    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Email already exists!");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You are registered successfully.</div>";
            header("location: index.php");
        } else {
            die("Something went wrong");
        }
    }
}
?>

            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name:" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email:" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password:" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" required>
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
            <div class="text-center mt-3">
                <p>Have an account? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
