<?php
$c = new mysqli('localhost', 'root', '', 'hivex-labs');
if ($c->connect_error) {
    echo "CONNECT_ERR: " . $c->connect_error . "\n";
    exit(1);
}
$result = $c->query('SELECT product_key, title, hero_image FROM product_content');
if (! $result) {
    echo "QUERY_ERR: " . $c->error . "\n";
    exit(1);
}
while ($row = $result->fetch_assoc()) {
    echo json_encode($row, JSON_UNESCAPED_SLASHES) . "\n";
}
