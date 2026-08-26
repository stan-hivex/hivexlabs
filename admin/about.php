<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php"; // only logged-in admins

$success = '';
$err = '';

// Fetch current about content
$result = $conn->query("SELECT * FROM about LIMIT 1");
$about = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $conn->real_escape_string($_POST['content']);

    if (empty($content)) {
        $err = "About content cannot be empty.";
    } else {
        if ($about) {
            // Update existing
            $conn->query("UPDATE about SET content='$content' WHERE id={$about['id']}");
        } else {
            // Insert new
            $conn->query("INSERT INTO about (content) VALUES ('$content')");
        }
        $success = "About section updated successfully!";
        // Refresh $about
        $about['content'] = $content;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit About — Admin</title>
<style>
body{background:#040506;color:var(--muted);font-family:Inter,Arial;padding:20px;--muted:#cbd5df;}
textarea{width:100%;padding:12px;margin-bottom:12px;border-radius:8px;border:1px solid #00ff88;background:#0f1316;color:#cbd5df;resize:vertical;}
button{padding:12px 20px;background:#00ff88;color:#040506;border:none;border-radius:8px;font-weight:700;cursor:pointer;}
</style>
</head>
<body>
<h2>Edit About Section</h2>

<?php if($err) echo "<p style='color:#dc2626;'>$err</p>"; ?>
<?php if($success) echo "<p style='color:#16a34a;'>$success</p>"; ?>

<form method="post">
    <textarea name="content" rows="8" placeholder="Write about your company..."><?= htmlspecialchars($about['content'] ?? '') ?></textarea>
    <button type="submit">Save Changes</button>
</form>

<div style="margin-top:12px"><a href="dashboard.php">← Back to Dashboard</a></div>
</body>
</html>
