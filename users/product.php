<?php
require_once __DIR__ . "/../config/db.php";

$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'software';

// Create product_content table if it doesn't exist
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

// Create feedback table if it doesn't exist
$createFeedbackTable = "CREATE TABLE IF NOT EXISTS product_feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_key VARCHAR(50),
    name VARCHAR(100),
    email VARCHAR(100),
    feedback TEXT,
    rating INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createFeedbackTable);

// Handle feedback submission
$feedbackMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_submit'])) {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $feedback = $conn->real_escape_string($_POST['feedback'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);

    if ($name && $email && $feedback) {
        $conn->query("INSERT INTO product_feedback (product_key, name, email, feedback, rating) VALUES ('$type', '$name', '$email', '$feedback', $rating)");
        $feedbackMessage = 'Thank you for your feedback!';
    }
}

// Get product content from database
$result = $conn->query("SELECT * FROM product_content WHERE product_key = '$type'");
$productDB = $result ? $result->fetch_assoc() : null;

// Fallback data if not in database
$defaultProducts = [
    'software' => [
        'title' => 'Software Solutions',
        'description' => 'Custom software solutions tailored to your business needs. From web applications to desktop software, we build robust and scalable solutions.',
        'features' => [
            'Custom Application Development',
            'Cloud-based Solutions',
            'API Integration',
            'Database Design',
            'Quality Assurance & Testing'
        ]
    ],
    'pos' => [
        'title' => 'POS System',
        'description' => 'Complete Point of Sale system designed for retail and hospitality businesses. Streamline your sales, inventory, and customer management.',
        'heroImage' => 'uploads/1776777493_poshivex.png',
        'features' => [
            'Multi-location Support',
            'Real-time Inventory Management',
            'Sales Analytics & Reports',
            'Customer Database',
            'Barcode & QR Code Support',
            'Offline Mode Support'
        ]
    ],
    'university' => [
        'title' => 'University/College Portal',
        'description' => 'Comprehensive academic management portal for educational institutions. Manage students, courses, grades, and more from one unified platform.',
        'features' => [
            'Student Information System',
            'Course Management',
            'Grade Tracking & Reporting',
            'Attendance Management',
            'Online Fee Payment',
            'Parent Portal Access'
        ]
    ],
    'rental' => [
        'title' => 'HiveX Rental Management',
        'description' => 'Complete property and equipment rental management system. Manage bookings, payments, inventory, and customer relationships effortlessly.',
        'features' => [
            'Booking Management',
            'Payment Processing',
            'Inventory Tracking',
            'Customer Management',
            'Automated Invoicing',
            'Mobile-friendly Interface'
        ]
    ],
    'roi' => [
        'title' => 'HiveX Return on Investment',
        'description' => 'Investment tracking and analysis platform. Monitor your investments and get detailed insights into your return on investment.',
        'features' => [
            'Investment Portfolio Management',
            'Performance Analytics',
            'Real-time Market Data',
            'Risk Assessment',
            'ROI Calculation & Reports',
            'Financial Forecasting'
        ]
    ]
];

$product = $defaultProducts[$type] ?? $defaultProducts['software'];

// Merge with database data if available
if ($productDB) {
    $product['title'] = $productDB['title'] ?: $product['title'];
    $product['description'] = $productDB['description'] ?: $product['description'];
    $product['heroImage'] = !empty($productDB['hero_image']) ? $productDB['hero_image'] : ($product['heroImage'] ?? null);
    $product['demoLink'] = !empty($productDB['demo_link']) ? $productDB['demo_link'] : ($product['demoLink'] ?? null);
    $product['advantages'] = $productDB['advantages'] ?? ($product['advantages'] ?? '');
    $product['demo_cashier_email'] = $productDB['demo_cashier_email'] ?? '';
    $product['demo_cashier_pass'] = $productDB['demo_cashier_pass'] ?? '';
    $product['demo_admin_email'] = $productDB['demo_admin_email'] ?? '';
    $product['demo_admin_pass'] = $productDB['demo_admin_pass'] ?? '';
}

