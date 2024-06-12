
<?php
session_start();
if(!isset($_SESSION["user"])){
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Homes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <h1 class="text-center my-4">Available Homes</h1>

        <div class="d-flex justify-content-end mb-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>




    <form action="index.php" method="GET" class="mb-4">
    <div class="row">

            <div class="col-md-3">
                <label for="city" class="form-label">City</label>
                <select name="city" id="city" class="form-control">
                    <option value="">All</option>
                    <option value="Istanbul">Istanbul</option>
                    <option value="Ankara">Ankara</option>
                    <option value="Izmir">Izmir</option>
                    <!-- Add more cities as needed -->
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="rent">Rent</option>
                    <option value="sell">Sell</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">All</option>
                    <option value="apartment">Apartment</option>
                    <option value="floor">Floor</option>
                    <option value="villa">Villa</option>
                    <option value="shop">Shop</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <?php
    require_once 'database.php';

    $city = isset($_GET['city']) ? $_GET['city'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $sql = "SELECT * FROM homes WHERE 1=1";

    if (!empty($city)) {
        $city = mysqli_real_escape_string($conn, $city);
        $sql .= " AND location='$city'";
    }
    if (!empty($status)) {
        $status = mysqli_real_escape_string($conn, $status);
        $sql .= " AND status='$status'";
    }
    if (!empty($type)) {
        $type = mysqli_real_escape_string($conn, $type);
        $sql .= " AND type='$type'";
    }

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo '<div class="alert alert-danger">Error fetching homes. Please try again later.</div>';
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($home = mysqli_fetch_assoc($result)) {
    echo '<div class="col-md-12">';
    echo '<a href="item.php?id=' . $home['id'] . '" class="text-decoration-none text-dark">'; // Added anchor tag
    echo '<div class="card mb-4" style="height: 320px;">'; // Added style for height
    echo '<div class="row g-0">';
    echo '<div class="col-md-4">';
    if (isset($home['id'])) {
        $imageExtensions = ['jpg', 'png']; // List of allowed image extensions
        $photo_filename = $home['id'];

        $foundImage = false;
        foreach ($imageExtensions as $extension) {

            $photoUrls = explode(',', $home['photo']);
            $firstPhotoUrl = trim($photoUrls[0]); // Trim to remove any extra spaces


            
            if (file_exists($firstPhotoUrl)) {
                echo '<img src="' . htmlspecialchars($firstPhotoUrl) . '" class="card-img-top" alt="' . htmlspecialchars($home['title']) . '" style="width: 100%; height: 100%; object-fit: cover;">'; // Added object-fit
                $foundImage = true;
                break; // Stop searching if image is found
            } else {
                echo "File not found: $firstPhotoUrl<br>";
            }
        }

        if (!$foundImage) {
            echo '<img src="uploads/defaulttt.jpg" class="card-img-top" alt="Default Image" style="width: 100%; height: 100%; object-fit: cover;">'; // Added object-fit
        }
    } else {
        echo '<img src="uploads/default.jpg" class="card-img-top" alt="Default Image" style="width: 100%; height: 100%; object-fit: cover;">'; // Added object-fit
    }
    echo '</div>'; // col-md-4

    echo '<div class="col-md-8">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . htmlspecialchars($home['title']) . '</h5>';
    echo '<p class="card-text">' . htmlspecialchars($home['description']) . '</p>';
    echo '<p class="card-text"><strong>Price:</strong> ' . htmlspecialchars($home['price']) . '</p>';
    echo '<p class="card-text"><strong>Location:</strong> ' . htmlspecialchars($home['location']) . '</p>';
    echo '<p class="card-text"><strong>Status:</strong> ' . ucfirst(htmlspecialchars($home['status'])) . '</p>';
    echo '<p class="card-text"><strong>Type:</strong> ' . ucfirst(htmlspecialchars($home['type'])) . '</p>';
    echo '</div>'; // card-body
    echo '</div>'; // col-md-8
    echo '</div>'; // row
    echo '</div>'; // card
    echo '</a>'; // Added closing anchor tag
    echo '</div>'; // col-md-12
}

            
            
            
            
        } else {
            echo '<div class="col-12"><div class="alert alert-info">No homes found matching your criteria.</div></div>';
        }
    }
    ?>
    </div>
</body>
</html>
