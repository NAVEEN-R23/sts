<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection details
$host = "localhost";
$user = "root";
$pass = "";
$db = "sts";

// Create a new database connection
$con = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($con->connect_error) {
    die("<script>alert('Failed to connect to DB: " . $con->connect_error . "');</script>");
}

// Function to fetch data from a table
function fetchData($con, $table) {
    $query = "SELECT * FROM $table ORDER BY created_at DESC"; // Assuming 'created_at' column exists for sorting
    $result = mysqli_query($con, $query);

    // Check if query execution was successful
    if (!$result) {
        die("<script>alert('Query failed: " . mysqli_error($con) . "');</script>");
    }

    $data = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fetch data for load requirements and return trips
$loadRequirements = fetchData($con, "load_requirements");
$returnTrips = fetchData($con, "return_trips");

// Close the database connection
mysqli_close($con);
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

   <!-- Main Part (Trip List) -->
<?php if ($isLoggedIn): ?>
   <main>
    <!-- Overall Heading -->
    <h2 style="
    font-size: 42px; 
    font-weight: 800; 
    color: transparent; 
    background: linear-gradient(90deg, #ff7a00, #ff4500); 
    -webkit-background-clip: text; 
    background-clip: text; 
    text-align: center; 
    margin: 30px 0; 
    letter-spacing: 2px; 
    text-transform: uppercase; 
    position: relative; 
    animation: glow 2s infinite alternate;
">
    Available Trips
</h2>

    <!-- Toggle Buttons -->
    <div class="toggle-buttons">
        <button id="show-return-trips" class="active">Return Trips</button>
        <button id="show-load-requirements">Load Requirements</button>
    </div>

    <!-- Return Trips Section -->
    <div id="return-trips-section" class="section" style="display: none;">
    <div class="definition-box">
            <h3><i class="fas fa-info-circle icon"></i> What is a Return Load?</h3>
            <p>
                A return load refers to the cargo that a vehicle carries on its way back to its origin after completing a delivery. Utilizing return loads helps optimize resource utilization by reducing empty trips and saving fuel costs. This approach enhances the overall efficiency of transportation operations by making use of otherwise wasted space during return journeys.
            </p>
        </div>
        <?php if (!empty($returnTrips)): ?>
            <div class="cards">
                <?php foreach ($returnTrips as $row): ?>
                    <div class="trip-card">
                        <h3><i class="fas fa-truck icon"></i> Return Trip Details</h3>
                        <div class="details">
                            <p>
                                <i class="fas fa-user icon"></i>
                                <strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt icon"></i>
                                <strong>Pickup:</strong> <?php echo htmlspecialchars($row['pickup_location']); ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt icon"></i>
                                <strong>Drop:</strong> <?php echo htmlspecialchars($row['drop_location']); ?>
                            </p>
                            <p>
                                <i class="fas fa-calendar-alt icon"></i>
                                <strong>Date:</strong> <?php echo htmlspecialchars($row['trip_date']); ?>
                            </p>
                            <p>
                                <i class="fas fa-truck icon"></i>
                                <strong>Vehicle:</strong> <?php echo htmlspecialchars($row['vehicle_type']); ?>
                            </p>
                            <p>
                                <i class="fas fa-hashtag icon"></i>
                                <strong>Vehicle Number:</strong> <?php echo htmlspecialchars($row['vehicle_number']); ?>
                            </p>
                            <p>
                                <i class="fas fa-check-circle icon"></i>
                                <strong>Availability:</strong> <?php echo htmlspecialchars($row['availability']);echo " ".htmlspecialchars($row['availability_unit']); ?>
                            </p>
                            <p>
                                <i class="fas fa-road icon"></i>
                                <strong>Distance:</strong> <?php echo htmlspecialchars($row['distance']); ?> 
                            </p>
                            <p>
                                <i class="fas fa-money-bill-wave icon"></i>
                                <strong>Cost:</strong> $<?php echo htmlspecialchars($row['cost']); ?>
                            </p>
                            <p>
                                <i class="fas fa-info-circle icon"></i>
                                <strong>Additional Info:</strong> <?php echo htmlspecialchars($row['additional_info']); ?>
                            </p>
                        </div>
                        <button class="cta-button" data-trip-id="<?php echo htmlspecialchars($row['id']); ?>">Book This Trip</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No return trips found.</p>
        <?php endif; ?>
    </div>

    <!-- Load Requirements Section -->
    <div id="load-requirements-section" class="section" style="display: block;">
    <div class="definition-box">
            <h3><i class="fas fa-info-circle icon"></i> What is a Load Requirement?</h3>
            <p>
                A load requirement refers to the specific quantity, weight, and type of cargo that needs to be transported from one location to another. Properly defining load requirements ensures efficient planning, appropriate vehicle selection, and safe transportation. Meeting load requirements effectively helps in minimizing operational costs and maintaining profitability while ensuring that the logistics process is smooth and well-organized.
            </p>
        </div>
        <?php if (!empty($loadRequirements)): ?>
            <div class="cards">
                <?php foreach ($loadRequirements as $row): ?>
                    <div class="trip-card">
                        <h3><i class="fas fa-truck icon"></i> Load Details</h3>
                        <div class="details">
                            <p>
                                <i class="fas fa-user icon"></i>
                                <strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt icon"></i>
                                <strong>Pickup:</strong> <?php echo htmlspecialchars($row['pickup_location']); ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt icon"></i>
                                <strong>Delivery:</strong> <?php echo htmlspecialchars($row['delivery_location']); ?>
                            </p>
                            <p>
                                <i class="fas fa-calendar-alt icon"></i>
                                <strong>Dates:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?> - <?php echo htmlspecialchars($row['delivery_date']); ?>
                            </p>
                            <p>
                                <i class="fas fa-boxes-stacked icon"></i>
                                <strong>Goods Type:</strong> <?php echo htmlspecialchars($row['type_of_goods']); ?>
                            </p>
                            <p>
                                <i class="fas fa-weight-hanging icon"></i>
                                <strong>Weight:</strong> <?php echo htmlspecialchars($row['weight']); ?> <?php echo htmlspecialchars($row['weight_unit']); ?>
                            </p>
                            <p>
                                <i class="fas fa-truck icon"></i>
                                <strong>Vehicle Type:</strong> <?php echo htmlspecialchars($row['vehicle_type']); ?>
                            </p>
                            <p>
                                <i class="fas fa-info-circle icon"></i>
                                <strong>Additional Info:</strong> <?php echo htmlspecialchars($row['additional_info']); ?>
                            </p>
                        </div>
                        <button class="cta-button" data-trip-id="<?php echo htmlspecialchars($row['id']); ?>">Book This Trip</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No load requirements found.</p>
        <?php endif; ?>
    </div>
</main>
<?php endif; ?>




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

<!-- JavaScript for toggling sections -->
<script>
// Get references to the sections and buttons
const loadRequirementsSection = document.getElementById('load-requirements-section');
const returnTripsSection = document.getElementById('return-trips-section');
const showLoadRequirementsButton = document.getElementById('show-load-requirements');
const showReturnTripsButton = document.getElementById('show-return-trips');

// Function to toggle sections
function toggleSections(showSectionId, hideSectionId) {
    document.getElementById(showSectionId).style.display = 'block';
    document.getElementById(hideSectionId).style.display = 'none';
}

// Event listeners for buttons
showLoadRequirementsButton.addEventListener('click', () => {
    toggleSections('load-requirements-section', 'return-trips-section');
});

showReturnTripsButton.addEventListener('click', () => {
    toggleSections('return-trips-section', 'load-requirements-section');
});

// Initially show Return Trips
toggleSections('return-trips-section', 'load-requirements-section');

// Select all buttons inside the toggle-buttons container
const buttons = document.querySelectorAll('.toggle-buttons button');

// Add click event listeners to each button
buttons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove the 'active' class from all buttons
        buttons.forEach(btn => btn.classList.remove('active'));

        // Add the 'active' class to the clicked button
        button.classList.add('active');
    });
});

    //booking button functions
    document.addEventListener('DOMContentLoaded', function() {
    const bookButtons = document.querySelectorAll('.cta-button');

    bookButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const tripCard = event.target.closest('.trip-card'); // Get the parent card of the button

            // Display a confirmation message
            if (confirm('Are you sure you want to book this trip?')) {
                // Remove the trip card from the DOM
                tripCard.remove();
                alert('Trip booked successfully!');
            }
        });
    });
});
    
    </script>

</body>

</html>