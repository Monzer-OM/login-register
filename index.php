<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit;
}
require_once 'database.php';

$city = isset($_GET['city']) ? $_GET['city'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

function getFilteredHomes($conn, $city, $status, $type) {
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

    return mysqli_query($conn, $sql);
}

$result = getFilteredHomes($conn, $city, $status, $type);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Homes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-img-top {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control {
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
        }
        nav {
            background-color: var(--color4);
            padding: 10px 0;
        }
        .nav_container {
            display: flex;
            width: 60%;
            margin: auto;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
        }
        .nav_container img {
            width: 20%;
        }
        .nav_buttons {
            display: flex;
            gap: 20px;
        }
        .buttons {
            background-color: var(--color3);
            color: white;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 1rem 1.5rem;
            font-size: 1.5rem;
            border-radius: 40px;
            transition: all .2s ease-in-out;
        }
        .buttons:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav_container">
            <img src="img/icons/logo1.png" alt="logo">
        </div>
        <div class="divider" style="border-bottom: 3px solid #D72323; margin-bottom: px;"></div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Homes</h1>
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
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <div class="d-flex justify-content-end mb-4">
                    <a href="logout.php" class="btn btn-danger w-100" style="padding-bottom: 10px; margin-bottom: 10px">Logout</a>
                    </div>
                </div>
                

            </div>
        </form>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            if (!$result) {
                echo '<div class="alert alert-danger">Error fetching homes. Please try again later.</div>';
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($home = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col">
                            <div class="card">
                                <?php
                                if (isset($home['photo']) && !empty($home['photo'])) {
                                    $photoUrls = explode(',', $home['photo']);
                                    $firstPhotoUrl = trim($photoUrls[0]);

                                    if (!empty($firstPhotoUrl) && file_exists($firstPhotoUrl)) {
                                        echo '<img src="' . htmlspecialchars($firstPhotoUrl) . '" class="card-img-top" alt="' . htmlspecialchars($home['title']) . '">';
                                    } else {
                                        echo '<img src="uploads/default.jpg" class="card-img-top" alt="Default Image">';
                                    }
                                } else {
                                    echo '<img src="uploads/default.jpg" class="card-img-top" alt="Default Image">';
                                }
                                ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($home['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($home['description']); ?></p>
                                    <p class="card-text"><strong>Price:</strong> <?php echo htmlspecialchars($home['price']); ?></p>
                                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($home['location']); ?></p>
                                    <p class="card-text"><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($home['status'])); ?></p>
                                    <p class="card-text"><strong>Type:</strong> <?php echo ucfirst(htmlspecialchars($home['type'])); ?></p>
                                    <a href="item.php?id=<?php echo htmlspecialchars($home['id']); ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col"><div class="alert alert-info">No homes found matching your criteria.</div></div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>