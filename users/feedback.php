<?php
ob_start(); // <-- add this to prevent accidental output
require_once __DIR__ . "/../config/db.php";
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    $rating = intval($_POST['rating']);

    $check = $conn->query("SELECT * FROM feedback 
        WHERE name='$name' AND email='$email' 
        AND message='$message' AND rating=$rating 
        AND created_at > NOW() - INTERVAL 5 MINUTE");

    if($check->num_rows === 0){
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message, rating, approved) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("sssi", $name, $email, $message, $rating);
        $stmt->execute();
    }

    header("Location: feedback.php?ok=1");
    exit;
}

// Fetch approved feedback
$approved_feedback = $conn->query("SELECT * FROM feedback WHERE approved = 1 ORDER BY id DESC");

ob_end_flush(); // end buffer
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Leave Feedback — HiveX Labs</title>
<style>
body{background:#040506;color:var(--muted);font-family:Inter,Arial;padding:20px;--muted:#cbd5df}
.container{display:flex;gap:30px;flex-wrap:wrap;max-width:1100px;margin:0 auto}
.left, .right{flex:1;min-width:300px}
input, textarea, select{width:100%;padding:10px;margin:6px 0;border-radius:6px;border:1px solid #00ebfa;background:#0f1316;color:#cbd5df;}
button{background: #00ebfa;padding:10px;border:none;border-radius:8px;color: #040506;font-weight:700;cursor:pointer}
.feedback-box{background:rgba(34,211,238,0.1);padding:12px;border-radius:8px;margin-bottom:12px;border:1px solid #22D3EE}
.feedback-box .name{font-weight:700;color: #020617}
.feedback-box .rating{color: #2563EB}
</style>
</head>
<body>
<div class="container">
    <!-- Left: Feedback form -->
    <div class="left">
        <h2>Leave Feedback & Rate Us</h2>
        <?php if($msg): ?><div style="color: #00ebfa;margin-bottom:10px"><?=htmlspecialchars($msg)?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Feedback" required></textarea>
            <label>Rating:</label>
            <select name="rating" required>
                <option value="">Select</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★</option>
                <option value="3">★★★</option>
                <option value="2">★★</option>
                <option value="1">★</option>
            </select>
            <button type="submit" name="feedback_submit">Submit Feedback</button>
        </form>
    </div>

    <!-- Right: Approved feedback -->
    <div class="right">
        <h2>What Our Clients Say</h2>
        <?php if($approved_feedback->num_rows): ?>
            <?php while($f = $approved_feedback->fetch_assoc()): ?>
                <div class="feedback-box">
                    <div class="name"><?=htmlspecialchars($f['name'])?></div>
                    <div class="rating"><?=str_repeat('★', $f['rating'])?></div>
                    <div class="message"><?=nl2br(htmlspecialchars($f['message']))?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
