<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="nice.css?version=2">
</head>
<body class="edit-background">
<?php
include 'db_connect.php';

$error_message = "";
$row = [
    'id' => '',
    'barcode' => '',
    'name' => '',
    'price' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, barcode, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error_message = "Product not found.";
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Check for invalid barcode length
    if (strlen($barcode) !== 13) {
        $error_message = "Error: Barcode must be exactly 13 characters long.";
        $row['id'] = $id;
        $row['barcode'] = $barcode;
        $row['name'] = $name;
        $row['price'] = $price;
    } elseif (!is_numeric($price) || $price <= 0) {
        $error_message = "Error: Price must be a positive number greater than zero.";
        $row['id'] = $id;
        $row['barcode'] = $barcode;
        $row['name'] = $name;
        $row['price'] = $price;
    } else {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            $stmt = $conn->prepare("UPDATE products SET barcode = ?, name = ?, price = ? WHERE id = ?");
            $stmt->bind_param("ssdi", $barcode, $name, $price, $id);

            if ($stmt->execute() === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
                $row['id'] = $id;
                $row['barcode'] = $barcode;
                $row['name'] = $name;
                $row['price'] = $price;
            }
            $stmt->close();
        } else {
            echo "<div class='back'>";
            echo "<div class='container'>";
            echo "<form method='post' action='edit_product.php'>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>";
            echo "<input type='hidden' name='barcode' value='" . htmlspecialchars($barcode) . "'>";
            echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
            echo "<input type='hidden' name='price' value='" . htmlspecialchars($price) . "'>";
            echo "<p>Are you sure you want to update the product?</p>";
            echo "<input type='submit' name='confirm' value='yes' class='btn'>";
            echo "<a href='edit_product.php?id=" . htmlspecialchars($id) . "' class='btn'>no</a>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            exit();
        }
    }
}
?>

<div class="back">
    <div class="container">
        <a href="productlist.php" class="btn back-btn">Back</a>
        <h1>Edit Product</h1>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post" action="edit_product.php" class="product-form">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <label for="barcode">Barcode:</label>
            <input type="text" id="barcode" name="barcode" maxlength="13" value="<?php echo htmlspecialchars($row['barcode']); ?>" required class="input">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required class="input">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required class="input">
            <input type="submit" value="Update Product" class="btn">
        </form>
    </div>
</div>
</body>
</html>
