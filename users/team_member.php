<?php
require_once __DIR__ . "/../config/db.php";
$id = (int)$_GET['id'];
$m = $conn->query("SELECT * FROM team_member WHERE id=$id")->fetch_assoc();
if(!$m) die("Member not found");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $m['name'] ?> | Team</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    background:#040506;
    color:#fff;
    font-family:Inter, Arial, sans-serif;
}
.container{
    max-width:1000px;
    margin:0 auto;
    padding:60px 6%;
}
.card{
    background:#0f1316;
    border-radius:14px;
    padding:30px;
    border:1px solid rgba(0,255,136,0.2);
}
.header{
    display:flex;
    gap:30px;
    flex-wrap:wrap;
    margin-bottom:30px;
}
.header img{
    width:260px;
    height:260px;
    object-fit:cover;
    border-radius:12px;
}
.header-info h1{
    margin:0;
    font-size:32px;
}
.header-info p{
    margin:8px 0 0;
    color:#00ff88;
    font-weight:700;
    font-size:18px;
}
.section{
    margin-top:30px;
}
.section h3{
    color:#00ff88;
    text-transform:uppercase;
    font-size:14px;
    letter-spacing:1.5px;
    margin-bottom:8px;
}
.section p{
    color:#cbd5df;
    line-height:1.7;
}
.socials{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-top:20px;
}
.socials span{
    background:rgba(0,255,136,0.12);
    padding:10px 14px;
    border-radius:10px;
    font-weight:600;
}
.back{
    display:inline-block;
    margin-bottom:20px;
    color:#00ff88;
    text-decoration:none;
    font-weight:700;
}
.back:hover{text-decoration:underline;}

@media(max-width:700px){
    .header{flex-direction:column;align-items:center;text-align:center;}
}
</style>
</head>

<body>

<div class="container">

    <a href="team.php" class="back">← Back to Team</a>

    <div class="card">

        <div class="header">
            <img src="../<?= $m['photo'] ?>" alt="<?= $m['name'] ?>">
            <div class="header-info">
                <h1><?= $m['name'] ?></h1>
                <p><?= $m['profession'] ?></p>
            </div>
        </div>

        <div class="section">
            <p><?= nl2br($m['bio']) ?></p>
        </div>

        <div class="section">
            <h3>Experience</h3>
            <p><?= nl2br($m['experience']) ?></p>
        </div>

        <div class="section">
            <h3>Achievements</h3>
            <p><?= nl2br($m['achievements']) ?></p>
        </div>

        <div class="section">
            <h3>Skills</h3>
            <p><?= nl2br($m['skills']) ?></p>
        </div>

        <div class="section">
            <h3>Contact</h3>
            <div class="socials">
                <span>Email: <?= $m['email'] ?></span>
                <span>GitHub: <?= $m['github'] ?></span>
                <span>LinkedIn: <?= $m['linkedin'] ?></span>
                <span>Twitter: <?= $m['twitter'] ?></span>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . "/../includes/cookie-consent.php"; ?>
</body>
</html>
