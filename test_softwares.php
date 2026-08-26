<?php
require_once 'config/db.php';
$result = $conn->query('SELECT COUNT(*) as count FROM softwares');
if ($result) {
    $row = $result->fetch_assoc();
    echo 'Softwares table exists with ' . $row['count'] . ' records.';
} else {
    echo 'Softwares table does not exist or query failed.';
}
?>