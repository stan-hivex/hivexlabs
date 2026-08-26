<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

if (!empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $row = $conn->query("SELECT image FROM projects WHERE id=$id")->fetch_assoc();
    if ($row) {
        @unlink(__DIR__ . "/../uploads/projects/" . $row['image']);
        $conn->query("DELETE FROM projects WHERE id=$id");
    }
    header("Location: projects.php");
    exit;
}

$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Projects — Admin</title>
<style>
body{background:#040506;color:#cbd5df;font-family:Inter,Arial;padding:20px}
.wrap{max-width:1100px;margin:auto}
.row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid rgba(0,255,136,.2)}
.btn{background:#00ff88;color:#040506;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700}
</style>
</head>
<body>

<div class="wrap">
<h2>Projects <a class="btn" style="float:right" href="add_project.php">+ Add Project</a></h2>

<?php while($p=$projects->fetch_assoc()): ?>
<div class="row">
    <div style="display:flex;gap:12px;align-items:center">
        <img src="../uploads/projects/<?= htmlspecialchars($p['image']) ?>"
             style="width:80px;height:60px;object-fit:cover;border-radius:6px">
        <div>
            <strong><?= htmlspecialchars($p['title']) ?></strong><br>
            <small><?= htmlspecialchars($p['category']) ?></small>
        </div>
    </div>
    <div>
        <a class="btn" href="edit_project.php?id=<?= $p['id'] ?>">Edit</a>
        <a class="btn" href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete project?')">Delete</a>
    </div>
</div>
<?php endwhile; ?>

</div>
</body>
</html>
