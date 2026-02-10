<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$cedula = trim($_POST['cedula']);
$nombre = trim($_POST['nombre']);
$centro_costo = trim($_POST['centro_costo']);
$cargo = trim($_POST['cargo']);
$password = $_POST['password'];
$password2 = $_POST['password2'];

// Validar que las contraseñas coincidan
if ($password !== $password2) {
    header('Location: index.php?error=password_mismatch');
    exit;
}

// Verificar si la cédula ya existe
$stmt = $conn->prepare("SELECT id FROM empleados WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Location: index.php?error=cedula_exists');
    exit;
}

// Hash de la contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insertar nuevo empleado
$stmt = $conn->prepare("INSERT INTO empleados (cedula, nombre, centro_costo, cargo, password, estado) VALUES (?, ?, ?, ?, ?, 'activo')");
$stmt->bind_param("sssss", $cedula, $nombre, $centro_costo, $cargo, $password_hash);

if ($stmt->execute()) {
    header('Location: index.php?success=1');
} else {
    header('Location: index.php?error=registration_failed');
}
exit;
?>
