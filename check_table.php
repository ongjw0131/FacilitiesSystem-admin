<?php
require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=assignment', 'root', '');
$result = $pdo->query('DESCRIBE society_user');

echo "Table Structure:\n";
echo str_pad("Field", 20) . " | " . str_pad("Type", 30) . " | " . str_pad("Null", 5) . "\n";
echo str_repeat("-", 70) . "\n";

while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo str_pad($row['Field'], 20) . " | " . str_pad($row['Type'], 30) . " | " . str_pad($row['Null'], 5) . "\n";
}
?>
