<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

// Add service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = $conn->real_escape_string($_POST['service_name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $conn->query("INSERT INTO services (service_name, description) VALUES ('$name','$desc')");
    header("Location: services.php");
    exit;
}

// Delete
if (!empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM services WHERE id = $id");
    header("Location: services.php");
    exit;
}

$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Services — Admin</title></head>
<body style="background:#040506;color:var(--muted);font-family:Inter,Arial;padding:20px;--muted:#cbd5df">
<div style="max-width:1100px;margin:0 auto">
    <h2>Services</h2>

    <div style="display:flex;gap:18px">
        <div style="flex:1">
            <?php if($services->num_rows): ?>
                <?php while($s = $services->fetch_assoc()): ?>
                    <div style="background:rgba(0,255,136,0.05);padding:12px;border-radius:8px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center">
                        <div>
                            <div style="font-weight:700"><?=htmlspecialchars($s['service_name'])?></div>
                            <div style="color:#9fb6c2;font-size:13px"><?=htmlspecialchars($s['description'])?></div>
                        </div>
                        <div style="display:flex;gap:8px">
                            <a href="edit_service.php?id=<?= $s['id'] ?>" style="background:#00ff88;padding:8px;border-radius:8px;color:#040506;text-decoration:none">Edit</a>
                            <a href="services.php?delete=<?= $s['id'] ?>" style="background:#dc2626;padding:8px;border-radius:8px;color:#FFFFFF;text-decoration:none" onclick="return confirm('Delete service?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No services yet.</p>
            <?php endif; ?>
        </div>

        <div style="width:320px">
            <h3>Add Service</h3>
            <form method="post">
                <input name="service_name" placeholder="Service name" required style="width:100%;padding:10px;margin:6px 0"><br>
                <textarea name="description" placeholder="Description" style="width:100%;padding:10px;margin:6px 0"></textarea><br>
                <button name="add_service" style="background:#00ff88;padding:10px;border:none;border-radius:8px;color:#040506;font-weight:700">Add</button>
            </form>
        </div>
    </div>

    <div style="margin-top:12px"><a href="dashboard.php">← back</a></div>
</div>
</body>
</html>
