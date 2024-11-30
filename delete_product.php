<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT name FROM products WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM products WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: productlist.php");
    exit();
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>
    <link rel="stylesheet" href="nice.css?version=1">
</head>
<body class="delete-background">
    <div class="back">
        <div class="container">
            <h1>Confirm Delete</h1>
            <p>Are you sure you want to delete the product: <strong><?php echo htmlspecialchars($row['name']); ?></strong>?</p>
            <form method="post" action="delete_product.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="confirm" value="yes">
                <input type="submit" value="Yes, Delete" class="btn">
                <a href="productlist.php" class="btn">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>