// Get all feedback for this product
$feedbackResult = $conn->query("SELECT * FROM product_feedback WHERE product_key = '$type' ORDER BY created_at DESC LIMIT 5");
$allFeedback = [];
if ($feedbackResult) {
    while ($row = $feedbackResult->fetch_assoc()) {
        $allFeedback[] = $row;
    }
}

function asset_path($path) {
    $currentDir = str_replace('\\', '/', realpath(__DIR__));
    $documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    if ($documentRoot && strpos($currentDir, $documentRoot) === 0) {
        $relativeDir = substr($currentDir, strlen($documentRoot));
    } else {
        $relativeDir = dirname($_SERVER['SCRIPT_NAME']);
    }
    $appRoot = dirname($relativeDir);
    if ($appRoot === '/' || $appRoot === '.' || $appRoot === '') {
        $appRoot = '';
    }
    return $appRoot . '/' . ltrim(str_replace('\\', '/', $path), '/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['title'] ?> - HiveX Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: radial-gradient(1200px 600px at 10% -10%, #060607 0%, #020202 45%);
            --muted: #c7d2fe;
            --accent: #3b82f6;
            --cyan: #93c5fd;
            --surface: rgba(7, 14, 28, 0.75);
            --surface-strong: rgba(4, 10, 20, 0.92);
            --border: rgba(59, 130, 246, 0.18);
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #05070dc4;
            color: var(--muted);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: var(--bg);
            opacity: 0.18;
            pointer-events: none;
            z-index: -2;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            padding: 22px 0;
            border-bottom: 1px solid rgba(59, 130, 246, 0.18);
            margin-bottom: 40px;
            backdrop-filter: blur(10px);
        }

        header a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
        }

        .hero-image {
            margin-bottom: 40px;
        }

        .hero-image img {
            width: 100%;
            height: auto;
            max-height: none;
            object-fit: contain;
            border-radius: 24px;
            border: 1px solid rgba(59, 130, 246, 0.25);
            box-shadow: 0 18px 55px rgba(0, 0, 0, 0.22);
        }

        .product-hero {
            padding: 48px 40px;
            text-align: center;
            margin-bottom: 60px;
            background: var(--surface);
            border-radius: 28px;
            border: 1px solid rgba(59, 130, 246, 0.16);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            position: relative;
            overflow: hidden;
        }

        .product-hero::before,
        .product-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .product-hero::before {
            background: linear-gradient(180deg, rgba(2, 15, 33, 0.55), transparent 80%);
        }

        .product-hero h1,
        .product-hero p,
        .product-hero .btn {
            position: relative;
            z-index: 1;
        }

        .product-hero h1 {
            font-size: 48px;
            margin-bottom: 18px;
            color: #fff;
        }

        .product-hero p {
            font-size: 18px;
            color: #d7e2ff;
            max-width: 760px;
            margin: 0 auto 30px;
            line-height: 1.75;
        }

        .product-hero.pos-hero {
            background: rgba(3, 13, 28, 0.92);
            border-color: rgba(0, 235, 250, 0.25);
        }

        .product-hero.pos-hero::before {
            background-image:
                linear-gradient(90deg, rgba(0,234,255,0.18) 1px, transparent 1px),
                linear-gradient(60deg, rgba(0,234,255,0.18) 1px, transparent 1px),
                linear-gradient(-60deg, rgba(0,234,255,0.18) 1px, transparent 1px);
            background-size: 80px 140px;
            opacity: 0.28;
        }

        .product-hero.pos-hero::after {
            background: radial-gradient(circle at 20% 20%, rgba(0, 255, 255, 0.08), transparent 18%),
                        radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.08), transparent 22%);
            opacity: 1;
        }

        .features-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 80px;
            position: relative;
            z-index: 1;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 28px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            min-height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            background: rgba(0, 235, 250, 0.1);
            box-shadow: 0 20px 45px rgba(0, 235, 250, 0.12);
            border-color: rgba(0, 235, 250, 0.25);
        }

        .feature-card h3 {
            color: var(--cyan);
            font-size: 16px;
            line-height: 1.7;
            margin: 0;
        }

        .advantages-section {
            margin-bottom: 80px;
            text-align: center;
        }

        .advantages-section h2 {
            font-size: 32px;
            color: var(--accent);
            margin-bottom: 28px;
        }

        .advantages-section ul {
            list-style: none;
            padding: 0;
            max-width: 820px;
            margin: 0 auto;
        }

        .advantages-section li {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 14px;
            color: #d8e2ff;
            text-align: left;
        }

        .demo-login-section {
            margin-bottom: 80px;
            text-align: center;
        }

        .demo-login-section h2 {
            font-size: 32px;
            color: var(--accent);
            margin-bottom: 28px;
        }

        .login-group {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 24px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
            backdrop-filter: blur(20px);
        }

        .login-group h3 {
            color: #fff;
            margin-bottom: 16px;
        }

        .login-group label {
            display: block;
            margin-bottom: 12px;
            color: var(--muted);
            font-weight: 500;
        }

        .login-group input {
            width: 100%;
            padding: 12px 14px;
            background: rgba(10, 16, 30, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 10px;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            border-radius: 999px;
            font-weight: 700;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            margin: 10px 6px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 42px rgba(0, 123, 255, 0.25);
        }

        .btn-secondary {
            background: rgba(59, 130, 246, 0.18);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.35);
        }

        .btn-secondary:hover {
            background: var(--accent);
            color: #fff;
        }

        .feedback-section {
            margin-bottom: 80px;
        }

        .feedback-section h2 {
            font-size: 32px;
            color: var(--accent);
            margin-bottom: 28px;
            text-align: center;
        }

        .success-message {
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.25);
            color: #d8f8ff;
            padding: 18px 22px;
            border-radius: 14px;
            margin-bottom: 24px;
            text-align: center;
        }

        .feedback-form {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 40px;
            max-width: 760px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--muted);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            background: rgba(10, 18, 35, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 12px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .form-group textarea {
            min-height: 140px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(20, 36, 60, 0.98);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.18);
        }

        .feedback-list {
            max-width: 760px;
            margin: 0 auto;
        }

        .feedback-item {
            background: rgba(255, 255, 255, 0.04);
            border-left: 4px solid var(--accent);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 18px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.09);
        }

        .feedback-item:hover {
            background: rgba(0, 235, 250, 0.08);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            gap: 12px;
        }

        .feedback-header strong {
            color: var(--accent);
        }

        .rating {
            color: #ffd166;
            font-size: 14px;
            letter-spacing: 0.03em;
        }

        .feedback-item p {
            color: var(--muted);
            margin-bottom: 10px;
            line-height: 1.8;
        }

        .feedback-item small {
            color: #99a8c6;
        }

        .cta-section {
            text-align: center;
            padding: 60px 24px;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(59, 130, 246, 0.16);
            border-radius: 22px;
            margin-bottom: 40px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
        }

        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 16px;
            color: #fff;
        }

        .cta-section p {
            color: var(--muted);
            margin-bottom: 24px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        footer {
            text-align: center;
            padding: 30px 0;
            border-top: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--muted);
        }

        @media (max-width: 1024px) {
            .product-hero {
                padding: 42px 28px;
            }

            .product-hero h1 {
                font-size: 38px;
            }
        }

        @media (max-width: 768px) {
            .product-hero {
                padding: 32px 24px;
            }

            .product-hero h1 {
                font-size: 32px;
            }

            .cta-section h2 {
                font-size: 26px;
            }

            .features-section {
                grid-template-columns: 1fr;
            }

            .feedback-section {
                padding: 20px 0;
            }

            .feedback-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="hive.php">← Back to Home</a>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (!empty($product['heroImage'])): ?>
                <section class="hero-image">
                    <img src="<?= htmlspecialchars(asset_path($product['heroImage'])) ?>" alt="<?= $product['title'] ?>">
                </section>
            <?php endif; ?>

            <section class="product-hero<?= $type === 'pos' ? ' pos-hero' : '' ?>">
                <h1><?= $product['title'] ?></h1>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                
                <?php if (!empty($product['demoLink'])): ?>
                    <a href="<?= htmlspecialchars($product['demoLink']) ?>" target="_blank" class="btn btn-secondary">View Demo</a>
                <?php endif; ?>
            </section>

            <section class="features-section">
                <?php foreach ($product['features'] as $feature): ?>
                    <div class="feature-card">
                        <h3>✓ <?= $feature ?></h3>
                    </div>
                <?php endforeach; ?>
            </section>

            <?php if (!empty($product['advantages'])): ?>
            <section class="advantages-section">
                <h2>Advantages</h2>
                <ul>
                    <?php foreach (explode("\n", $product['advantages']) as $adv): ?>
                        <?php if (trim($adv)): ?>
                            <li><?= htmlspecialchars(trim($adv)) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>

            <?php if (!empty($product['demo_cashier_email']) || !empty($product['demo_admin_email'])): ?>
            <section class="demo-login-section">
                <h2>Demo Login Credentials</h2>
                <?php if (!empty($product['demo_cashier_email'])): ?>
                <div class="login-group">
                    <h3>Cashier Access</h3>
                    <label>Email: <input type="text" readonly value="<?= htmlspecialchars($product['demo_cashier_email']) ?>"></label>
                    <label>Password: <input type="text" readonly value="<?= htmlspecialchars($product['demo_cashier_pass']) ?>"></label>
                </div>
                <?php endif; ?>
                <?php if (!empty($product['demo_admin_email'])): ?>
                <div class="login-group">
                    <h3>Admin Access</h3>
                    <label>Email: <input type="text" readonly value="<?= htmlspecialchars($product['demo_admin_email']) ?>"></label>
                    <label>Password: <input type="text" readonly value="<?= htmlspecialchars($product['demo_admin_pass']) ?>"></label>
                </div>
                <?php endif; ?>
            </section>
            <?php endif; ?>

            <!-- Feedback Section -->
            <section class="feedback-section">
                <h2>Customer Feedback</h2>

                <?php if ($feedbackMessage): ?>
                    <div class="success-message"><?= $feedbackMessage ?></div>
                <?php endif; ?>

                <div class="feedback-form">
                    <h3>Share Your Feedback</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <select id="rating" name="rating">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Fair</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="feedback">Your Feedback</label>
                            <textarea id="feedback" name="feedback" required placeholder="Share your thoughts about this product..."></textarea>
                        </div>

                        <button type="submit" name="feedback_submit" class="btn">Submit Feedback</button>
                    </form>
                </div>

                <?php if (!empty($allFeedback)): ?>
                    <div class="feedback-list">
                        <h3>Recent Feedback</h3>
                        <?php foreach ($allFeedback as $fb): ?>
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <strong><?= htmlspecialchars($fb['name']) ?></strong>
                                    <span class="rating">
                                        <?php for ($i = 0; $i < $fb['rating']; $i++): ?>
                                            ⭐
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <p><?= htmlspecialchars($fb['feedback']) ?></p>
                                <small><?= date('F d, Y', strtotime($fb['created_at'])) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="cta-section">
                <h2>Ready to Get Started?</h2>
                <p style="margin-bottom: 30px; color: #cbd5df;">Let's transform your business with our <?= $product['title'] ?></p>
                <a href="hive.php#contact" class="btn">Contact Us</a>
                <a href="hive.php#services" class="btn">Learn More</a>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 HiveX Labs. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
