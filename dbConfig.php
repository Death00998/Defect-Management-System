<?php
$host = 'your_db_host';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

$db = new mysqli($host, $username, $password, $database);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
