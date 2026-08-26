<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

// Fetch all messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Contact Messages — Admin</title>
<style>
body {background:#040506;color:var(--muted);font-family:Inter,Arial;margin:0;padding:20px;--muted:#cbd5df;}
.wrap {max-width:1100px;margin:0 auto;}
.row {display:flex;justify-content:space-between;align-items:center;padding:12px 10px;border-bottom:1px solid rgba(34,211,238,0.2);}
.row div {flex:1;}
a.btn {background:linear-gradient(90deg,#dc2626,#b91c1c);padding:6px 12px;border-radius:8px;color:#FFFFFF;text-decoration:none;font-weight:700;}
</style>
</head>
<body>
<div class="wrap">
    <h2>Contact Messages</h2>
    <?php if($messages->num_rows): ?>
        <?php while($m = $messages->fetch_assoc()): ?>
            <div class="row">
                <div><strong><?=htmlspecialchars($m['name'])?></strong> (<?=htmlspecialchars($m['email'])?>)</div>
                <div><?=htmlspecialchars($m['message'])?></div>
                <div><?=htmlspecialchars($m['created_at'])?></div>
                <div><a class="btn" href="contact_messages.php?delete=<?= $m['id'] ?>" onclick="return confirm('Delete this message?')">Delete</a></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php
// Handle deletion
if (!empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    header("Location: contact_messages.php");
    exit;
}
?>
