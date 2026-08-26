<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

// Quick stats
$projectsCount = $conn->query("SELECT COUNT(*) as c FROM projects")->fetch_assoc()['c'];
$servicesCount = $conn->query("SELECT COUNT(*) as c FROM services")->fetch_assoc()['c'];
$msgCount = $conn->query("SELECT COUNT(*) as c FROM messages")->fetch_assoc()['c'];

// Create products table if it doesn't exist
$createProductTable = "CREATE TABLE IF NOT EXISTS product_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_key VARCHAR(50) UNIQUE,
    title VARCHAR(255),
    description LONGTEXT,
    hero_image VARCHAR(255),
    demo_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createProductTable);

$productsCount = $conn->query("SELECT COUNT(*) as c FROM product_content")->fetch_assoc()['c'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard — HiveX Labs</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<style>
    :root{--bg:#040506;--panel:#0f1316;--accent:#00ff88;--cyan:#00ff88;--muted:#cbd5df}
    body{background:var(--bg);color:var(--muted);font-family:Inter,Arial;margin:0;padding:0}
    header{padding:20px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(0,255,136,0.2)}
    .brand{display:flex;align-items:center;gap:14px}
    .logo{background:var(--accent);width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--bg);font-weight:800}
    .wrap{padding:28px;max-width:1100px;margin:0 auto}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-top:20px}
    .card{background:var(--panel);padding:18px;border-radius:12px;border:1px solid rgba(0,255,136,0.3);box-shadow:0 2px 8px rgba(0,0,0,0.3)}
    a.btn{display:inline-block;padding:10px 14px;background:var(--accent);color:var(--bg);border-radius:10px;text-decoration:none;font-weight:700}
    nav{display:flex;gap:10px}
</style>
</head>
<body>
    <header>
        <div class="brand">
            <div class="logo">HX</div>
            <div>
                <div style="font-weight:800;color:var(--accent)">HiveX Labs</div>
                <div style="font-size:12px;color:var(--muted)">Admin Dashboard</div>
            </div>
        </div>
        <nav>
            <a class="btn" href="projects.php">Projects</a>
            <a class="btn" href="services.php">Services</a>
            <a class="btn" href="manage_products.php">Products</a>
            <a class="btn" href="hero.php">Hero</a>
            <a class="btn" href="softwares.php">Softwares</a>
            <a class="btn" href="hero_tools.php">Hero Tools</a>
            <a class="btn" href="about.php">About</a>
            <a class="btn" href="team.php">Team</a>
            <a class="btn" href="header_settings.php">Header</a>
            <a class="btn" href="contact_messages.php">Messages (<?= $msgCount ?>)</a>
            <a class="btn" href="messages_feedback.php">Feedback</a>
            <a class="btn" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="wrap">
        <h1 style="margin:0 0 8px 0">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h1>

        <p style="color:var(--muted);margin:0 0 20px 0">Overview of your HiveX Labs content.</p>

        <div class="grid">
            <div class="card">
                <h3 style="margin:0 0 10px 0">Projects</h3>
                <p style="font-size:28px;margin:0;font-weight:800"><?= $projectsCount ?></p>
                <div style="margin-top:12px"><a class="btn" href="projects.php">Manage Projects</a></div>
            </div>

            <div class="card">
                <h3 style="margin:0 0 10px 0">Services</h3>
                <p style="font-size:28px;margin:0;font-weight:800"><?= $servicesCount ?></p>
                <div style="margin-top:12px"><a class="btn" href="services.php">Manage Services</a></div>
            </div>

            <div class="card">
                <h3 style="margin:0 0 10px 0">Softwares</h3>
                <p style="font-size:28px;margin:0;font-weight:800"><?= $productsCount ?></p>
                <div style="margin-top:12px"><a class="btn" href="manage_products.php">Manage Softwares</a></div>
            </div>

            <div class="card">
                <h3 style="margin:0 0 10px 0">Messages</h3>
                <p style="font-size:28px;margin:0;font-weight:800"><?= $msgCount ?></p>
                <div style="margin-top:12px"><a class="btn" href="messages.php">View Messages</a></div>
            </div>
        </div>
    </div>
</body>
</html>
