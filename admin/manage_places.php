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
// Delete a place if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch media and photo filenames to delete them from the server
    $query = "SELECT media, photo FROM tourist_places WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($media, $photo);
    $stmt->fetch();
    $stmt->close();

    // Delete files from server
    if (!empty($media) && file_exists("../videos/" . $media)) {
        unlink("../videos/" . $media);
    }
    if (!empty($photo) && file_exists("../images/" . $photo)) {
        unlink("../images/" . $photo);
    }

    // Delete from database
    $delete_query = "DELETE FROM tourist_places WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Place deleted successfully!'); window.location.href='manage_places.php';</script>";
    } else {
        echo "<script>alert('Error deleting place!');</script>";
    }
    
    $stmt->close();
}
// Check if edit_id is set to load existing data
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM tourist_places WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_data = $result_edit->fetch_assoc();
    $stmt->close();
}

// Update the place details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_place'])) {
    $update_id = $_POST['update_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Check if new media or photo is uploaded
    $media = $_POST['existing_media'];
    if (!empty($_FILES["media"]["name"])) {
        $target_media = "../videos/" . basename($_FILES["media"]["name"]);
        move_uploaded_file($_FILES["media"]["tmp_name"], $target_media);
        $media = basename($_FILES["media"]["name"]);
    }

    $photo = $_POST['existing_photo'];
    if (!empty($_FILES["photo"]["name"])) {
        $target_photo = "../images/" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_photo);
        $photo = basename($_FILES["photo"]["name"]);
    }

    // Update the database
    $update_query = "UPDATE tourist_places SET name=?, description=?, media=?, photo=?, latitude=?, longitude=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssddi", $name, $description, $media, $photo, $latitude, $longitude, $update_id);

    if ($stmt->execute()) {
        echo "<script>alert('Place updated successfully!'); window.location.href='manage_places.php';</script>";
    } else {
        echo "<script>alert('Error updating place!');</script>";
    }

    $stmt->close();
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
        .edit-btn {
    background: #ffc107;
    color: black;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 16px;
}
.edit-btn:hover {
    background: #e0a800;
}

    </style>
</head>
<body>
    <header>
        Manage Tourist Places
        <a href="admin_dashboard.php" class="add-btn">Back to Dashboard</a>
    </header>
    
    <div class="container">
    <h2><?php echo isset($edit_data) ? "Edit Place" : "Add New Place"; ?></h2>
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_id" value="<?php echo isset($edit_data) ? $edit_data['id'] : ''; ?>">
    
    <input type="text" name="name" placeholder="Enter Place Name" required 
           value="<?php echo isset($edit_data) ? $edit_data['name'] : ''; ?>">
    
    <button type="button" id="fetch-location" class="add-btn">Get Coordinates</button>
    
    <textarea name="description" placeholder="Description" required><?php echo isset($edit_data) ? $edit_data['description'] : ''; ?></textarea>
    
    <label>Video</label>
    <input type="file" name="media">
    <input type="hidden" name="existing_media" value="<?php echo isset($edit_data) ? $edit_data['media'] : ''; ?>">
    <?php if (isset($edit_data['media'])) { echo "<p>Current: " . $edit_data['media'] . "</p>"; } ?>
    
    <label>Photo</label>
    <input type="file" name="photo">
    <input type="hidden" name="existing_photo" value="<?php echo isset($edit_data) ? $edit_data['photo'] : ''; ?>">
    <?php if (isset($edit_data['photo'])) { echo "<p>Current: " . $edit_data['photo'] . "</p>"; } ?>
    
    <input type="text" id="latitude" name="latitude" placeholder="Latitude" required readonly 
           value="<?php echo isset($edit_data) ? $edit_data['latitude'] : ''; ?>">
    
    <input type="text" id="longitude" name="longitude" placeholder="Longitude" required readonly 
           value="<?php echo isset($edit_data) ? $edit_data['longitude'] : ''; ?>">
    
    <button type="submit" name="<?php echo isset($edit_data) ? "update_place" : "add_place"; ?>" 
            class="add-btn">
        <?php echo isset($edit_data) ? "Update Place" : "Add Place"; ?>
    </button>
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
                        <a href="?edit_id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>

                </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        $(document).ready(function(){
    $("#fetch-location").click(function(){
        var location = $("input[name='name']").val();
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
