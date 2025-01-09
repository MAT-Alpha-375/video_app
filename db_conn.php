<?php
$servername = "videoapplicationdb.database.windows.net";
$username = "Ahsan";
$password = "Alpha@718";
$dbname = "videoappdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

// try {
//     $conn = new PDO("sqlsrv:server = tcp:videoapplicationdb.database.windows.net,1433; Database = videoappdb", "Ahsan", "{your_password_here}");
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch (PDOException $e) {
//     print("Error connecting to SQL Server.");
//     die(print_r($e));
// }


// $connectionInfo = array("UID" => "Ahsan", "pwd" => "{your_password_here}", "Database" => "videoappdb", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
// $serverName = "tcp:videoapplicationdb.database.windows.net,1433";
// $conn = sqlsrv_connect($serverName, $connectionInfo);
?>