<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>

<body>
<div class="container">
    <h1 class="text-center my-4">Add Home</h1>

    <?php
require_once 'database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $type = $_POST['type'];

    // Handle file uploads
    $target_dir = "uploads/";
    $uploaded_files = array();

    foreach ($_FILES['photo']['name'] as $key => $name) {
        $target_file = $target_dir . basename($name);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if the file is a JPG or PNG image
        if ($imageFileType != "jpg" && $imageFileType != "png") {
            echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, and PNG files are allowed for photo $name.</div>";
            continue; // Skip this file and move to the next iteration
        }
        if (move_uploaded_file($_FILES['photo']['tmp_name'][$key], $target_file)) {
            $uploaded_files[] = $target_file;
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file: $name.</div>";
        }
    }

    // Convert array of file paths to a comma-separated string
    $photo = implode(',', $uploaded_files);

    // Insert data into the database
    $sql = "INSERT INTO homes (title, price, photo, description, location, status, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssss", $title, $price, $photo, $description, $location, $status, $type);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>Home added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Something went wrong. Please try again later.</div>";
    }
}
?>



    <form action="addHome.php" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="title">Title:</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>
        <div class="form-group mb-3">
            <label for="price">Price:</label>
            <input type="number" class="form-control" name="price" id="price" required>
        </div>
        <div class="form-group mb-3">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" name="photo[]" id="photo" multiple required>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="location">Location:</label>
            <select class="form-control" name="location" id="location" required>
                <option value="" disabled selected>Select City</option>
                <option value="Adana">Adana</option>
                <option value="Adıyaman">Adıyaman</option>
                <option value="Afyonkarahisar">Afyonkarahisar</option>
                <option value="Ağrı">Ağrı</option>
                <option value="Amasya">Amasya</option>
                <option value="Ankara">Ankara</option>
                <option value="Antalya">Antalya</option>
                <option value="Artvin">Artvin</option>
                <option value="Aydın">Aydın</option>
                <option value="Balıkesir">Balıkesir</option>
                <option value="Bilecik">Bilecik</option>
                <option value="Bingöl">Bingöl</option>
                <option value="Bitlis">Bitlis</option>
                <option value="Bolu">Bolu</option>
                <option value="Burdur">Burdur</option>
                <option value="Bursa">Bursa</option>
                <option value="Çanakkale">Çanakkale</option>
                <option value="Çankırı">Çankırı</option>
                <option value="Çorum">Çorum</option>
                <option value="Denizli">Denizli</option>
                <option value="Diyarbakır">Diyarbakır</option>
                <option value="Edirne">Edirne</option>
                <option value="Elazığ">Elazığ</option>
                <option value="Erzincan">Erzincan</option>
                <option value="Erzurum">Erzurum</option>
                <option value="Eskişehir">Eskişehir</option>
                <option value="Gaziantep">Gaziantep</option>
                <option value="Giresun">Giresun</option>
                <option value="Gümüşhane">Gümüşhane</option>
                <option value="Hakkari">Hakkari</option>
                <option value="Hatay">Hatay</option>
                <option value="Isparta">Isparta</option>
                <option value="Mersin">Mersin</option>
                <option value="İstanbul">İstanbul</option>
                <option value="İzmir">İzmir</option>
                <option value="Kars">Kars</option>
                <option value="Kastamonu">Kastamonu</option>
                <option value="Kayseri">Kayseri</option>
                <option value="Kırklareli">Kırklareli</option>
                <option value="Kırşehir">Kırşehir</option>
                <option value="Kocaeli">Kocaeli</option>
                <option value="Konya">Konya</option>
                <option value="Kütahya">Kütahya</option>
                <option value="Malatya">Malatya</option>
                <option value="Manisa">Manisa</option>
                <option value="Kahramanmaraş">Kahramanmaraş</option>
                <option value="Mardin">Mardin</option>
                <option value="Muğla">Muğla</option>
                <option value="Muş">Muş</option>
                <option value="Nevşehir">Nevşehir</option>
                <option value="Niğde">Niğde</option>
                <option value="Ordu">Ordu</option>
                <option value="Rize">Rize</option>
                <option value="Sakarya">Sakarya</option>
                <option value="Samsun">Samsun</option>
                <option value="Siirt">Siirt</option>
                <option value="Sinop">Sinop</option>
                <option value="Sivas">Sivas</option>
                <option value="Tekirdağ">Tekirdağ</option>
                <option value="Tokat">Tokat</option>
                <option value="Trabzon">Trabzon</option>
                <option value="Tunceli">Tunceli</option>
                <option value="Şanlıurfa">Şanlıurfa</option>
                <option value="Uşak">Uşak</option>
                <option value="Van">Van</option>
                <option value="Yozgat">Yozgat</option>
                <option value="Zonguldak">Zonguldak</option>
                <option value="Aksaray">Aksaray</option>
                <option value="Bayburt">Bayburt</option>
                <option value="Karaman">Karaman</option>
                <option value="Kırıkkale">Kırıkkale</option>
                <option value="Batman">Batman</option>
                <option value="Şırnak">Şırnak</option>
                <option value="Bartın">Bartın</option>
                <option value="Ardahan">Ardahan</option>
                <option value="Iğdır">Iğdır</option>
                <option value="Yalova">Yalova</option>
                <option value="Karabük">Karabük</option>
                <option value="Kilis">Kilis</option>
                <option value="Osmaniye">Osmaniye</option>
                <option value="Düzce">Düzce</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="status">Status:</label>
            <select class="form-control" name="status" id="status" required>
                <option value="rent">Rent</option>
                <option value="sell">Sell</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="type">Type of Home:</label>
            <select class="form-control" name="type" id="type" required>
                <option value="apartment">Apartment</option>
                <option value="floor">Floor</option>
                <option value="villa">Villa</option>
                <option value="shop">Shop</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="form-btn">
            <input type="submit" class="btn btn-primary" value="Add">
        </div>
    </form>
</div>
</body>
</html>
