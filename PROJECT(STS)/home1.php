<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ensure session is started only once
}
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "sts";
$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("<script>alert('Failed to connect to DB: " . $con->connect_error . "');</script>");
}
// Fetch trip details from the database
$query = "SELECT * FROM return_trips ORDER BY trip_date DESC"; // Fetch trips sorted by date
$result = mysqli_query($con, $query);
$trips = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $trips[] = $row;
    }
}
// Close connection
mysqli_close($con);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php
    // Check if the user is Logged in
    $isLoggedIn = isset($_SESSION['email']);
    ?>
    <!-- Header -->
    <header>
        <img src="properties/logo.png" alt="logo of sts" class="logo">
        <h1 class="grow tracking-in-expand">SMART TRIP SHARE</h1>
        <nav>
            <ul>
                <li><a href="home1.php" class="active">Home</a></li>
                <li><a href="#">About Us</a></li>
          <li><a href="#">Contact Us</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="returnTrip.php">Post Return Trip</a></li>
                    <li><a href="loadRequirement.php">Post Load Requirement</a></li>
                    <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <script>
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>

    <!---first part (project explain)-->
    <div class="first-container">
        <div class="left-info">
            <h1>Smart Trip Share : <br>Optimizing Return Trip <br>Efficiency in Load Transport</h1>
            <p>Filling vehicles with return cargo ensures cost savings, efficiency, and sustainability. With Smart Trip
                Share, say goodbye to empty return trips, wasted fuel, and lost revenue. Customers can post their load
                details, allowing nearby drivers to transport shipments efficiently. This ensures maximum truck
                utilization, reduces costs, and minimizes fuel waste, making transportation more profitable and
                sustainable for everyone.</p>
        </div>
        <div class="right-img">
            <img src="properties/second-img.webp" alt="truck img">
        </div>
    </div>

    <!-- Impact Section -->
    <div class="impact-section">
        <div class="impact-box">
            <h2>1000+</h2>
            <p>Trucks Utilized Efficiently</p>
        </div>
        <div class="impact-box">
            <h2>30%+</h2>
            <p>Fuel Cost Savings</p>
        </div>
        <div class="impact-box">
            <h2>5000+ Tons</h2>
            <p>Reduced Carbon Emissions</p>
        </div>
    </div>



    <!---second part (systen explain)-->
    <div class="image-container">
        <div class="image-box">
            <div class="flip-box">
                <div class="front">
                    <img src="properties/img1.jpeg" alt="Image 1">
                </div>
                <div class="back">
                    <h1>1. No Empty Returns, Only Smart Savings!</h1>
                    <h3>Maximize Every Trip & Reduce Costs</h3>
                    <p>Trucks should never return empty. SMART TRIP SHARE helps drivers save fuel and earn more by
                        filling return trips with cargo, making transportation affordable for users.</p>
                </div>
            </div>
        </div>

        <div class="image-box">
            <div class="flip-box">
                <div class="front">
                    <img src="properties/img2.jpeg" alt="Image 2">
                </div>
                <div class="back">
                    <h1>2. Fill Every Mile,<br> Save Every Ride!</h1>
                    <h3>Utilize Every Route Efficiently</h3>
                    <p>Empty miles mean wasted resources. Our platform connects available cargo with transporters,
                        ensuring every trip contributes to cost savings and sustainability.</p>
                </div>
            </div>
        </div>

        <div class="image-box">
            <div class="flip-box">
                <div class="front">
                    <img src="properties/img3.jpeg" alt="Image 3">
                </div>
                <div class="back">
                    <h1>3. Post Your Load,<br> Find Your Ride!</h1>
                    <h3>Seamless Load Posting for Easy Transport</h3>
                    <p>Customers can post their load details, making it easy for nearby drivers to transport shipments
                        efficiently. This ensures trucks are always utilized, reducing costs and fuel waste.</p>
                </div>
            </div>
        </div>

        <div class="image-box">
            <div class="flip-box">
                <div class="front">
                    <img src="properties/img4.jpeg" alt="Image 4">
                </div>
                <div class="back">
                    <h1>4. Reduce Wastage, <br>Ride Smarter!</h1>
                    <h3>Eco-Friendly, Cost-Effective Transport</h3>
                    <p>Our platform reduces fuel waste by ensuring trucks are always loaded, cutting carbon emissions
                        and promoting sustainable logistics.</p>
                </div>
            </div>
        </div>

        <div class="image-box">
            <div class="flip-box">
                <div class="front">
                    <img src="properties/img5.jpeg" alt="Image 5">
                </div>
                <div class="back">
                    <h1>5. Connecting Routes, Cutting Costs!</h1>
                    <h3>Linking Transporters & Customers for Smart Routes</h3>
                    <p>We connect users with transporters, creating cost-effective, optimized routes that benefit both
                        parties.</p>
                </div>
            </div>
        </div>
    </div>

    <!--main part (trip list)-->
    <main>
    <div class="trips-section">
    <h2>Available Trips</h2>
    <div class="trip-cards">
        <?php if (!empty($trips)): ?>
            <?php foreach ($trips as $trip): ?>
                <div class="trip-card">
                    <!-- Header -->
                    <h3><?= htmlspecialchars($trip['name']) ?></h3>
                    <!-- Contact Details -->
                    <div class="detail-row">
                        <i class="fas fa-phone icon"></i>
                        <span class="highlight"><?= htmlspecialchars($trip['phone']) ?></span>
                    </div>
                    <!-- Pickup and Drop Locations -->
                    <div class="detail-row">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <span>Pickup:</span>
                        <span class="highlight"><?= htmlspecialchars($trip['pickup_location']) ?></span>
                    </div>
                    <div class="detail-row">
                        <i class="fas fa-map-pin icon"></i>
                        <span>Drop:</span>
                        <span class="highlight"><?= htmlspecialchars($trip['drop_location']) ?></span>
                    </div>
                    <!-- Date and Vehicle Details -->
                    <div class="detail-row">
                        <i class="fas fa-calendar-alt icon"></i>
                        <span>Date:</span>
                        <span class="highlight"><?= htmlspecialchars($trip['trip_date']) ?></span>
                    </div>
                    <div class="detail-row">
                        <i class="fas fa-truck icon"></i>
                        <span>Vehicle:</span>
                        <span class="highlight"><?= htmlspecialchars($trip['vehicle_type']) ?> (<?= htmlspecialchars($trip['vehicle_number']) ?>)</span>
                    </div>
                    <!-- Distance and Cost -->
                    <div class="detail-row">
                        <i class="fas fa-road icon"></i>
                        <span>Distance:</span>
                        <span class="highlight"><?= htmlspecialchars($trip['distance']) ?></span>
                    </div>
                    <div class="detail-row">
                        <i class="fas fa-rupee-sign icon"></i>
                        <span>Cost:</span>
                        <span class="highlight">₹<?= htmlspecialchars($trip['cost']) ?></span>
                    </div>
                    <!-- Additional Info -->
                    <div class="detail-row">
                        <i class="fas fa-info-circle icon"></i>
                        <span>Additional Info:</span>
                        <span><?= htmlspecialchars($trip['additional_info']) ?></span>
                    </div>
                    <!-- Action Button -->
                    <button>Contact Driver</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; margin-top: 20px;">No trips available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

    </main>

    <!--bottom section (key benfits)-->
    <section>
        <div class="sec">
            <h2>Seamless Convenience</h2>
            <p>No more empty return trips! Easily connect cargo owners with available trucks, ensuring efficient
                transportation without the hassle of coordinating separate journeys.</p>
        </div>
        <div class="sec">
            <h2>Eco-Friendly & Sustainable</h2>
            <p>Reduce fuel waste and carbon emissions by utilizing trucks for return loads, promoting a greener and more
                sustainable transport system.</p>
        </div>
        <div class="sec">
            <h2>Maximized Efficiency</h2>
            <p>Every mile matters! Smart Trip Share ensures trucks are always loaded, minimizing empty trips and
                maximizing overall transport efficiency.</p>
        </div>
        <div class="sec">
            <h2>Cost Savings for Everyone</h2>
            <p>Lower transportation costs by filling empty truck space with return loads, making trips more profitable
                for drivers and cost-effective for cargo owners.</p>
        </div>
    </section>


    <!--final part (last)-->
    <div class="final">
        <div class="final-img">
            <img src="properties/final-img.webp" alt="">
        </div>
        <div class="final-para">
            <h2>Our Smart Transportation Approach</h2>
            <p>We don’t just connect trips, we make them smarter. Smart Trip Share helps shippers and transporters find
                the best match easily, reducing empty trips and saving costs. Our simple and transparent process ensures
                efficient, hassle-free, and eco-friendly transportation for everyone.</p>
        </div>
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



</body>

</html>