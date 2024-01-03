<?php
// session_start();

// Assuming you have a database connection
include 'db_connection.php';
$totalPrice = 0;
// Get the count of rows in the cart table
$getCartCountSQL = "SELECT COUNT(*) AS cart_count FROM cart";
$getCartCountStmt = $conn->prepare($getCartCountSQL);

if ($getCartCountStmt === false) {
    echo "Error in preparing statement: " . $conn->error;
} else {
    $getCartCountStmt->execute();

    if ($getCartCountStmt->errno) {
        echo "Error executing statement: " . $getCartCountStmt->error;
    } else {
        $getCartCountStmt->bind_result($cart_count);
        $getCartCountStmt->fetch();
        $getCartCountStmt->close();
    }
}

// Retrieve products in the cart
$getCartProductsSQL = "SELECT cart.product_id, products.product_name, products.product_image, cart.quantity, products.current_price 
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
        $getCartProductsStmt->bind_result($product_id, $product_name, $product_image, $quantity, $product_price);
    }
}


?>


	<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +2547-2717-6688</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> electro@gmail.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> Nairobi, Ngong Road</a></li>
					</ul>
					<ul class="header-links pull-right">
					<li>
  <?php
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
      // User is logged in, display personalized greeting
      echo '<a href="#"><i class="fa fa-user-o"></i> Welcome, ' . $_SESSION['username'] . '!</a>';
      echo '</li><li><a href="logout.php"><i class="fa fa-lock"></i> LogOut</a>';
    } else {
      // User is not logged in, display the login link
      echo '<a href="login.php"><i class="fa fa-user-o"></i> My Account</a>';
    }
  ?>
</li>
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="./img/logo.PNG" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form>
									<select class="input-select">
										<option value="0">All Categories</option>
										<option value="1">Category 01</option>
										<option value="1">Category 02</option>
									</select>
									<input class="input" placeholder="Search here">
									<button class="search-btn">Search</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">

								<!-- Cart -->
								<div class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <i class="fa fa-shopping-cart"></i>
        <span>Your Cart</span>
        <div class="qty"><?php echo $cart_count; ?></div>
    </a>
    <div class="cart-dropdown">
        <div class="cart-list">
            <?php
            while ($getCartProductsStmt->fetch()) {
				 // Calculate and update total price
				 $totalPrice += $quantity * $product_price;
                ?>
                <div class="product-widget">
                    <div class="product-img">
                        <img src="<?php echo $product_image; ?>" alt="">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name"><a href="#"><?php echo $product_name; ?></a></h3>
                        <h4 class="product-price"><span class="qty"><?php echo $quantity; ?>x</span>Kes <?php echo $product_price; ?></h4>
                    </div>
                    <button class="delete"><i class="fa fa-close"></i></button>
                </div>
                <?php
            }
            $getCartProductsStmt->close();
            ?>
        </div>
        <div class="cart-summary">
            <small><?php echo $cart_count; ?> Item(s) selected</small>
			
					<h5>Total Kes <?php echo $totalPrice; ?></h5>
		</div><div class="cart-btns">
            <a href="checkout.php">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
        </div>
        </div>
        
    </div>
</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->

		<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav">
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="product.php">Categories</a></li>
						<li><a href="store.php">Shop</a></li>
						<li><a href="checkout.php">Cart</a></li>
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->