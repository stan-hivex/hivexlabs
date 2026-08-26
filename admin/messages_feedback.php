<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

// Approve or reject feedback
// APPROVE FEEDBACK
if (!empty($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE feedback SET approved = 1, status = 'approved' WHERE id = $id");
    header("Location: messages_feedback.php");
    exit;
}

// REJECT FEEDBACK
if (!empty($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE feedback SET approved = 0, status = 'rejected' WHERE id = $id");
    header("Location: messages_feedback.php");
    exit;
}



// Fetch contact messages
$contacts = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
// Fetch feedback messages
$feedbacks = $conn->query("SELECT * FROM feedback GROUP BY name, email, message, rating ORDER BY created_at DESC");

?>

<div style="display:flex; gap:40px; flex-wrap:wrap">
    <!-- Contact Messages -->
    <div style="flex:1; min-width:300px">
        <h3>Contact Messages</h3>
        <?php while($c = $contacts->fetch_assoc()): ?>
            <div style="margin-bottom:10px; padding:8px; border:1px solid #333; border-radius:6px;">
                <strong><?=htmlspecialchars($c['name'])?></strong> (<?=htmlspecialchars($c['email'])?>)<br>
                <?=htmlspecialchars($c['message'])?><br>
                <small><?=htmlspecialchars($c['created_at'])?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Feedback Messages -->
    <div style="flex:1; min-width:300px">
        <h3>Feedback & Ratings</h3>
        <?php while($f = $feedbacks->fetch_assoc()): ?>
            <div style="margin-bottom:10px; padding:8px; border:1px solid #333; border-radius:6px; background:<?= $f['approved'] ? '#020' : '#220' ?>">
                <strong><?=htmlspecialchars($f['name'])?></strong> (<?=htmlspecialchars($f['email'])?>)<br>
                <?=htmlspecialchars($f['message'])?><br>
                Rating: <?=str_repeat("★",$f['rating']).str_repeat("☆",5-$f['rating'])?><br>
                <small><?=htmlspecialchars($f['created_at'])?></small><br>
                <?php if(!$f['approved']): ?>
                    <a href="?approve=<?=$f['id']?>" style="color:#0f0">Approve</a> |
                    <a href="?reject=<?=$f['id']?>" style="color:#f00" onclick="return confirm('Reject this feedback?')">Reject</a>
                <?php else: ?>
                    <span style="color:#0f0">Approved</span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
