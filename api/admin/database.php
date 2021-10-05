<?php
$servername = "localhost";
$username = "agramoni_pstuian_user";
$password = "uOjS*Q_N#c)?";
$dbName = 'agramoni_pstuian';
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
