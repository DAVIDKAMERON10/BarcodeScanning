<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner</title>
    <link rel="stylesheet" href="nice.css?version=1">
</head>

<body class="home-background">

    <div class="back">
        <div class="container">
            <h1>David's Barcode Scanner</h1>
            <div class="scanner-section">
                <form method="post" action="index.php" class="scanner-form">
                    <label for="barcode">Enter Barcode:</label>
                    <input type="text" id="barcode" name="barcode" required class="inputer">
                    <input type="submit" value="Scan" class="btn12">
                </form>

                <?php
                include 'db_connect.php';
                $scanInProgress = false;

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['barcode'])) {
                    $barcode = $_POST['barcode'];

                    // Prepare the SQL statement
                    $stmt = $conn->prepare("SELECT name, price FROM products WHERE BINARY barcode = ?");
                    $stmt->bind_param("s", $barcode);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<div class='product-details'>";
                        echo "<h2>Product Details</h2>";
                        echo "<h3><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</h3>";
                        echo "<h3><strong>Price:</strong> ₱" . number_format(htmlspecialchars($row['price']), 2) . "</h3>";
                        echo "</div>";

                        // Insert into scan_history table
                        $name = $row['name'];
                        $price = $row['price'];
                        $insert_stmt = $conn->prepare("INSERT INTO scan_history (barcode, name, price) VALUES (?, ?, ?)");
                        $insert_stmt->bind_param("ssd", $barcode, $name, $price);
                        $insert_stmt->execute();
                        $insert_stmt->close();

                        $scanInProgress = true;
                    } else {
                        echo "<p class='error-message1'>Access Denied: Product not found!</p>";
                        $scanInProgress = true;
                    }

                    $stmt->close();
                }
                ?>

                <?php 
                if ($scanInProgress) { 
                    echo '<form method="get" action="index.php" class="refresh-form">';
                    echo '<input type="submit" value="Refresh" class="btn12">';
                    echo '</form>';
                } 
                ?>
            </div>

            <div class="button-container">
                <a href="add_product.php" class="btn">Add New Product</a>
                <a href="productlist.php" class="btn">View Product List</a>
                <a href="history.php" class="btn">View Product History</a>
            </div>
        </div>
    </div>
</body>
</html>
