<?php
require_once __DIR__ . "/../config/db.php";

// Handle form submission
if(isset($_POST['save_term'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    // If editing existing term
    if(isset($_POST['id']) && !empty($_POST['id'])){
        $stmt = $conn->prepare("UPDATE terms_of_service SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $_POST['id']);
        $stmt->execute();
    } else { // Adding new term
        $stmt = $conn->prepare("INSERT INTO terms_of_service (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
    }
}

// Delete term
if(isset($_GET['delete'])){
    $stmt = $conn->prepare("DELETE FROM terms_of_service WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
}

// Fetch all terms
$terms = $conn->query("SELECT * FROM terms_of_service ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>

<h2>Manage Terms of Service</h2>

<form method="post">
    <input type="hidden" name="id" value="">
    <input type="text" name="title" placeholder="Term Title" required><br><br>
    <textarea name="content" placeholder="Term Content" rows="5" required></textarea><br><br>
    <button type="submit" name="save_term">Save Term</button>
</form>

<hr>

<h3>Existing Terms</h3>
<?php foreach($terms as $term): ?>
    <div style="margin-bottom:20px;">
        <strong><?= htmlspecialchars($term['title']) ?></strong>
        <p><?= nl2br(htmlspecialchars($term['content'])) ?></p>
        <a href="?delete=<?= $term['id'] ?>" style="color:red;">Delete</a>
        <!-- Optional: Add an edit feature -->
    </div>
<?php endforeach; ?>
