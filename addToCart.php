<?php
session_start();

// Assuming you have a database connection
include 'db_connection.php';
// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Ensure that the script stops execution after the redirect
}

// Get the user_id from the session (assuming you set it after the user logs in)
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    // Get product details from the form
    $product_id = $_POST['product_id'];
    $quantity = 1; // Assuming you have a quantity input in your form

    // Validate and sanitize inputs if needed

    // Check if the product_id already exists in the user's cart
    $checkProductSQL = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $checkStmt = $conn->prepare($checkProductSQL);
    $checkStmt->bind_param("ii", $user_id, $product_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Product already exists, update the quantity
        $updateCartSQL = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateCartSQL);
        $updateStmt->bind_param("iii", $quantity, $user_id, $product_id);

        if ($updateStmt->execute()) {
            echo "Quantity updated in cart successfully.";
        } else {
            echo "Error updating quantity in cart: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        // Product does not exist, insert a new row
        $insertCartSQL = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertCartSQL);
        $insertStmt->bind_param("iii", $user_id, $product_id, $quantity);

        if ($insertStmt->execute()) {
            echo "Product added to cart successfully.";
            header("Location: index.php");
    
        } else {
            echo "Error adding product to cart: " . $insertStmt->error;
        }

        $insertStmt->close();
    }

    // Close the statement
    $checkStmt->close();
}
?>
