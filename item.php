<?php
// Include the database connection file
require_once 'database.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    // Sanitize the 'id' parameter to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query to fetch the item details using the 'id'
    $sql = "SELECT * FROM homes WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $home = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($home['title']); ?> - Item Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <?php
                if (isset($home['photo']) && !empty($home['photo'])) {
                    $imageUrl =  $home['photo'];
                    if (file_exists($imageUrl)) {
                        echo '<img src="' . htmlspecialchars($imageUrl) . '" class="img-fluid" alt="' . htmlspecialchars($home['title']) . '">';
                    } else {
                        echo '<img src="uploads/defaulttt.jpg" class="img-fluid" alt="Default Image">';
                        echo '<p>Debug: File does not exist - ' . htmlspecialchars($imageUrl) . '</p>'; // Debugging line
                    }
                } else {
                    echo '<img src="uploads/defalut.jpg" class="img-fluid" alt="Default Image">';
                    echo '<p>Debug: No photo provided.</p>'; // Debugging line
                }
                ?>
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($home['title']); ?></h2>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($home['description']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($home['price']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($home['location']); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($home['status'])); ?></p>
                <p><strong>Type:</strong> <?php echo ucfirst(htmlspecialchars($home['type'])); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        // Handle case where no item with the specified id was found
        echo '<div class="alert alert-danger">Item not found.</div>';
    }
} else {
    // Handle case where the 'id' parameter is missing
    echo '<div class="alert alert-danger">Invalid request. Please provide an item id.</div>';
}
?>
