<?php
require_once 'config/db.php';

$conn->query('ALTER TABLE product_content ADD COLUMN advantages LONGTEXT AFTER description');
$conn->query('ALTER TABLE product_content ADD COLUMN demo_cashier_email VARCHAR(255) AFTER demo_link');
$conn->query('ALTER TABLE product_content ADD COLUMN demo_cashier_pass VARCHAR(255) AFTER demo_cashier_email');
$conn->query('ALTER TABLE product_content ADD COLUMN demo_admin_email VARCHAR(255) AFTER demo_cashier_pass');
$conn->query('ALTER TABLE product_content ADD COLUMN demo_admin_pass VARCHAR(255) AFTER demo_admin_email');
$conn->query('ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS phone VARCHAR(50) AFTER email');
$conn->query('ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS project_type VARCHAR(150) AFTER phone');

echo 'Database columns added successfully!';
?>