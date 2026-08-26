<?php
require_once __DIR__ . "/../config/db.php";

$msg = '';

// Fetch existing settings
$result = $conn->query("SELECT * FROM site_settings LIMIT 1");
$settings = $result && $result->num_rows ? $result->fetch_assoc() : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $site_name = $conn->real_escape_string($_POST['site_name'] ?? '');

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $logo_name = time() . '_' . $_FILES['logo']['name'];
        $logo_folder = __DIR__ . '/../uploads/'; // actual file path on server
if (!file_exists($logo_folder)) { mkdir($logo_folder, 0777, true); }

$logo_path = $logo_folder . $logo_name;
move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);

// Save relative path in DB
$logo_db = 'uploads/' . $logo_name;

    } else {
        $logo_name = $settings['logo'] ?? 'uploads/default-logo.png';
    }

    if ($settings) {
    // UPDATE existing record
    $stmt = $conn->prepare("UPDATE site_settings SET site_name=?, logo=? WHERE id=?");
    $stmt->bind_param("ssi", $site_name, $logo_db, $settings['id']);
} else {
    // INSERT new record
    $stmt = $conn->prepare("INSERT INTO site_settings (site_name, logo) VALUES (?,?)");
    $stmt->bind_param("ss", $site_name, $logo_db); // only 2 variables
}





    if ($stmt->execute()) {
        $msg = "Settings saved successfully!";
        header("Location: header_settings.php"); exit;
    } else {
        $msg = "Failed to save settings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Header Settings</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f4f4;padding:30px;}
form{background:#fff;padding:20px;border-radius:12px;max-width:500px;margin:auto;}
input, button{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc;}
button{background:#3b82f6;color:#fff;border:none;cursor:pointer;}
.msg{color:green;margin-bottom:12px;}
img{margin-top:10px;height:60px;}
</style>
</head>
<body>

<h2>Header Settings</h2>
<?php if($msg) echo "<div class='msg'>{$msg}</div>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Site Name</label>
    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>

    <label>Logo</label>
    <input type="file" name="logo">
    <?php if(!empty($settings['logo'])): ?>
        <img src="../<?= $settings['logo'] ?>" alt="Logo">
    <?php endif; ?>

    <button type="submit">Save Settings</button>
</form>

</body>
</html>
