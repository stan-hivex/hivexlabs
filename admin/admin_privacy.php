<?php
require_once __DIR__ . "/../config/db.php";

// Save policy
if(isset($_POST['save_policy'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO privacy_policy (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_privacy.php?success=1");
    exit();
}

// Fetch existing
$policies = $conn->query("SELECT * FROM privacy_policy ORDER BY id ASC");
?>

<h2>Add Privacy Policy Section</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Section Title" required><br><br>

    <textarea name="content" rows="6" placeholder="Policy Content" required></textarea><br><br>

    <button type="submit" name="save_policy">Save Policy</button>
</form>

<hr>

<h2>Existing Privacy Policies</h2>

<?php while($row = $policies->fetch_assoc()): ?>
    <div>
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
    </div>
<?php endwhile; ?>
