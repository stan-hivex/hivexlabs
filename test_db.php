<?php
require_once 'config/db.php';
$result = $conn->query('SELECT * FROM product_content');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo 'Product: ' . $row['title'] . ' - Key: ' . $row['product_key'] . PHP_EOL;
    }
} else {
    echo 'No products found or query failed.';
}
?>