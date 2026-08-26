<?php
$password_plain = 'admin123';
$hashed = password_hash($password_plain, PASSWORD_BCRYPT);
echo "Hashed password: $hashed";
?>
