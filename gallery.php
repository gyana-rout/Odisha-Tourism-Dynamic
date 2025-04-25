<?php
session_start(); // <-- Add this first line
include 'db_connect.php';

// Check login status properly
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Please login to continue");
    exit();
}

// Fetch tourist places
$sql = "SELECT * FROM tourist_places";
$result = $conn->query($sql);
?>
<style>
        h1 {
            color: #ff5722;
            text-align: center;
            font-size: 2.5rem;
        }
        nav {
            background: #ff5722;
            padding: 15px;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Odisha Tourism System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="gallery.php">Tourist Places</a></li>
                <li><a href="guide_booking.php">Book a Guide</a></li>
                <li><a href="my_booking.php">My Bookings</a></li>
                <li><a href="near_places.php">Find Near Places</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
                <li><a href="admin_login.php" style="color: red; font-weight: bold;">Admin Login</a></li>
            </ul>
        </nav>
    </header>
<section id="gallery">
    <h2>Explore Tourist Places</h2>
    <div class="gallery-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="place-card">
                <?php if (pathinfo($row['media'], PATHINFO_EXTENSION) === 'mp4') { ?>
                    <video controls>
                        <source src="videos/<?php echo htmlspecialchars($row['media']); ?>" type="video/mp4">
                    </video>
                <?php } else { ?>
                    <img src="images/<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <?php } ?>
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <button class="book-btn" onclick="bookTour(<?php echo $row['id']; ?>)">Book Now</button>
            </div>
        <?php } ?>
    </div>
</section>

<style>
    #gallery {
        text-align: center;
        padding: 20px;
        background: #f9f9f9;
    }
    .gallery-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    .place-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 300px;
        padding: 15px;
        text-align: center;
    }
    .place-card img, .place-card video {
        width: 100%;
        border-radius: 10px;
    }
    .place-card h3 {
        margin: 10px 0;
        color: #007bff;
    }
    .place-card p {
        font-size: 14px;
        color: #555;
    }
    .book-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }
    .book-btn:hover {
        background: #218838;
    }
</style>

<script>
    // Function to handle play and pause for videos
    function bookTour(placeId) {
        // JavaScript just redirects; user already validated on PHP side
        window.location.href = "booking.php?place_id=" + placeId;
    }

    // Wait for the DOM to load before adding event listeners
    window.onload = function() {
        let videos = document.querySelectorAll('video'); // Select all videos on the page
        
        // Loop through each video and add event listeners
        videos.forEach(function(video) {
            video.addEventListener('play', function() {
                // When a video is played, pause all other videos
                videos.forEach(function(otherVideo) {
                    if (otherVideo !== video) {
                        otherVideo.pause();
                    }
                });
            });
        });
    };
</script>

