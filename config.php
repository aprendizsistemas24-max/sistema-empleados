<?php
// Usar las variables de entorno que ya configuraste en Railway
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQL_DATABASE');
$port = getenv('MYSQLPORT');

// Conexión corregida para producción
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    // Esto te ayudará a ver el error real si vuelve a fallar
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
