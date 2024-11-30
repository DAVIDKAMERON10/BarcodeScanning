<?php
include 'db_connect.php';

$confirmDelete = false;

// Check if the reset action is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_reset'])) {
    // Execute the delete query
    $sql = "DELETE FROM scan_history";
    if ($conn->query($sql) === TRUE) {
        $message = "History reset successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetching the history records
$sql = "SELECT * FROM scan_history";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product History</title>
    <link rel="stylesheet" href="nice.css?version=1">
</head>
<body class="history-background">
    <div class="back">
        <div class="container">
            <h1>Product History</h1>

            <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

            <div class="history-management">
                <table>
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Date Scanned</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['barcode']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>₱" . number_format(htmlspecialchars($row['price']), 2) . "</td>";
                            echo "<td>" . htmlspecialchars($row['scan_time']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No scan history found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="button-container">
                <form method="post" action="history.php" style="display: inline;">
                    <input type="hidden" name="confirm_reset" value="yes">
                    <input type="submit" value="Reset History" class="btn">
                </form>
                <a href="index.php" class="btn">Back to Scanner</a>
            </div>
        </div>
    </div>
</body>
</html>
