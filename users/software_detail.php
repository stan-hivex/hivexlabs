<?php
require_once __DIR__ . "/../config/db.php";

function asset_path($path) {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#', $path)) {
        return $path;
    }

    $isRootRelative = strpos($path, '/') === 0;
    $encodedSegments = array_map('rawurlencode', array_filter(explode('/', ltrim($path, '/')), 'strlen'));
    $path = implode('/', $encodedSegments);
    if ($isRootRelative) {
        return '/' . $path;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $appRoot = dirname(dirname($scriptName));
    if ($appRoot === '/' || $appRoot === '.' || $appRoot === '\\') {
        $appRoot = '';
    }

    if ($appRoot === '' && strpos($scriptName, '/hivex-labs/') !== false) {
        $appRoot = '/hivex-labs';
    }

    return ($appRoot === '' ? '' : $appRoot) . '/' . $path;
}

$product_key = isset($_GET['key']) ? $conn->real_escape_string($_GET['key']) : '';

if (empty($product_key)) {
    header("Location: hive.php#softwares");
    exit;
}

$result = $conn->query("SELECT * FROM product_content WHERE product_key = '$product_key'");

if ($result->num_rows === 0) {
    header("Location: hive.php#softwares");
    exit;
}

$software = $result->fetch_assoc();

$imagePath = $software['hero_image'] ?? '';
$imageUrl = asset_path($imagePath);
$serverPath = '';
$fileExists = false;
$debugReason = 'No debug reason available.';
if ($imagePath === '') {
    $debugReason = 'No hero_image value is stored in the database.';
} else {
    if (preg_match('#^(https?:)?//#', $imagePath)) {
        $serverPath = '(external URL)';
        $debugReason = 'Image is stored as an external URL. Check that the URL is reachable.';
    } else {
        $rootPath = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
        $relativePath = strpos($imagePath, '/') === 0 ? $imagePath : '/' . $imagePath;
        $serverPath = $rootPath . $relativePath;
        $fileExists = file_exists($serverPath);
        if ($fileExists) {
            $debugReason = 'The file exists on the server. If the image still does not display, verify server rewrite or URL access for /uploads/ files.';
        } else {
            $debugReason = 'The image file is missing on the server at the expected path.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($software['title']) ?> – HiveX Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { 
            --bg: radial-gradient(1200px 600px at 10% -10%, #060607 0%, #020202 45%);
            --muted: #cbd5df; 
            --accent: #00ebfa; 
            --green: #00ff88; 
            scroll-behavior: smooth;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Inter, sans-serif; }
        html, body { 
            background: #05070dc4;
            color: var(--muted); 
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        .detail-container { max-width: 900px; margin: 40px auto; padding: 0 20px; position: relative; z-index: 1; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--accent); text-decoration: none; font-weight: 600; margin-bottom: 30px; transition: all 0.2s ease; }
        .back-link:hover { gap: 12px; }
        .software-detail { background: rgba(0,235,250,0.05); border: 1px solid rgba(0,235,250,0.2); border-radius: 16px; padding: 40px; }
        .detail-header { margin-bottom: 30px; }
        .detail-header h1 { color: var(--accent); font-size: 32px; margin-bottom: 10px; }
        .detail-header .date { color: #9fb6c2; font-size: 14px; }
        .hero-image { width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px; margin: 20px 0; background: #f8f9fa; }
        .section { margin-bottom: 30px; }
        .section h2 { color: var(--green); font-size: 20px; margin-bottom: 12px; }
        .section p, .section li { line-height: 1.8; color: var(--muted); margin-bottom: 10px; }
        .section ul { margin-left: 20px; }
        .section li { margin-bottom: 8px; }
        .demo-section { background: rgba(0,255,136,0.05); padding: 20px; border-radius: 12px; border-left: 4px solid var(--green); margin-top: 20px; }
        .demo-link { display: inline-block; margin-top: 15px; padding: 10px 20px; background: var(--green); color: #040506; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
        .demo-link:hover { background: #00ff88; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,255,136,0.3); }
        .debug-box { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.14); color: #e0f7ff; padding: 18px; border-radius: 14px; margin-bottom: 24px; font-family: monospace; white-space: pre-wrap; word-break: break-all; }
        
        /* Mobile Responsive Styles for Flexible Images */
        @media (max-width: 768px) {
            .hero-image { 
                width: 100%; 
                height: auto;
                max-height: none;
                object-fit: contain; 
                border-radius: 12px; 
                margin: 20px 0; 
                background: #f8f9fa;
                display: block;
            }
            .detail-container { margin: 20px auto; padding: 0 15px; }
            .software-detail { padding: 20px; }
        }
        
        @media (max-width: 480px) {
            .hero-image { 
                width: 100%; 
                height: auto;
                max-height: none;
                object-fit: contain;
                border-radius: 8px;
                margin: 15px 0;
                background: #f8f9fa;
            }
            .detail-container { padding: 0 10px; }
            .software-detail { padding: 15px; }
            .detail-header h1 { font-size: 24px; }
        }
         /* HEX GRID LAYER */
.circuit-bg::before {
    content: "";
    position: absolute;
    inset: 0;

    background-image:
        linear-gradient(90deg, rgba(0,234,255,0.25) 1px, transparent 1px),
        linear-gradient(60deg, rgba(0,234,255,0.25) 1px, transparent 1px),
        linear-gradient(-60deg, rgba(0,234,255,0.25) 1px, transparent 1px);

    background-size: 80px 140px;
    opacity: 0.25;
}

.circuit-bg {
    position: fixed;
    inset: 0;
    z-index: -3;
    background: #05070d;

    background-image:
      radial-gradient(circle at 50% 50%, transparent 34px, rgba(0,234,255,0.35) 35px, rgba(0,234,255,0.35) 36px, transparent 37px);

    background-size: 70px 60.62px;
}
    </style>
</head>
<body>
    <div class="circuit-bg"></div>
    <div class="detail-container">
        <a href="<?= asset_path('hive.php') ?>#softwares" class="back-link"><i class="fas fa-arrow-left"></i> Back to Software</a>

        <div class="software-detail">
            <div class="detail-header">
                <h1><?= htmlspecialchars($software['title']) ?></h1>
                <p class="date">Added: <?= date('M d, Y', strtotime($software['created_at'])) ?></p>
            </div>

            <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
                <div class="debug-box">
                    Stored hero_image: <?= htmlspecialchars($imagePath) ?>
                    Generated URL: <?= htmlspecialchars($imageUrl) ?>
                    Server path: <?= htmlspecialchars($serverPath) ?>
                    File exists: <?= $fileExists ? 'yes' : 'no' ?>
                    Reason: <?= htmlspecialchars($debugReason) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($software['hero_image'])): ?>
                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($software['title']) ?>" class="hero-image">
            <?php endif; ?>

            <div class="section">
                <h2>About</h2>
                <p><?= nl2br(htmlspecialchars($software['description'])) ?></p>
            </div>

            <?php if (!empty($software['advantages'])): ?>
                <div class="section">
                    <h2>Key Features & Advantages</h2>
                    <p><?= nl2br(htmlspecialchars($software['advantages'])) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($software['demo_link'])): ?>
                <div class="demo-section">
                    <h3 style="color: var(--green); margin-bottom: 10px;">Try It Out</h3>
                    <p>Experience this software firsthand with our live demo.</p>
                    <a href="<?= htmlspecialchars($software['demo_link']) ?>" target="_blank" class="demo-link">
                        <i class="fas fa-external-link-alt"></i> Open Demo
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../includes/cookie-consent.php"; ?>
</body>
</html>