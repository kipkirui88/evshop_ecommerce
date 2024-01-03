
<?php // Start the session
session_start();
include 'db_connection.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Ensure that the script stops execution after the redirect
}

// Assuming you have a user_id in the session
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Insert data into the orders table
    $sqlInsertOrder = "INSERT INTO orders (user_id, name, email, phone, address) VALUES (?, ?, ?, ?, ?)";
    $stmtInsertOrder = $conn->prepare($sqlInsertOrder);
    $stmtInsertOrder->bind_param("issss", $user_id, $name, $email, $phone, $address);

    if ($stmtInsertOrder->execute()) {
        // Order placed successfully, now clear the user's cart
        $sqlClearCart = "DELETE FROM cart WHERE user_id = ?";
        $stmtClearCart = $conn->prepare($sqlClearCart);
        $stmtClearCart->bind_param("i", $user_id);

        if ($stmtClearCart->execute()) {
            echo "Order placed successfully, and cart cleared!";
            header("Location: index.php");
            exit();
        } else {
            echo "Error clearing cart: " . $stmtClearCart->error;
        }
    } else {
        echo "Error placing order: " . $stmtInsertOrder->error;
    }

    // Close prepared statements
    $stmtInsertOrder->close();
    $stmtClearCart->close();
}

// Close the database connection
$conn->close();
include 'includes/header.php';


// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Ensure that the script stops execution after the redirect
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
						<h3 class="breadcrumb-header">Checkout</h3>
						<ul class="breadcrumb-tree">
							<li><a href="#">Home</a></li>
							<li class="active">Checkout</li>
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

					<div class="col-md-7">
						<!-- Billing Details -->
						<div class="billing-details">
							<div class="section-title">
								<h3 class="title">Billing address</h3>
							</div>
							<form method="post">
							<div class="form-group">
								<input class="input" type="text" name="name" placeholder="Your Name" required>
							</div>
							<div class="form-group">
								<input class="input" type="email" name="email" placeholder="Your Email" required>
							</div>
							<div class="form-group">
								<input class="input" type="number" name="phone" placeholder="Your Phone Number" required>
							</div>
							<div class="form-group">
								<input class="input" type="text" name="address" placeholder="Your Address" required>
							</div>
							
							<button type="submit" class="primary-btn order">Place Order</button>
							</form>
						</div>
						<!-- /Billing Details -->

						<!-- Shiping Details -->
						<div class="shiping-details">
							<div class="section-title">
								<!-- <h3 class="title">Shiping address</h3> -->
							</div>
							<div class="input-checkbox">
								<!-- <input type="checkbox" id="shiping-address"> -->
								<label for="shiping-address">
									<span></span>
									<!-- Ship to a diffrent address? -->
								</label>
								<div class="caption">
									<div class="form-group">
										<input class="input" type="text" name="first_name" placeholder="First Name">
									</div>
									<div class="form-group">
										<input class="input" type="text" name="last_name" placeholder="Last Name">
									</div>
									<button type="submit">Place Order</button>
								</div>
							</div>
						</div>
						<!-- /Shiping Details -->

						<!-- Order notes -->
						<!-- <div class="order-notes">
							<textarea class="input" placeholder="Order Notes"></textarea>
						</div> -->
						<!-- /Order notes -->
					</div>

					<!-- Order Details -->
					<div class="col-md-5 order-details">
    <div class="section-title text-center">
        <h3 class="title">Your Order</h3>
    </div>
    <div class="order-summary">
        <div class="order-col">
            <div><strong>PRODUCT</strong></div>
            <div><strong>QUANTITY</strong></div>
            <div><strong>PRICE</strong></div>
            <div><strong>TOTAL</strong></div>
        </div>
        <div class="order-products">
            <?php
            // Fetch products in the user's cart
            $getCartProductsSQL = "SELECT cart.product_id, products.product_name, cart.quantity, products.current_price 
                                  FROM cart 
                                  INNER JOIN products ON cart.product_id = products.product_id";
            $getCartProductsStmt = $conn->prepare($getCartProductsSQL);

            if ($getCartProductsStmt === false) {
                echo "Error in preparing statement: " . $conn->error;
            } else {
                $getCartProductsStmt->execute();

                if ($getCartProductsStmt->errno) {
                    echo "Error executing statement: " . $getCartProductsStmt->error;
                } else {
                    $getCartProductsStmt->bind_result($product_id, $product_name, $quantity, $product_price);

                    while ($getCartProductsStmt->fetch()) {
                        // Calculate total price for each product
                        $totalProductPrice = $quantity * $product_price;
                        ?>
                        <div class="order-col">
                            <div><?php echo $product_name; ?></div>
                            <div><?php echo $quantity;    ?></div>
                            <div>Kes <?php echo $product_price; ?></div>
                            <div>Kes <?php echo $totalProductPrice; ?></div>
                        </div>
                        <?php
                    }
                }
            }
            $getCartProductsStmt->close();
            ?>
        </div>
        <div class="order-col">
            <div>Shipping</div>
            <div><strong>FREE</strong></div>
        </div>
        <div class="order-col">
            <div><strong>TOTAL</strong></div>
            <div><strong class="order-total">Kes <?php echo $totalPrice; ?></strong></div>
        </div>
    </div>
	<a href="index.php" class="primary-btn">Continue Shopping</a>
    <div class="payment-method">
    </div>
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
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="newsletter">
							<p>Sign Up for the <strong>NEWSLETTER</strong></p>
							<form>
								<input class="input" type="email" placeholder="Enter Your Email">
								<button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
							</form>
							<ul class="newsletter-follow">
								<li>
									<a href="#"><i class="fa fa-facebook"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-twitter"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-instagram"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-pinterest"></i></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /NEWSLETTER -->
		<?php include 'includes/footer.php' ?>

<?php include 'includes/scripts.php' ?>

	</body>
</html>
