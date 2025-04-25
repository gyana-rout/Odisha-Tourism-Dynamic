<?php
include '../db_connect.php';

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

$sql = "SELECT * FROM tourist_places WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$search = "%".$keyword."%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) { ?>
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
<?php }

$stmt->close();
$conn->close();
?>
