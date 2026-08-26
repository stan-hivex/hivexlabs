<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

// Handle form submission for adding/editing software
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_key = $conn->real_escape_string($_POST['product_key']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $advantages = $conn->real_escape_string($_POST['advantages']);
    $demo_link = $conn->real_escape_string($_POST['demo_link']);
    $hero_image = '';

    // Handle file upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['size'] > 0) {
        $uploadDir = __DIR__ . "/../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = time() . "_" . basename($_FILES['hero_image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadPath)) {
            $hero_image = "uploads/" . $fileName;
        }
    }

    // Check if editing existing product
    $existing = $conn->query("SELECT id FROM product_content WHERE product_key = '$product_key'");

    if ($existing->num_rows > 0) {
        // Update existing
        $update_sql = "UPDATE product_content SET
            title = '$title',
            description = '$description',
            advantages = '$advantages',
            demo_link = '$demo_link'";
        if (!empty($hero_image)) {
            $update_sql .= ", hero_image = '$hero_image'";
        }
        $update_sql .= " WHERE product_key = '$product_key'";
        $conn->query($update_sql);
        $message = "Software updated successfully!";
    } else {
        // Insert new
        $conn->query("INSERT INTO product_content
            (product_key, title, description, advantages, hero_image, demo_link)
            VALUES ('$product_key', '$title', '$description', '$advantages', '$hero_image', '$demo_link')");
        $message = "Software added successfully!";
    }

    header("Location: softwares.php?msg=" . urlencode($message));
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $product_key = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM product_content WHERE product_key = '$product_key'");
    header("Location: softwares.php?msg=" . urlencode("Software deleted successfully!"));
    exit;
}

// Get all software products
$softwares = $conn->query("SELECT * FROM product_content ORDER BY created_at DESC");

// Get software for editing
$edit_software = null;
if (isset($_GET['edit'])) {
    $product_key = $conn->real_escape_string($_GET['edit']);
    $result = $conn->query("SELECT * FROM product_content WHERE product_key = '$product_key'");
    $edit_software = $result->fetch_assoc();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Softwares — Admin</title>
    <style>
        body { background: #040506; color: #cbd5df; font-family: Inter, Arial; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .message { background: #00ff88; color: #040506; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .error { background: #dc2626; color: #fff; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .software-item { background: rgba(0,255,136,0.05); padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .software-info h4 { color: #00ff88; margin: 0 0 5px 0; }
        .software-info p { margin: 0; color: #9fb6c2; font-size: 14px; }
        .actions { display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600; }
        .btn-edit { background: #00ff88; color: #040506; }
        .btn-delete { background: #dc2626; color: #fff; }
        .btn-add { background: #3b82f6; color: #fff; padding: 12px 24px; display: inline-block; margin-bottom: 20px; }
        .form-container { background: rgba(59,130,246,0.05); padding: 20px; border-radius: 12px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #00ff88; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid rgba(0,235,250,0.3); border-radius: 6px; background: #020617; color: #cbd5df; }
        .form-group textarea { min-height: 100px; }
        .back-link { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Software Products</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="message"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <a href="softwares.php?action=add" class="btn btn-add">+ Add New Software</a>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_software): ?>
            <div class="form-container">
                <h3><?= $edit_software ? 'Edit Software' : 'Add New Software' ?></h3>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Key (unique identifier):</label>
                        <input type="text" name="product_key" value="<?= $edit_software['product_key'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" value="<?= $edit_software['title'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required><?= $edit_software['description'] ?? '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Advantages:</label>
                        <textarea name="advantages" placeholder="List the key advantages/features..."><?= $edit_software['advantages'] ?? '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Demo Link (optional):</label>
                        <input type="url" name="demo_link" value="<?= $edit_software['demo_link'] ?? '' ?>" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label>Hero Image (optional):</label>
                        <input type="file" name="hero_image" accept="image/*">
                        <?php if (!empty($edit_software['hero_image'])): ?>
                            <small>Current: <?= $edit_software['hero_image'] ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-add"><?= $edit_software ? 'Update Software' : 'Add Software' ?></button>
                    <a href="softwares.php" class="back-link">Cancel</a>
                </form>
            </div>
        <?php endif; ?>

        <h3>Existing Software Products</h3>
        <?php if($softwares->num_rows > 0): ?>
            <?php while($software = $softwares->fetch_assoc()): ?>
                <div class="software-item">
                    <div class="software-info">
                        <h4><?= htmlspecialchars($software['title']) ?> (<?= htmlspecialchars($software['product_key']) ?>)</h4>
                        <p><?= htmlspecialchars(substr($software['description'], 0, 100)) ?>...</p>
                        <?php if (!empty($software['demo_link'])): ?>
                            <small style="color: #3b82f6;">Demo: <?= htmlspecialchars($software['demo_link']) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="actions">
                        <a href="softwares.php?edit=<?= $software['product_key'] ?>" class="btn btn-edit">Edit</a>
                        <a href="softwares.php?delete=<?= $software['product_key'] ?>" class="btn btn-delete" onclick="return confirm('Delete this software?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No software products added yet.</p>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>