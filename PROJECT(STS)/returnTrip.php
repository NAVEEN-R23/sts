<?php
session_start(); // Start the session

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "sts";

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("<script>alert('Failed to connect to DB: " . $con->connect_error . "');</script>");
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['email']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $pickup = mysqli_real_escape_string($con, $_POST['pickup']);
    $drop = mysqli_real_escape_string($con, $_POST['drop']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $vehicleType = mysqli_real_escape_string($con, $_POST['vehicle_type']);
    $vehicleNumber = mysqli_real_escape_string($con, $_POST['vehicle_number']);
    $availability = mysqli_real_escape_string($con, $_POST['availability']);
    $availabilityUnit = mysqli_real_escape_string($con, $_POST['availability_unit']);
    $ratePerKm = mysqli_real_escape_string($con, $_POST['rate_per_km']);
    $distance = mysqli_real_escape_string($con, $_POST['distance']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']);
    $additionalInfo = mysqli_real_escape_string($con, $_POST['additional_info']);

    // Insert query to store data in the database
    $insertQuery = "INSERT INTO return_trips (
                        name, phone, pickup_location, drop_location, trip_date,
                        vehicle_type, vehicle_number, availability, availability_unit,
                        rate_per_km, distance, cost, additional_info
                    ) VALUES (
                        '$name', '$phone', '$pickup', '$drop', '$date',
                        '$vehicleType', '$vehicleNumber', '$availability', '$availabilityUnit',
                        '$ratePerKm', '$distance', '$cost', '$additionalInfo'
                    )";

    // Execute the query
    if (mysqli_query($con, $insertQuery)) {
        echo "<script>alert('Trip details posted successfully!'); window.location.href='home1.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Close connection
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Return Trip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSybDT75L6wDBted7VoQRQyBdpB1IdMHFqvV&libraries=places&callback=initAutocomplete" async defer></script>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            padding-top: 100px; /* Adjust based on header height */
            min-height: 100vh;
        }
        /* Header Styling */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 100px;
            background-color: #E0E0E0;
            color: black;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .logo {
            height: 80px;
        }
        .grow {
            flex-grow: 1;
        }
        ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }
        li > a {
            color: black;
            text-decoration: none;
            font-size: 25px;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        li > a.active {
            color: #ff9900;
        }
        li > a:hover {
            color: #8d8a8a;
        }
        /* Form Container Styles */
        .container {
            background: #E0E0E0;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            text-align: left;
            width: 720px;
            position: relative;
            margin: 50px auto;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .container h3 {
            font-size: 28px;
            font-weight: bold;
            color: #222;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        h4 {
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        h4 i {
            color: #ff9900;
        }
        .container p {
            font-size: 16px;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.6;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            border-left: 5px solid #ff9900;
            border-right: 5px solid #ff9900;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #555;
        }
        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            outline: none;
        }
        input:hover,
        select:hover,
        textarea:hover {
            border-color: #000;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff9900; /* Gradient background */
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            transition: transform 0.2s ease, background 0.3s ease;
        }
        button:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #e6b800, #ffcc00);
        }
        #distance_display {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            animation: slideIn 0.5s ease-in-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: -5px;
            display: none;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            50% {
                transform: translateX(5px);
            }
            75% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(0);
            }
        }
        /*footer (the end)*/
footer {
    background: #1a1a1a;
    color: white;
    padding: 50px 0;
    font-family: Arial, sans-serif;
}
.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    gap: 40px;
    text-align: left;
    align-items: flex-start;
    /* Ensures all sections start from the top */
}
.footer-logo,
.footer-links,
.footer-contact,
.footer-social {
    flex: 1;
    min-width: 220px;
    max-width: 250px;
}
.footer-logo h2,
.footer-links h3,
.footer-contact h3,
.footer-social h3 {
    color: #ff9900;
    margin-bottom: 10px;
}
.footer-links ul {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    flex-direction: column;
    padding: 0;
    justify-content: center;
    max-width: 600px;
}
.footer-links ul li {
    display: inline-block;
    width: auto;
}
.footer-links ul li a {
    text-decoration: none;
    color: white;
    font-size: 16px;
}
.footer-links ul li a:hover {
    color: #ff9900;
}
.footer-contact p {
    margin-bottom: 8px;
}
.footer-social p {
    font-size: 14px;
    margin-bottom: 10px;
}
.footer-social .social-icons {
    display: flex;
    justify-content: flex-start;
    gap: 15px;
}
.footer-social .social-icons a {
    color: white;
    font-size: 22px;
    transition: 0.3s;
}
.footer-social .social-icons a:hover {
    color: #ff9900;
}
.footer-bottom {
    text-align: center;
    margin-top: 30px;
    border-top: 1px solid #444;
    padding-top: 30px;
}
    </style>
