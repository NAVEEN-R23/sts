<?php
session_start();

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
if (!$isLoggedIn) {
    die("<script>alert('You must be logged in to post a load requirement.'); window.location.href='login.php';</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $typeOfGoods = mysqli_real_escape_string($con, $_POST['type_of_goods']);
    $weight = mysqli_real_escape_string($con, $_POST['weight']);
    $weightUnit = mysqli_real_escape_string($con, $_POST['weight_unit']);
    $pickupLocation = mysqli_real_escape_string($con, $_POST['pickup_location']);
    $deliveryLocation = mysqli_real_escape_string($con, $_POST['delivery_location']);
    $pickupDate = mysqli_real_escape_string($con, $_POST['pickup_date']);
    $deliveryDate = mysqli_real_escape_string($con, $_POST['delivery_date']);
    $vehicleType = mysqli_real_escape_string($con, $_POST['vehicle_type']);
    $feet = mysqli_real_escape_string($con, $_POST['feet']);
    $wheels = mysqli_real_escape_string($con, $_POST['wheels']);
    $budget = mysqli_real_escape_string($con, $_POST['budget']);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['payment_method']);
    $additionalInfo = mysqli_real_escape_string($con, $_POST['additional_info']);

    // Insert query to store data in the database
    $insertQuery = "INSERT INTO load_requirements (
                        name, phone, email, type_of_goods, weight, weight_unit,
                        pickup_location, delivery_location, pickup_date, delivery_date,
                        vehicle_type, feet, wheels, budget, payment_method, additional_info
                    ) VALUES (
                        '$name', '$phone', '$email', '$typeOfGoods', '$weight', '$weightUnit',
                        '$pickupLocation', '$deliveryLocation', '$pickupDate', '$deliveryDate',
                        '$vehicleType', '$feet', '$wheels', '$budget', '$paymentMethod', '$additionalInfo'
                    )";

    // Execute the query
    if (mysqli_query($con, $insertQuery)) {
        echo "<script>alert('Load Requirement details posted successfully!'); window.location.href='home1.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

mysqli_close($con);
?>s

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Load Requirement</title>
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
        /* Footer Styling */
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

        /* Custom CSS for Feet and Wheels */
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        .checkbox-group input[type="radio"] {
            width: auto;
            height: auto;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Header (Navigation Bar) -->
    <header>
        <img src="properties/logo.png" alt="logo of sts" class="logo">
        <h1 class="grow tracking-in-expand">SMART TRIP SHARE</h1>
        <nav>
            <ul>
                <li><a href="home1.php">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="returnTrip.php">Post Return Trip</a></li>
                <li><a href="loadRequirement.php" class="active">Post Load Requirement</a></li>
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
        <h3>Post Your Load Requirement</h3>
        <p>
            Welcome to Smart Trip Share! Fill out the form below to post your load requirement details. 
            Provide accurate information so that drivers can connect with you easily.
        </p>
        <!-- Post Load Requirement Form -->
        <form action="" method="POST" id="loadForm">
            <!-- Customer Details Section -->
            <h4><i class="fas fa-user"></i> Customer Details</h4>
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
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            <span class="error" id="emailError">Please enter a valid email address.</span>
            <!-- Load Details Section -->
            <h4><i class="fas fa-box-open"></i> Load Details</h4>
            <label for="type_of_goods">Type of Goods:</label>
            <select id="type_of_goods" name="type_of_goods" required>
                <option value="" disabled selected>Select type of goods</option>
                <option value="General Cargo">General Cargo</option>
                <option value="Perishable Goods">Perishable Goods</option>
                <option value="Hazardous Materials">Hazardous Materials</option>
                <option value="Oversized/Heavy Loads">Oversized/Heavy Loads</option>
                <option value="Furniture">Furniture</option>
                <option value="Electronics">Electronics</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Other">Other</option>
            </select>
            <span class="error" id="goodsTypeError">Please select the type of goods.</span>
            <label for="weight">Weight:</label>
            <div style="display: flex; gap: 10px;">
                <input type="number" id="weight" name="weight" placeholder="Enter weight" min="1" required style="flex: 1;">
                <select id="weight_unit" name="weight_unit" required style="flex: 1;">
                    <option value="" disabled selected>Select unit</option>
                    <option value="kg">kg</option>
                    <option value="tons">tons</option>
                </select>
            </div>
            <span class="error" id="weightError">Please enter a valid weight.</span>
            
            <!-- Pickup and Delivery Details -->
            <h4><i class="fas fa-map-marker-alt"></i> Pickup and Delivery Details</h4>
            <label for="pickup_location">Pickup Location:</label>
            <input type="text" id="pickup_location" name="pickup_location" placeholder="Enter pickup location" required>
            <label for="delivery_location">Delivery Location:</label>
            <input type="text" id="delivery_location" name="delivery_location" placeholder="Enter delivery location" required>
            <label for="pickup_date">Pickup Date:</label>
            <input type="date" id="pickup_date" name="pickup_date" required>
            <span class="error" id="pickupDateError">Please select a valid pickup date.</span>
            <label for="delivery_date">Delivery Date:</label>
            <input type="date" id="delivery_date" name="delivery_date" required>
            <span class="error" id="deliveryDateError">Delivery date must be after the pickup date.</span>

            <!-- Vehicle Details Section -->
            <h4><i class="fas fa-truck"></i> Vehicle Details</h4>
            <label for="vehicle_type">Vehicle Type:</label>
            <select id="vehicle_type" name="vehicle_type" required>
                <option value="" disabled selected>Select vehicle type</option>
                <option value="Truck">Truck</option>
                <option value="Trailer">Trailer</option>
                <option value="Van">Van</option>
                <option value="Container">Container</option>
                <option value="Tanker">Tanker</option>
                <option value="Refrigerated Truck">Refrigerated Truck</option>
                <option value="Other">Other</option>
            </select>
            <span class="error" id="vehicleTypeError">Please select a vehicle type.</span>
            <label>Feet:</label>
            <div class="checkbox-group">
                <label><input type="radio" name="feet" value="14ft" required> 14ft</label>
                <label><input type="radio" name="feet" value="17ft"> 17ft</label>
                <label><input type="radio" name="feet" value="19ft"> 19ft</label>
                <label><input type="radio" name="feet" value="20ft"> 20ft</label>
                <label><input type="radio" name="feet" value="22ft"> 22ft</label>
                <label><input type="radio" name="feet" value="24ft"> 24ft</label>
                <label><input type="radio" name="feet" value="30+ft"> 30+ft</label>
            </div>
            <span class="error" id="feetError">Please select a valid length in feet.</span>
            <label>Wheels:</label>
            <div class="checkbox-group">
                <label><input type="radio" name="wheels" value="4" required> 4</label>
                <label><input type="radio" name="wheels" value="6"> 6</label>
                <label><input type="radio" name="wheels" value="8"> 8</label>
                <label><input type="radio" name="wheels" value="10"> 10</label>
                <label><input type="radio" name="wheels" value="12"> 12</label>
                <label><input type="radio" name="wheels" value="14"> 14</label>
                <label><input type="radio" name="wheels" value="16"> 16</label>
                <label><input type="radio" name="wheels" value="20+"> 20+</label>
            </div>
            <span class="error" id="wheelsError">Please select a valid number of wheels.</span>
            <!-- Pricing and Payment Details -->
            <h4><i class="fas fa-money-bill-wave"></i> Pricing and Payment Details</h4>
            <label for="budget">Budget (₹):</label>
            <input type="number" id="budget" name="budget" placeholder="Enter your budget" min="1" required>
            <span class="error" id="budgetError">Please enter a valid budget.</span>
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="" disabled selected>Select payment method</option>
                <option value="Cash">Cash</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Credit Card">Credit Card</option>
            </select>
            <span class="error" id="paymentMethodError">Please select a payment method.</span>
            <!-- Additional Information -->
            <label for="additional_info">Additional Information:</label>
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
        let autocompleteDelivery;
        document.addEventListener('DOMContentLoaded', () => {
            // Restrict date input to current and future dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('pickup_date').setAttribute('min', today);
            document.getElementById('delivery_date').setAttribute('min', today);
            // Ensure delivery date is after pickup date
            document.getElementById('pickup_date').addEventListener('change', function () {
                const pickupDate = this.value;
                document.getElementById('delivery_date').setAttribute('min', pickupDate);
            });
            initAutocomplete();
        });
        function initAutocomplete() {
            autocompletePickup = new google.maps.places.Autocomplete(
                document.getElementById("pickup_location"),
                { types: ["geocode"] }
            );
            autocompleteDelivery = new google.maps.places.Autocomplete(
                document.getElementById("delivery_location"),
                { types: ["geocode"] }
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
            // Email validation
            const email = document.getElementById('email').value.trim();
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }
            // Type of goods validation
            const goodsType = document.getElementById('type_of_goods').value;
            if (!goodsType) {
                document.getElementById('goodsTypeError').style.display = 'block';
                isValid = false;
            }
            // Weight validation
            const weight = document.getElementById('weight').value.trim();
            const weightUnit = document.getElementById('weight_unit').value;
            if (!weight || isNaN(weight) || weight <= 0 || !weightUnit) {
                document.getElementById('weightError').style.display = 'block';
                isValid = false;
            }
            // Vehicle type validation
            const vehicleType = document.getElementById('vehicle_type').value;
            if (!vehicleType) {
                document.getElementById('vehicleTypeError').style.display = 'block';
                isValid = false;
            }
            // Feet validation
            const feet = document.querySelector('input[name="feet"]:checked');
            if (!feet) {
                document.getElementById('feetError').style.display = 'block';
                isValid = false;
            }
            // Wheels validation
            const wheels = document.querySelector('input[name="wheels"]:checked');
            if (!wheels) {
                document.getElementById('wheelsError').style.display = 'block';
                isValid = false;
            }
            // Pickup date validation
            const pickupDate = document.getElementById('pickup_date').value;
            const today = new Date().toISOString().split('T')[0];
            if (!pickupDate || pickupDate < today) {
                document.getElementById('pickupDateError').style.display = 'block';
                isValid = false;
            }
            // Delivery date validation
            const deliveryDate = document.getElementById('delivery_date').value;
            if (!deliveryDate || deliveryDate < today || deliveryDate < pickupDate) {
                document.getElementById('deliveryDateError').style.display = 'block';
                isValid = false;
            }
            // Budget validation
            const budget = document.getElementById('budget').value.trim();
            if (!budget || isNaN(budget) || budget <= 0) {
                document.getElementById('budgetError').style.display = 'block';
                isValid = false;
            }
            // Payment method validation
            const paymentMethod = document.getElementById('payment_method').value;
            if (!paymentMethod) {
                document.getElementById('paymentMethodError').style.display = 'block';
                isValid = false;
            }
            return isValid;
        }
    </script>
</body>
</html>