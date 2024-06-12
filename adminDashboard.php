<?php
// admin_dashboard.php
session_start();
require_once 'database.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Fetch all homes from the database
$sql = "SELECT * FROM homes";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <a href="addHome.php" class="btn btn-primary mb-3">Add Home</a>
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($home = mysqli_fetch_assoc($result)) {
                // Extract the first photo
                $photoUrls = explode(',', $home['photo']);
                $firstPhotoUrl = trim($photoUrls[0]);
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<img src="' . htmlspecialchars($firstPhotoUrl) . '" class="img-fluid" alt="Home Image">';

                echo '<h5 class="card-title">' . htmlspecialchars($home['title']) . '</h5>';
                echo '<p class="card-text">' . htmlspecialchars($home['description']) . '</p>';
                echo '<p class="card-text"><strong>Price:</strong> ' . htmlspecialchars($home['price']) . '</p>';
                echo '<p class="card-text"><strong>Location:</strong> ' . htmlspecialchars($home['location']) . '</p>';
                echo '<p class="card-text"><strong>Status:</strong> ' . ucfirst(htmlspecialchars($home['status'])) . '</p>';
                echo '<p class="card-text"><strong>Type:</strong> ' . ucfirst(htmlspecialchars($home['type'])) . '</p>';
                echo '<a href="editHome.php?id=' . $home['id'] . '" class="btn btn-primary">Edit</a>';
                echo '<form action="deleteHome.php" method="POST" style="display:inline-block;">';
                echo '<input type="hidden" name="id" value="' . $home['id'] . '">';
                echo '<button type="submit" class="btn btn-danger">Delete</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-info">No homes found.</div>';
        }
        ?>
    </div>
</body>
</html>
