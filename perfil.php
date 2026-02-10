<?php
session_start();
require_once 'config.php';

// Verificar sesión
if (!isset($_SESSION['empleado_id'])) {
    header('Location: index.php');
    exit;
}

$empleado_id = $_SESSION['empleado_id'];

// Obtener datos del empleado
$stmt = $conn->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->bind_param("i", $empleado_id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();

// Procesar subida de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $upload_dir = 'uploads/fotos/';
    
    // Crear directorio si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES['foto'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Validar que sea una imagen
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if ($file_error === 0) {
        if (in_array($file_ext, $allowed_extensions)) {
            if ($file_size <= 5000000) { // 5MB máximo
                // Nombre único para el archivo
                $new_file_name = 'foto_' . $empleado['cedula'] . '_' . time() . '.' . $file_ext;
                $file_destination = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Eliminar foto anterior si existe
                    if (!empty($empleado['foto_perfil']) && file_exists($empleado['foto_perfil'])) {
                        unlink($empleado['foto_perfil']);
                    }
                    
                    // Actualizar en la base de datos
                    $stmt = $conn->prepare("UPDATE empleados SET foto_perfil = ? WHERE id = ?");
                    $stmt->bind_param("si", $file_destination, $empleado_id);
                    $stmt->execute();
                    
                    $success_message = "Foto actualizada exitosamente";
                    // Recargar datos
                    $stmt = $conn->prepare("SELECT * FROM empleados WHERE id = ?");
                    $stmt->bind_param("i", $empleado_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $empleado = $result->fetch_assoc();
                } else {
                    $error_message = "Error al subir la foto";
                }
            } else {
                $error_message = "La foto es demasiado grande (máximo 5MB)";
            }
        } else {
            $error_message = "Solo se permiten imágenes JPG, JPEG, PNG o GIF";
        }
    } else {
        $error_message = "Error al procesar la foto";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - <?php echo htmlspecialchars($empleado['nombre']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #333;
            font-size: 24px;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .profile-section {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            align-items: start;
        }

        .photo-upload {
            text-align: center;
        }

        .photo-preview {
            width: 250px;
            height: 250px;
            border-radius: 15px;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 4px solid #e0e0e0;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview .placeholder {
            font-size: 80px;
            color: #ccc;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.2s;
        }

        .file-input-label:hover {
            transform: translateY(-2px);
        }

        .upload-info {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        .info-grid {
            display: grid;
            gap: 20px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-weight: 700;
            color: #555;
            font-size: 14px;
        }

        .info-value {
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        @media (max-width: 768px) {
            .profile-section {
                grid-template-columns: 1fr;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Mi Perfil</h1>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="profile-section">
                <div class="photo-upload">
                    <div class="photo-preview" id="photoPreview">
                        <?php if (!empty($empleado['foto_perfil']) && file_exists($empleado['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($empleado['foto_perfil']); ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <div class="placeholder">👤</div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="file-input-wrapper">
                            <label for="foto" class="file-input-label">
                                📷 Subir Foto
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewAndUpload(this)">
                        </div>
                        <div class="upload-info">
                            Formatos: JPG, PNG, GIF<br>
                            Tamaño máximo: 5MB
                        </div>
                    </form>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Cédula:</div>
                        <div class="info-value"><?php echo htmlspecialchars($empleado['cedula']); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nombre Completo:</div>
                        <div class="info-value"><?php echo htmlspecialchars($empleado['nombre']); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Centro de Costo:</div>
                        <div class="info-value"><?php echo htmlspecialchars($empleado['centro_costo']); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Cargo:</div>
                        <div class="info-value"><?php echo htmlspecialchars($empleado['cargo']); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Estado:</div>
                        <div class="info-value">
                            <span class="status-badge"><?php echo strtoupper($empleado['estado']); ?></span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Fecha de Registro:</div>
                        <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($empleado['fecha_registro'])); ?></div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <a href="generar_pdf.php?id=<?php echo $empleado['id']; ?>" class="btn btn-primary" target="_blank">
                    📄 Descargar Tarjeta PDF
                </a>
            </div>
        </div>
    </div>

    <script>
        function previewAndUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('photoPreview').innerHTML = 
                        '<img src="' + e.target.result + '" alt="Foto de perfil">';
                }
                
                reader.readAsDataURL(input.files[0]);
                
                // Auto-enviar el formulario
                setTimeout(() => {
                    document.getElementById('uploadForm').submit();
                }, 500);
            }
        }
    </script>
</body>
</html>
