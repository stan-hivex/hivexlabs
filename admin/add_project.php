<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $title    = $_POST['title'];
    $category = $_POST['category'];

    // Optional link
    $link = !empty($_POST['link']) ? $_POST['link'] : null;

    // Image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            __DIR__ . "/../uploads/projects/" . $image
        );
    }

    $stmt = $conn->prepare(
        "INSERT INTO projects (title, category, link, image) VALUES (?,?,?,?)"
    );
    $stmt->bind_param("ssss", $title, $category, $link, $image);

    $stmt->execute();
    header("Location: projects.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Project</title>
<style>
body{background:#040506;color:#cbd5df;font-family:Inter}
form{max-width:480px;margin:40px auto}
input,select{
    width:100%;
    padding:12px;
    margin-bottom:14px;
    border-radius:8px;
    border:none
}
button{
    background:#00ff88;
    color:#040506;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:700
}
small{color:#8aa3af}
</style>
</head>

<body>
<form method="post" enctype="multipart/form-data">
<h2>Add Project</h2>

<input name="title" placeholder="Project title" required>

<select name="category" required>
    <option value="">Select Category</option>
    <option>Graphic Design</option>
    <option>Branding</option>
    <option>Web Design</option>
    <option>Motion / UI Design</option>
    <option>Software Engineering</option>
</select>

<input name="link" type="url" placeholder="Project link (optional)">
<small>Leave empty if project has no live link</small>

<input type="file" name="image" accept="image/*">

<button>Save Project</button>
</form>
</body>
</html>

