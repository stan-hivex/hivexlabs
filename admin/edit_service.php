<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

$id = intval($_GET['id'] ?? 0);
$row = $conn->query("SELECT * FROM services WHERE id = $id")->fetch_assoc();
if (!$row) { header("Location: services.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['service_name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $stmt = $conn->prepare("UPDATE services SET service_name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $desc, $id);
    $stmt->execute();
    header("Location: services.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Service</title></head>
<body style="background:#040506;color:#fff;font-family:Inter,Arial;padding:20px">
<div style="max-width:720px;margin:0 auto">
    <h2>Edit Service</h2>
    <form method="post">
        <input name="service_name" value="<?=htmlspecialchars($row['service_name'])?>" required style="width:100%;padding:10px;margin:6px 0;border:1px solid #00ff88;background:#0f1316;color:#cbd5df"><br>
        <textarea name="description" style="width:100%;padding:10px;margin:6px 0;border:1px solid #00ff88;background:#0f1316;color:#cbd5df"><?=htmlspecialchars($row['description'])?></textarea><br>
        <button style="background:#00ff88;padding:10px;border:none;border-radius:8px;color:#040506;font-weight:700">Update</button>
    </form>
    <div style="margin-top:12px"><a href="services.php">← back</a></div>
</div>
</body>
</html>
