<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

$contact = $conn->query("SELECT * FROM contact_section LIMIT 1")->fetch_assoc();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $form_enabled = isset($_POST['form_enabled']) ? 1 : 0;

    if ($contact) {
        $stmt = $conn->prepare("UPDATE contact_section SET address=?, email=?, phone=?, form_enabled=? WHERE id=?");
        $stmt->bind_param("sssii", $address, $email, $phone, $form_enabled, $contact['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_section (address, email, phone, form_enabled) VALUES (?,?,?,?)");
        $stmt->bind_param("sssi", $address, $email, $phone, $form_enabled);
        $stmt->execute();
    }

    header("Location: contact.php?ok=1");
    exit;
}
?>

<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Contact</title></head>
<body style="background:#040506;color:#fff;font-family:Inter,Arial;padding:20px">
<div style="max-width:820px;margin:0 auto">
    <h2>Edit Contact Section</h2>
    <?php if(isset($_GET['ok'])): ?><div style="color:#00ff88">Saved.</div><?php endif; ?>
    <form method="post">
        <label>Address</label><br>
        <input name="address" value="<?= htmlspecialchars($contact['address'] ?? '') ?>" style="width:100%;padding:10px;margin:6px 0"><br>
        <label>Email</label><br>
        <input name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>" style="width:100%;padding:10px;margin:6px 0"><br>
        <label>Phone</label><br>
        <input name="phone" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>" style="width:100%;padding:10px;margin:6px 0"><br>
        <label>
            <input type="checkbox" name="form_enabled" <?= !empty($contact['form_enabled']) ? 'checked' : '' ?>> Enable Contact Form
        </label><br><br>
        <button style="background:#00ff88;padding:10px;border:none;border-radius:8px;color:#040506;font-weight:700">Save</button>
    </form>
    <div style="margin-top:12px"><a href="dashboard.php">← back</a></div>
</div>
</body>
</html>
