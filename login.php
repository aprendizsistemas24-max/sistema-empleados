<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$cedula = trim($_POST['cedula']);
$password = $_POST['password'];

// Buscar empleado
$stmt = $conn->prepare("SELECT * FROM empleados WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php?error=invalid');
    exit;
}

$empleado = $result->fetch_assoc();

// Verificar contraseña
if (!password_verify($password, $empleado['password'])) {
    header('Location: index.php?error=invalid');
    exit;
}

// Verificar estado
if ($empleado['estado'] !== 'activo') {
    header('Location: index.php?error=inactive');
    exit;
}

// Iniciar sesión
$_SESSION['empleado_id'] = $empleado['id'];
$_SESSION['empleado_cedula'] = $empleado['cedula'];
$_SESSION['empleado_nombre'] = $empleado['nombre'];

// Redirigir al perfil
header('Location: perfil.php');
exit;
?>