</head>
<body>
    <!-- Header (Navigation Bar) -->
    <header>
        <!-- Ensure the logo path is correct -->
        <img src="properties/logo.png" alt="logo of sts" class="logo">
        <h1 class="grow tracking-in-expand">SMART TRIP SHARE</h1>
        <nav>
            <ul>
                <li><a href="home1.php">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="returnTrip.php" class="active">Post Return Trip</a></li>
                <li><a href="loadRequirement.php">Post Load Requirement</a></li>
                <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </nav>
    </header>
    <script>
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
    <!-- Main Content -->
    <div class="container">
        <h3>Post Your Return Trip Details</h3>
        <p>
            Welcome to Smart Trip Share! Fill out the form below to post your return trip details. 
            Provide accurate information about your trip so that others can connect with you easily.
        </p>
        <!-- Post Trip Form -->
        <form action="" method="POST" id="tripForm">
            <!-- Personal Details Section -->
            <h4><i class="fas fa-user"></i> Personal Details</h4>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            <span class="error" id="nameError">Please enter your name.</span>
            <label for="phone">Phone Number:</label>
            <input 
                type="tel" 
                id="phone" 
                name="phone" 
                placeholder="Enter your 10-digit phone number" 
                pattern="[0-9]{10}" 
                inputmode="numeric" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                maxlength="10" 
                required
            >
            <span class="error" id="phoneError">Please enter a valid 10-digit phone number.</span>
            <!-- Trip Details Section -->
            <h4><i class="fas fa-map-marker-alt"></i> Trip Details</h4>
            <label for="pickup">Returning From:</label>
            <input type="text" id="pickup" name="pickup" placeholder="Enter pickup location" required>
            <label for="drop">Returning To:</label>
            <input type="text" id="drop" name="drop" placeholder="Enter drop location" required>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" placeholder="Select trip date" required>
            <span class="error" id="dateError">Please select a valid date.</span>
            <!-- Vehicle Details Section -->
            <h4><i class="fas fa-truck"></i> Vehicle Details</h4>
            <label for="vehicle_type">Vehicle Type:</label>
            <select id="vehicle_type" name="vehicle_type" required>
                <option value="" disabled selected>Select vehicle type</option>
                <option value="Truck">Truck</option>
                <option value="Mini Truck">Mini Truck</option>
                <option value="Van">Van</option>
            </select>
            <span class="error" id="vehicleTypeError">Please select a vehicle type.</span>
            <label for="vehicle_number">Vehicle Registration Number:</label>
            <input 
                type="text" 
                id="vehicle_number" 
                name="vehicle_number" 
                placeholder="e.g., TN0OAB1234" 
                maxlength="10" 
                pattern="[A-Za-z0-9]{10}" 
                oninput="this.value = this.value.toUpperCase()" 
                required
            >
            <span class="error" id="vehicleNumberError">Please enter a valid 10-character vehicle registration number.</span>
            <label for="availability">Availability:</label>
            <div style="display: flex; gap: 10px;">
                <input type="number" id="availability" name="availability" placeholder="Enter availability" required style="flex: 1;">
                <select id="availability_unit" name="availability_unit" required style="flex: 1;">
                    <option value="" disabled selected>Select unit</option>
                    <option value="kg">kg</option>
                    <option value="tons">tons</option>
                </select>
            </div>
            <span class="error" id="availabilityError">Please enter a valid availability.</span>
            <!-- Rate Per Kilometer -->
            <label for="rate_per_km">Rate per Kilometer (₹):</label>
            <input type="number" id="rate_per_km" name="rate_per_km" placeholder="Enter rate per km" min="1" required>
            <span class="error" id="ratePerKmError">Please enter a valid rate per kilometer.</span>
            <!-- Calculated Fields -->
            <button type="button" onclick="calculateDistance()">Calculate Distance</button>
            <p id="distance_display">Distance: N/A, Cost: N/A</p>
            <input type="hidden" id="distance" name="distance">
            <input type="hidden" id="cost" name="cost">
            <!-- Additional Info -->
            <label for="additional_info">Additional Info:</label>
            <textarea id="additional_info" name="additional_info" rows="3" placeholder="Provide any additional details here..."></textarea>
            <!-- Submit Button -->
            <button type="submit" onclick="return validateForm()">Submit</button>
        </form>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <h2>Smart Trip Share</h2>
                <p>Optimizing return trips, reducing costs, and making logistics smarter.</p>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Contact Us</h3>
                <p>Email: support@sts.com</p>
                <p>Phone: +91 73583 07246</p>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <p>Stay connected with us on social media for the latest updates.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Smart Trip Share. All rights reserved.</p>
        </div>
    </footer>
    <script>
        let autocompletePickup;
        let autocompleteDrop;
        let pickupLocation;
        let dropLocation;
        document.addEventListener('DOMContentLoaded', () => {
            // Restrict date input to current and future dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);
            initAutocomplete();
        });
        function initAutocomplete() {
            autocompletePickup = new google.maps.places.Autocomplete(
                document.getElementById("pickup"),
                { types: ["geocode"] }
            );
            autocompleteDrop = new google.maps.places.Autocomplete(
                document.getElementById("drop"),
                { types: ["geocode"] }
            );
            autocompletePickup.addListener("place_changed", () => {
                pickupLocation = autocompletePickup.getPlace().geometry.location;
            });
            autocompleteDrop.addListener("place_changed", () => {
                dropLocation = autocompleteDrop.getPlace().geometry.location;
            });
        }
        function calculateDistance() {
            if (!pickupLocation || !dropLocation) {
                alert("Please select both pickup and drop locations.");
                return;
            }
            const ratePerKm = parseFloat(document.getElementById('rate_per_km').value);
            if (isNaN(ratePerKm) || ratePerKm <= 0) {
                document.getElementById('ratePerKmError').style.display = 'block';
                return;
            }
            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [pickupLocation],
                    destinations: [dropLocation],
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status !== "OK") {
                        alert("Error: " + status);
                        return;
                    }
                    const distanceText = response.rows[0].elements[0].distance.text;
                    const distanceValue = response.rows[0].elements[0].distance.value; // Distance in meters
                    const distanceInKm = distanceValue / 1000; // Convert meters to kilometers
                    const cost = Math.round(distanceInKm * ratePerKm); // Approximate cost
                    // Display distance and cost
                    document.getElementById("distance_display").innerText = `Distance: ${distanceText}, Cost: ₹${cost}`;
                    document.getElementById("distance").value = distanceText;
                    document.getElementById("cost").value = cost;
                }
            );
        }
        function validateForm() {
            let isValid = true;
            // Clear previous errors
            document.querySelectorAll('.error').forEach(error => error.style.display = 'none');
            // Name validation
            const name = document.getElementById('name').value.trim();
            if (!name) {
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            }
            // Phone number validation
            const phone = document.getElementById('phone').value.trim();
            if (!/^\d{10}$/.test(phone)) {
                document.getElementById('phoneError').style.display = 'block';
                isValid = false;
            }
            // Date validation
            const date = document.getElementById('date').value;
            const today = new Date().toISOString().split('T')[0];
            if (!date || date < today) {
                document.getElementById('dateError').style.display = 'block';
                isValid = false;
            }
            // Vehicle type validation
            const vehicleType = document.getElementById('vehicle_type').value;
            if (!vehicleType) {
                document.getElementById('vehicleTypeError').style.display = 'block';
                isValid = false;
            }
            // Vehicle number validation
            const vehicleNumber = document.getElementById('vehicle_number').value.trim();
            if (!/^[A-Z0-9]{10}$/.test(vehicleNumber)) {
                document.getElementById('vehicleNumberError').style.display = 'block';
                isValid = false;
            }
            // Availability validation
            const availability = document.getElementById('availability').value.trim();
            if (!availability || isNaN(availability) || availability <= 0) {
                document.getElementById('availabilityError').style.display = 'block';
                isValid = false;
            }
            // Rate per kilometer validation
            const ratePerKm = document.getElementById('rate_per_km').value.trim();
            if (!ratePerKm || isNaN(ratePerKm) || ratePerKm <= 0) {
                document.getElementById('ratePerKmError').style.display = 'block';
                isValid = false;
            }
            return isValid;
        }
    </script>
</body>
</html>