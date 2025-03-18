<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['location'])) {
    $location = urlencode($_POST['location']);
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$location}";

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "YourApp/1.0 (your@email.com)"); // Required User-Agent
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    if (!empty($data)) {
        $latitude = $data[0]['lat'];
        $longitude = $data[0]['lon'];
        
        // Return JSON response for AJAX
        echo json_encode([
            "success" => true,
            "latitude" => $latitude,
            "longitude" => $longitude
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Location not found!"]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Latitude & Longitude</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            margin: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Find Latitude & Longitude</h2>
    <form id="location-form">
        <input type="text" id="location" name="location" placeholder="Enter a place (e.g., Odisha, India)" required>
        <button type="submit">Get Coordinates</button>
    </form>

    <h3 id="result"></h3>

    <script>
        $(document).ready(function() {
            $("#location-form").submit(function(event) {
                event.preventDefault();
                
                var location = $("#location").val();
                
                $.post("get_location.php", { location: location }, function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        $("#result").html("Latitude: " + data.latitude + "<br>Longitude: " + data.longitude);
                    } else {
                        $("#result").html("<span style='color: red;'>" + data.message + "</span>");
                    }
                });
            });
        });
    </script>

</body>
</html>
