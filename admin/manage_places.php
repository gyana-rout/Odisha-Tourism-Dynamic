<?php
include '../db_connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Add a new place
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_place'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // File Upload Directory
    $target_dir_photo = "../images/";
    $target_dir_video = "../videos/";

    // Handle Media Upload (Video or Image)
    $media_file = $target_dir_video . basename($_FILES["media"]["name"]);
    move_uploaded_file($_FILES["media"]["tmp_name"], $media_file);
    $media = basename($_FILES["media"]["name"]); 

    // Handle Photo Upload
    $photo_file = $target_dir_photo . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_file);
    $photo = basename($_FILES["photo"]["name"]);

    // Insert into Database
    $sql = "INSERT INTO tourist_places (name, description, media, photo, latitude, longitude) 
            VALUES ('$name', '$description', '$media', '$photo', '$latitude', '$longitude')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New place added successfully!');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all places
$result = $conn->query("SELECT * FROM tourist_places");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tourist Places</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .delete-btn, .add-btn {
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .add-btn {
            background: #28a745;
        }
        .add-btn:hover {
            background: #218838;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form textarea, form button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        form button {
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        Manage Tourist Places
        <a href="admin_dashboard.php" class="add-btn">Back to Dashboard</a>
    </header>
    
    <div class="container">
        <h2>Add New Place</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" id="place-name" name="name" placeholder="Enter Place Name" required>
            <button type="button" id="fetch-location" class="add-btn">Get Coordinates</button>
            <textarea name="description" placeholder="Description" required></textarea>
            <label>Video</label><input type="file" name="media" required>
            <label>Photo</label><input type="file" name="photo" required>
            <input type="text" id="latitude" name="latitude" placeholder="Latitude" required readonly>
            <input type="text" id="longitude" name="longitude" placeholder="Longitude" required readonly>
            <button type="submit" name="add_place" class="add-btn">Add Place</button>
        </form>
    </div>
    
    <div class="container">
        <h2>Existing Places</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Media</th>
                <th>Photo</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <?php 
                        $file_extension = pathinfo($row['media'], PATHINFO_EXTENSION);
                        $video_extensions = ['mp4', 'avi', 'mov', 'wmv']; 

                        if (in_array($file_extension, $video_extensions)) { ?>
                            <video width="100" controls>
                                <source src="../videos/<?php echo $row['media']; ?>" type="video/<?php echo $file_extension; ?>">
                            </video>
                        <?php } else { ?>
                            <img src="../images/<?php echo $row['media']; ?>" width="100">
                        <?php } ?>
                    </td>
                    <td>
                        <img src="../images/<?php echo $row['photo']; ?>" width="100">
                    </td>
                    <td><?php echo $row['latitude']; ?></td>
                    <td><?php echo $row['longitude']; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        $(document).ready(function(){
            $("#fetch-location").click(function(){
                var location = $("#place-name").val();
                if (location !== "") {
                    $.post("get_location.php", { location: location }, function(data) {
                        var result = JSON.parse(data);
                        if (result.success) {
                            $("#latitude").val(result.latitude);
                            $("#longitude").val(result.longitude);
                        } else {
                            alert("Location not found!");
                        }
                    });
                } else {
                    alert("Please enter a place name.");
                }
            });
        });
    </script>
</body>
</html>
