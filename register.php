<?php
// Include database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user details from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL query to insert user details into the "users" table
    $sql = "INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $username, $password);

    if ($stmt->execute()) {
        echo "User registered successfully!";
        
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php' ?>

<body>
    <?php include 'includes/navbar.php' ?>

    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">Register</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="#">Home</a></li>
                        <li class="active">Register</li>
                    </ul>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- Registration Form -->
                <div class="col-md-5 order-details">
                    <form method="post">
                    <!-- Section Title -->
                    <div class="section-title text-center">
                        <h3 class="title">Registration Form</h3>
                    </div>
                    <!-- /Section Title -->

                    <!-- Registration Form Fields -->
                    <div class="col-md-12">
                        <div class="billing-details">
                            <div class="form-group">
                                <input class="input" type="text" name="first_name" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="last_name" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input class="input" type="password" name="password" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <!-- /Registration Form Fields -->

                    <!-- Login Link -->
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                    <!-- /Login Link -->

                    <!-- Submit Button -->
                    <button type="s" class="primary-btn register">Register</button>
                </form>
                </div>
                <!-- /Registration Form -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- NEWSLETTER -->
    <div id="newsletter" class="section">
        <!-- ... (existing newsletter code) ... -->
    </div>
    <!-- /NEWSLETTER -->

    <?php include 'includes/footer.php' ?>
    <?php include 'includes/scripts.php' ?>

</body>

</html>
