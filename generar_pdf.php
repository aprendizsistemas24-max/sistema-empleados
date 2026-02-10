<?php
require_once 'config.php';

// Verificar si se proporcionó un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de empleado no proporcionado');
}

$id = intval($_GET['id']);

// Obtener datos del empleado
$stmt = $conn->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Empleado no encontrado');
}

$empleado = $result->fetch_assoc();

// Convertir foto a base64 si existe
$foto_base64 = '';
if (!empty($empleado['foto_perfil']) && file_exists($empleado['foto_perfil'])) {
    $image_data = file_get_contents($empleado['foto_perfil']);
    $image_type = pathinfo($empleado['foto_perfil'], PATHINFO_EXTENSION);
    $foto_base64 = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
}

// Generar HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjeta de Empleado</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 10px;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            max-width: 700px;
            margin: 0 auto;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #667eea;
        }
        .photo-container {
            flex-shrink: 0;
        }
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            border: 4px solid #667eea;
        }
        .no-photo {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #ccc;
            border: 4px solid #e0e0e0;
        }
        .profile-name {
            flex: 1;
        }
        .profile-name h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .profile-name .cedula {
            font-size: 16px;
            color: #666;
            font-weight: bold;
        }
        .section-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }
        .info-row {
            background: #f8f9fa;
            margin-bottom: 12px;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 180px;
            font-size: 14px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .status-activo {
            background: #d1fae5;
            color: #065f46;
        }
        .status-inactivo {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
        }
        .validation-text {
            color: #666;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 15px;
            text-align: justify;
        }
        .timestamp {
            color: #999;
            font-size: 11px;
            font-style: italic;
        }
        .document-id {
            color: #999;
            font-size: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 TARJETA DE EMPLEADO</h1>
        <p>Sistema de Gestión de Personal</p>
    </div>
    
    <div class="content">
        <div class="profile-header">
            <div class="photo-container">';

if ($foto_base64) {
    $html .= '<img src="' . $foto_base64 . '" alt="Foto de perfil" class="profile-photo">';
} else {
    $html .= '<div class="no-photo">👤</div>';
}

$html .= '
            </div>
            <div class="profile-name">
                <h2>' . htmlspecialchars($empleado['nombre']) . '</h2>
                <div class="cedula">Cédula: ' . htmlspecialchars($empleado['cedula']) . '</div>
            </div>
        </div>
        
        <h3 class="section-title">INFORMACIÓN PERSONAL</h3>
        
        <div class="info-row">
            <div class="info-label">Centro de Costo:</div>
            <div class="info-value">' . htmlspecialchars($empleado['centro_costo']) . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Cargo:</div>
            <div class="info-value">' . htmlspecialchars($empleado['cargo']) . '</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Estado:</div>
            <div class="info-value">
                <span class="status-badge status-' . $empleado['estado'] . '">' . strtoupper($empleado['estado']) . '</span>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Fecha de Registro:</div>
            <div class="info-value">' . date('d/m/Y H:i', strtotime($empleado['fecha_registro'])) . '</div>
        </div>
        
        <div class="footer">
            <h3 style="color: #667eea; font-size: 16px; margin-bottom: 10px;">VALIDACIÓN DEL DOCUMENTO</h3>
            <p class="validation-text">
                Este documento certifica que la persona identificada con la cédula mencionada es empleado 
                ' . ($empleado['estado'] === 'activo' ? 'activo' : 'registrado') . ' en nuestra organización. 
                La información aquí contenida es confidencial y de uso exclusivo para fines laborales autorizados.
            </p>
            <p class="timestamp">Documento generado el ' . date('d/m/Y H:i:s') . '</p>
            <p class="document-id">ID de registro: #' . str_pad($empleado['id'], 6, '0', STR_PAD_LEFT) . '</p>
        </div>
    </div>
</body>
</html>
';

// Configurar headers para descarga de PDF
header('Content-Type: text/html; charset=UTF-8');

echo $html;
echo '
<script>
// Auto-abrir ventana de impresión
window.onload = function() {
    // Preguntar si desea descargar como PDF
    if (confirm("¿Deseas descargar este documento como PDF?")) {
        window.print();
    } else {
        window.close();
    }
}
</script>
';
?>
