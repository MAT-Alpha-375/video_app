<?php
$servername = "videoapplicationdb.database.windows.net";
$username = "Ahsan";
$password = "Alpha@718";
$dbname = "videoappdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}
