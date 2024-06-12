<?php
// editHome.php
session_start();
require_once 'database.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}


// Get the home ID from the query string
$homeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($homeId == 0) {
    header('Location: adminDashboard.php');
    exit();
}

// Fetch the home details from the database
$sql = "SELECT * FROM homes WHERE id = $homeId";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Home not found.";
    exit();
}

$home = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    $sql = "UPDATE homes SET 
                title = '$title',
                description = '$description',
                price = '$price',
                location = '$location',
                status = '$status',
                type = '$type'
            WHERE id = $homeId";

    if (mysqli_query($conn, $sql)) {
        header('Location: adminDashboard.php');
        exit();
    } else {
        echo "Error updating home: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Home</h1>
        <form action="editHome.php?id=<?php echo $homeId; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($home['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($home['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="<?php echo htmlspecialchars($home['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($home['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="rent" <?php if ($home['status'] == 'rent') echo 'selected'; ?>>Rent</option>
                    <option value="sell" <?php if ($home['status'] == 'sell') echo 'selected'; ?>>Sell</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="apartment" <?php if ($home['type'] == 'apartment') echo 'selected'; ?>>Apartment</option>
                    <option value="floor" <?php if ($home['type'] == 'floor') echo 'selected'; ?>>Floor</option>
                    <option value="villa" <?php if ($home['type'] == 'villa') echo 'selected'; ?>>Villa</option>
                    <option value="shop" <?php if ($home['type'] == 'shop') echo 'selected'; ?>>Shop</option>
                    <option value="other" <?php if ($home['type'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</body>
</html>
