<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

$success = "";

// SAVE MEMBER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $profession = $_POST['profession'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $achievements = $_POST['achievements'];
    $skills = $_POST['skills'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $linkedin = $_POST['linkedin'];
    $github = $_POST['github'];
    $twitter = $_POST['twitter'];

    // PHOTO UPLOAD
    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $photo = "uploads/team_" . time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . "/../" . $photo);
    }

    $stmt = $conn->prepare("
        INSERT INTO team_member 
        (name, profession, photo, bio, experience, achievements, skills, email, phone, linkedin, github, twitter)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssssssss",
        $name,$profession,$photo,$bio,$experience,$achievements,$skills,
        $email,$phone,$linkedin,$github,$twitter
    );

    $stmt->execute();
    $success = "Team member added successfully!";
}

// FETCH MEMBERS
$members = $conn->query("SELECT * FROM team_member ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Team Management</title>
<style>
body{background:#040506;color:var(--muted);font-family:Arial;padding:20px;--muted:#cbd5df}
h1{color:#00ff88}
form{background:#0f1316;padding:20px;border-radius:12px;margin-bottom:40px;border:1px solid #00ff88}
input,textarea{
    width:100%;padding:12px;margin-bottom:12px;
    background:#0f1316;border:1px solid #00ff88;border-radius:8px;color:#cbd5df
}
button{background:#00ff88;color:#040506;padding:12px 20px;border:none;border-radius:8px;font-weight:bold}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.card{background:#0f1316;padding:15px;border-radius:12px;border:1px solid #00ff88}
.card img{width:100%;border-radius:8px}
</style>
</head>

<body>

<h1>Team Members</h1>

<?php if($success): ?><p style="color:#0f0"><?= $success ?></p><?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input name="name" placeholder="Full Name" required>
    <input name="profession" placeholder="Profession" required>
    <input type="file" name="photo" required>

    <textarea name="bio" placeholder="Bio"></textarea>
    <textarea name="experience" placeholder="Experience"></textarea>
    <textarea name="achievements" placeholder="What they've done"></textarea>
    <textarea name="skills" placeholder="Skills"></textarea>

    <input name="email" placeholder="Email">
    <input name="phone" placeholder="Phone">
    <input name="linkedin" placeholder="LinkedIn URL">
    <input name="github" placeholder="GitHub URL">
    <input name="twitter" placeholder="Twitter URL">

    <button>Add Team Member</button>
</form>

<h2>Existing Members</h2>
<div class="grid">
<?php while($m = $members->fetch_assoc()): ?>
    <div class="card">
        <img src="../<?= $m['photo'] ?>">
        <h3><?= htmlspecialchars($m['name']) ?></h3>
        <p><?= htmlspecialchars($m['profession']) ?></p>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
