<?php
require_once 'app/config/Database.php';
require_once 'app/config/Config.php';

$db = new Database();
$result = $db->fetchAll('DESCRIBE employees');

echo "Employees table structure:\n";
foreach($result as $row) {
    echo "Column: " . $row['Field'] . " - Type: " . $row['Type'] . "\n";
}
