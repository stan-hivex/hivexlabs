<?php
require_once 'config/db.php';
$conn->query('CREATE TABLE IF NOT EXISTS softwares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    software_name VARCHAR(255) NOT NULL,
    description TEXT,
    icon_class VARCHAR(100) DEFAULT "fa-code",
    demo_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)');
echo 'Softwares table created successfully!';
?>