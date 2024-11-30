<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="nice.css?version=1">
</head>

<body class="list-background">
    <div class="back">
        <div class="container">
            <h1>Product List</h1>

            <!-- Search Form -->
            <form method="POST" action="productlist.php" class="search-form">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Search by barcode or name"
                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>"
                    class="input1">
                    <button type="submit" class="search-button"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </div>
            </form>

            <div class="product-management">
                <table>
                    <thead>
                        <tr>
                            <th>
                                Barcode
                                <div class="dropdown">
                                    <button class="dropbtn">Sort</button>
                                    <div class="dropdown-content">
                                        <a href="?sort=barcode&order=asc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">0-9 <i class="fa-solid fa-arrow-up"></i></a>
                                        <a href="?sort=barcode&order=desc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">9-0 <i class="fa-solid fa-arrow-down"></i></a>
                                    </div>
                                </div>
                            </th>
                            <th>
                                Name
                                <div class="dropdown">
                                    <button class="dropbtn">Sort</button>
                                    <div class="dropdown-content">
                                        <a href="?sort=name&order=asc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">A-Z <i class="fa-solid fa-arrow-up"></i></a>
                                        <a href="?sort=name&order=desc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">Z-A <i class="fa-solid fa-arrow-down"></i></a>
                                    </div>
                                </div>
                            </th>
                            <th>
                                Price
                                <div class="dropdown">
                                    <button class="dropbtn">Sort</button>
                                    <div class="dropdown-content">
                                        <a href="?sort=price&order=asc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">Low-High <i class="fa-solid fa-arrow-up"></i></a>
                                        <a href="?sort=price&order=desc&search=<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">High-Low <i class="fa-solid fa-arrow-down"></i></a>
                                    </div>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    include 'db_connect.php';

                    // Set the number of products per page
                    $products_per_page = 5;

                    // Get the current page number from the URL, default to 1 if not set
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Calculate the starting row for the SQL query
                    $start_from = ($current_page - 1) * $products_per_page;

                    // Get sorting parameter
                    $sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // Default sort by id
                    $sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default sorting order

                    // Validate the sorting column
                    $valid_sort_columns = ['id', 'barcode', 'name', 'price'];
                    if (!in_array($sort_column, $valid_sort_columns)) {
                        $sort_column = 'id';
                    }

                    // Handle search query
                    $search_query = "";
                    if (isset($_POST['search'])) {
                        $search_query = $conn->real_escape_string($_POST['search']);
                    }

                    // Get the total number of products
                    $total_sql = "SELECT COUNT(*) AS total FROM products";
                    if ($search_query) {
                        $total_sql .= " WHERE barcode LIKE '%$search_query%' OR name LIKE '%$search_query%'";
                    }
                    $total_result = $conn->query($total_sql);
                    $total_row = $total_result->fetch_assoc();
                    $total_products = $total_row['total'];

                    // Calculate total pages
                    $total_pages = ceil($total_products / $products_per_page);

                    // Fetch the products for the current page with sorting and search
                    $sql = "SELECT id, barcode, name, price FROM products";
                    if ($search_query) {
                        $sql .= " WHERE barcode LIKE '%$search_query%' OR name LIKE '%$search_query%'";
                    }
                    $sql .= " ORDER BY $sort_column $sort_order LIMIT $start_from, $products_per_page";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['barcode']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>₱" . number_format(htmlspecialchars($row['price']), 2) . "</td>";
                            echo "<td>";
                            echo "<div class='action-buttons'>";
                            echo "<a href='edit_product.php?id=" . $row['id'] . "' class='btn-small'><i class='fa-solid fa-pen-to-square'></i></a>";
                            echo "<a href='delete_product.php?id=" . $row['id'] . "' class='btn-small'><i class='fa-solid fa-trash'></i></a>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No products found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <form method="GET" action="productlist.php">
                        <input type="hidden" name="sort" value="<?php echo $sort_column; ?>">
                        <input type="hidden" name="order" value="<?php echo $sort_order; ?>">
                        <input type="hidden" name="search" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit" name="page" value="<?php echo $current_page - 1; ?>" class="btn-big">
                            <i class="fa-solid fa-arrow-left"></i> Previous
                        </button>
                    </form>
                <?php endif; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <form method="GET" action="productlist.php">
                        <input type="hidden" name="sort" value="<?php echo $sort_column; ?>">
                        <input type="hidden" name="order" value="<?php echo $sort_order; ?>">
                        <input type="hidden" name="search" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit" name="page" value="<?php echo $current_page + 1; ?>" class="btn-big">Next
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="button-container">
                <a href="index.php" class="btn">Back to Scanner</a>
                <a href="add_product.php" class="btn">Add New Product</a>
                <a href="history.php" class="btn">View Product History</a>
            </div>
        </div>
    </div>
</body>
</html>
