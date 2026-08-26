<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

$id = intval($_GET['id'] ?? 0);
$row = $conn->query("SELECT * FROM projects WHERE id = $id")->fetch_assoc();
if (!$row) {
    header("Location: projects.php");
    exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);

    $filename = $row['image'];
    if (!empty($_FILES['image']['name'])) {
        // upload new
        @unlink(__DIR__ . "/../uploads/projects/" . $row['image']);
        $img = $_FILES['image'];
        $filename = time() . "_" . preg_replace('/[^a-z0-9\-_\.]/i','',basename($img['name']));
        $target = __DIR__ . "/../uploads/projects/" . $filename;
        move_uploaded_file($img['tmp_name'], $target);
    }

    $stmt = $conn->prepare("UPDATE projects SET title = ?, category = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $category, $filename, $id);
    $stmt->execute();
    header("Location: projects.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Project</title></head>
<body style="background:#040506;color:#fff;font-family:Inter,Arial;padding:20px">
<div style="max-width:720px;margin:0 auto">
    <h2>Edit Project</h2>
    <?php if($err): ?><div style="color:#ff8b8b"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Title</label><br>
<input 
    name="title"
    value="<?= htmlspecialchars($row['title']) ?>"
    required
    style="width:100%;padding:10px;margin:6px 0;border:1px solid #00ff88;background:#0f1316;color:#cbd5df"
><br>

<label>Category</label><br>
<input 
    name="category"
    value="<?= htmlspecialchars($row['category']) ?>"
    style="width:100%;padding:10px;margin:6px 0;border:1px solid #00ff88;background:#0f1316;color:#cbd5df"
><br>

        <label>Current Image</label><br>
        <img src="../uploads/projects/<?=htmlspecialchars($row['image'])?>" style="width:240px;height:auto;border-radius:6px;display:block;margin:8px 0">
        <label>Replace Image (optional)</label><br><input type="file" name="image" accept="image/*" style="margin:6px 0"><br>
        <button style="background:#00ff88;padding:10px;border:none;border-radius:8px;color:#040506;font-weight:700">Update</button>
    </form>
    <div style="margin-top:12px"><a href="projects.php">← back</a></div>
</div>
</body>
</html>
