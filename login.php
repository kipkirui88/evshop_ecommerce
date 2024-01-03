<?php
// Include database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user credentials from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to retrieve user details from the "users" table
    $sql = "SELECT user_id, username, password FROM users WHERE username = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    if ($stmt === false) {
        die('Error executing statement: ' . $stmt->error);
    }

    // Bind the result variables
    $stmt->bind_result($user_id, $db_username, $db_password);

    // Fetch the result
    $stmt->fetch();

    // Verify if the password matches
    if (password_verify($password, $db_password)) {
        // Password is correct, user is logged in

        // You can store user information in the session if needed
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $db_username;

        echo '<script>alert("Login successful! Redirecting..."); window.location.href = "checkout.php";</script>';
        exit();
    } else {
        // Login failed
        echo '<script>alert("Incorrect username or password!");</script>';
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
                    <h3 class="breadcrumb-header">Login</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="#">Home</a></li>
                        <li class="active">Login</li>
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
                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <form method="post">
                    <!-- Order Summary -->
                    <div class="section-title text-center">
                        <h3 class="title">Login Form</h3>
                    </div>
                    <!-- /Order Summary -->
                    <div class="col-md-12">
                        <!-- Login Form -->
                        <div class="billing-details">
                            <div class="form-group">
                                <input class="input" type="text" name="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input class="input" type="password" name="password" placeholder="Password">
                            </div>
                            <!-- Create Account Link -->
                            <p>Don't have an account? <a href="register.php">Create one</a></p>
                        </div>
                        <!-- /Login Form -->
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="primary-btn login">Login</button>
                    </form>
                </div>
                <!-- /Order Details -->
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
