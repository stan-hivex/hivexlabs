<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Check if user is logged in (basic check, adjust as needed)
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: login.php");
//     exit;
// }

$products = [
    'software' => 'Software',
    'pos' => 'POS System',
    'university' => 'University/College Portal',
    'rental' => 'HiveX Rental Management',
    'roi' => 'HiveX Return on Investment'
];

$currentProduct = isset($_GET['product']) ? htmlspecialchars($_GET['product']) : 'software';

// Create table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS product_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_key VARCHAR(50) UNIQUE,
    title VARCHAR(255),
    description LONGTEXT,
    hero_image VARCHAR(255),
    demo_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createTableSQL);

// Get current product data
$result = $conn->query("SELECT * FROM product_content WHERE product_key = '$currentProduct'");
$productData = $result ? $result->fetch_assoc() : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $demoLink = $conn->real_escape_string($_POST['demo_link'] ?? '');
    $advantages = $conn->real_escape_string($_POST['advantages'] ?? '');
    $demoCashierEmail = $conn->real_escape_string($_POST['demo_cashier_email'] ?? '');
    $demoCashierPass = $conn->real_escape_string($_POST['demo_cashier_pass'] ?? '');
    $demoAdminEmail = $conn->real_escape_string($_POST['demo_admin_email'] ?? '');
    $demoAdminPass = $conn->real_escape_string($_POST['demo_admin_pass'] ?? '');
    $heroImage = $productData['hero_image'] ?? '';

    // Handle file upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['size'] > 0) {
        $uploadDir = __DIR__ . "/../uploads/";
        $fileName = time() . "_" . basename($_FILES['hero_image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadPath)) {
            $heroImage = "uploads/" . $fileName;
        }
    }

    // Insert or update
    if ($productData) {
        $conn->query("UPDATE product_content SET title='$title', description='$description', hero_image='$heroImage', demo_link='$demoLink', advantages='$advantages', demo_cashier_email='$demoCashierEmail', demo_cashier_pass='$demoCashierPass', demo_admin_email='$demoAdminEmail', demo_admin_pass='$demoAdminPass' WHERE product_key='$currentProduct'");
    } else {
        $conn->query("INSERT INTO product_content (product_key, title, description, hero_image, demo_link, advantages, demo_cashier_email, demo_cashier_pass, demo_admin_email, demo_admin_pass) VALUES ('$currentProduct', '$title', '$description', '$heroImage', '$demoLink', '$advantages', '$demoCashierEmail', '$demoCashierPass', '$demoAdminEmail', '$demoAdminPass')");
    }

    $productData = [
        'title' => $title,
        'description' => $description,
        'hero_image' => $heroImage,
        'demo_link' => $demoLink,
        'advantages' => $advantages,
        'demo_cashier_email' => $demoCashierEmail,
        'demo_cashier_pass' => $demoCashierPass,
        'demo_admin_email' => $demoAdminEmail,
        'demo_admin_pass' => $demoAdminPass
    ];
    $success = "Product updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #02061700, #0f172a00);
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 40px;
        }

        header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        header a {
            color: #00ff88;
            text-decoration: none;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            background: rgba(15, 19, 22, 0.6);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: #fff;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: #00ff88;
            color: #0a0a0a;
            border-color: #00ff88;
        }

        .tab-btn:hover {
            border-color: #00ff88;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #00ff88;
        }

        input[type="text"],
        input[type="url"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            background: rgba(15, 19, 22, 0.6);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 8px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #00ff88;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        .preview {
            margin-top: 15px;
        }

        .preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }

        .btn {
            padding: 12px 30px;
            background: #00ff88;
            color: #0a0a0a;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
        }

        .success {
            background: rgba(0, 255, 136, 0.2);
            border: 1px solid #00ff88;
            color: #00ff88;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
            }

            .tab-btn {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Manage Softwares</h1>
            <a href="../admin/dashboard.php">← Back to Admin Dashboard</a>
        </header>

        <?php if (isset($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <div class="tabs">
            <?php foreach ($products as $key => $name): ?>
                <a href="?product=<?= $key ?>" class="tab-btn <?= $currentProduct === $key ? 'active' : '' ?>">
                    <?= $name ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Product Title</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?= htmlspecialchars($productData['title'] ?? '') ?>" 
                    required
                />
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    required
                ><?= htmlspecialchars($productData['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="hero_image">Hero Image</label>
                <input type="file" id="hero_image" name="hero_image" accept="image/*" />
                <?php if ($productData && !empty($productData['hero_image'])): ?>
                    <div class="preview">
                        <p>Current Image:</p>
                        <img src="/<?= htmlspecialchars($productData['hero_image']) ?>" alt="Hero Image">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="demo_link">Demo Link</label>
                <input 
                    type="url" 
                    id="demo_link" 
                    name="demo_link" 
                    placeholder="https://demo.example.com"
                    value="<?= htmlspecialchars($productData['demo_link'] ?? '') ?>" 
                />
            </div>

            <div class="form-group">
                <label for="advantages">Advantages</label>
                <textarea 
                    id="advantages" 
                    name="advantages" 
                    placeholder="Enter advantages, one per line"
                ><?= htmlspecialchars($productData['advantages'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="demo_cashier_email">Demo Cashier Email</label>
                <input 
                    type="email" 
                    id="demo_cashier_email" 
                    name="demo_cashier_email" 
                    value="<?= htmlspecialchars($productData['demo_cashier_email'] ?? '') ?>" 
                />
            </div>

            <div class="form-group">
                <label for="demo_cashier_pass">Demo Cashier Password</label>
                <input 
                    type="text" 
                    id="demo_cashier_pass" 
                    name="demo_cashier_pass" 
                    value="<?= htmlspecialchars($productData['demo_cashier_pass'] ?? '') ?>" 
                />
            </div>

            <div class="form-group">
                <label for="demo_admin_email">Demo Admin Email</label>
                <input 
                    type="email" 
                    id="demo_admin_email" 
                    name="demo_admin_email" 
                    value="<?= htmlspecialchars($productData['demo_admin_email'] ?? '') ?>" 
                />
            </div>

            <div class="form-group">
                <label for="demo_admin_pass">Demo Admin Password</label>
                <input 
                    type="text" 
                    id="demo_admin_pass" 
                    name="demo_admin_pass" 
                    value="<?= htmlspecialchars($productData['demo_admin_pass'] ?? '') ?>" 
                />
            </div>

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
