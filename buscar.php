<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';

if (empty($cedula)) {
    header('Location: index.php?error=1');
    exit;
}

// Buscar empleado en la base de datos
$stmt = $conn->prepare("SELECT * FROM empleados WHERE cedula = ? AND estado = 'activo'");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php?error=1');
    exit;
}

$empleado = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Empleado</title>
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
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .employee-info {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
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

        .status-inactive {
            background: #ef4444;
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            min-width: 200px;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .info-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>📋 Tarjeta de Empleado</h1>
                <p>Información registrada en el sistema</p>
            </div>

            <div class="employee-info">
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
                        <span class="status-badge <?php echo $empleado['estado'] !== 'activo' ? 'status-inactive' : ''; ?>">
                            <?php echo strtoupper($empleado['estado']); ?>
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Fecha de Registro:</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($empleado['fecha_registro'])); ?></div>
                </div>
            </div>

            <div class="actions">
                <a href="generar_pdf.php?id=<?php echo $empleado['id']; ?>" class="btn btn-primary">
                    📄 Descargar Tarjeta PDF
                </a>
                <a href="index.php" class="btn btn-secondary">
                    ← Volver a Consultar
                </a>
            </div>
        </div>
    </div>
</body>
</html>
