<?php
require_once __DIR__ . "/../config/db.php";

if(isset($_POST['add_tool'])){
    $name = $_POST['name'];
    
    $logo = "uploads/" . basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], "../" . $logo);

    $stmt = $conn->prepare("INSERT INTO hero_tools (name, logo) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $logo);
    $stmt->execute();
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Tool Name" required>
    <input type="file" name="logo" required>
    <button type="submit" name="add_tool">Add Tool</button>
</form>
