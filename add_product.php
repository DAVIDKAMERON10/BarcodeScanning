<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="nice.css?version=2">
</head>
<body class="add-product-background">
    <div class="back">
        <div class="container">
            <a href="index.php" class="btn2 back-btn">Back</a>
            <h1>Add New Product</h1>
            <?php
            include 'db_connect.php';

            $error_message = "";
            $success_message = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $barcode = $conn->real_escape_string($_POST['barcode']);
                $name = $conn->real_escape_string($_POST['name']);
                $price = $conn->real_escape_string($_POST['price']);

                // Validate barcode length
                if (strlen($barcode) == 13) {
                    $error_message = "Error: Barcode must be exactly 13 characters long.";
                } 
                // Validate price
                elseif (!is_numeric($price) || $price <= 0) {
                    $error_message = "Error: Price must not be ₱0.00 or below OR not in words.";
                } else {
                    // Check if the barcode already exists
                    $check_sql = "SELECT * FROM products WHERE barcode = '$barcode'";
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Error: Barcode already exists. Please use a unique barcode.";
                    } else {
                        // Insert new product
                        $sql = "INSERT INTO products (barcode, name, price) VALUES ('$barcode', '$name', '$price')";

                        if ($conn->query($sql) === TRUE) {
                            $success_message = "Product added successfully!";
                        } else {
                            $error_message = "Error: " . $conn->error;
                        }
                    }
                }
            }
            ?>
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <p><?php echo $success_message; ?></p>
            <?php endif; ?>
            <form method="post" action="add_product.php" class="product-form">
                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" maxlength="13" required class="input">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required class="input">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required class="input">
                <input type="submit" value="Add Product" class="btn">
            </form>
        </div>
    </div>
</body>
</html>
