<?php
require_once 'config.php';

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'registrar') {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $centro_costo = trim($_POST['centro_costo']);
        $cargo = trim($_POST['cargo']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $estado = $_POST['estado'];

        $stmt = $conn->prepare("INSERT INTO empleados (cedula, nombre, centro_costo, cargo, password, estado) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $cedula, $nombre, $centro_costo, $cargo, $password, $estado);
        
        if ($stmt->execute()) {
            $success = "Empleado registrado exitosamente";
        } else {
            $error = "Error al registrar: " . $conn->error;
        }
    } elseif ($_POST['action'] === 'actualizar') {
        $id = $_POST['id'];
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $centro_costo = trim($_POST['centro_costo']);
        $cargo = trim($_POST['cargo']);
        $estado = $_POST['estado'];

        // Si se proporciona nueva contraseña
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE empleados SET cedula=?, nombre=?, centro_costo=?, cargo=?, password=?, estado=? WHERE id=?");
            $stmt->bind_param("ssssssi", $cedula, $nombre, $centro_costo, $cargo, $password, $estado, $id);
        } else {
            $stmt = $conn->prepare("UPDATE empleados SET cedula=?, nombre=?, centro_costo=?, cargo=?, estado=? WHERE id=?");
            $stmt->bind_param("sssssi", $cedula, $nombre, $centro_costo, $cargo, $estado, $id);
        }
        
        if ($stmt->execute()) {
            $success = "Empleado actualizado exitosamente";
        } else {
            $error = "Error al actualizar: " . $conn->error;
        }
    } elseif ($_POST['action'] === 'eliminar') {
        $id = $_POST['id'];
        
        // Obtener foto antes de eliminar
        $stmt = $conn->prepare("SELECT foto_perfil FROM empleados WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $empleado = $result->fetch_assoc();
        
        // Eliminar foto si existe
        if (!empty($empleado['foto_perfil']) && file_exists($empleado['foto_perfil'])) {
            unlink($empleado['foto_perfil']);
        }
        
        // Eliminar empleado
        $stmt = $conn->prepare("DELETE FROM empleados WHERE id=?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success = "Empleado eliminado exitosamente";
        } else {
            $error = "Error al eliminar: " . $conn->error;
        }
    }
}

// Obtener todos los empleados
$result = $conn->query("SELECT * FROM empleados ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 28px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 22px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        input, select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
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

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
            padding: 8px 16px;
            font-size: 12px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
        }

        thead {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        tbody {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            font-size: 14px;
            color: #1f2937;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-activo {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactivo {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .back-link {
            background: white;
            color: #667eea;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .employee-photo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍💼 Panel de Administración</h1>
            <a href="index.php" class="back-link">← Volver a Inicio</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>➕ Registrar Nuevo Empleado</h2>
            <form method="POST">
                <input type="hidden" name="action" value="registrar">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Cédula *</label>
                        <input type="text" name="cedula" required pattern="[0-9]+" title="Solo números">
                    </div>
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label>Centro de Costo *</label>
                        <input type="text" name="centro_costo" required>
                    </div>
                    <div class="form-group">
                        <label>Cargo *</label>
                        <input type="text" name="cargo" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña *</label>
                        <input type="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Empleado</button>
            </form>
        </div>

        <div class="card">
            <h2>📋 Lista de Empleados Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 6%;">Foto</th>
                        <th style="width: 8%;">ID</th>
                        <th style="width: 12%;">Cédula</th>
                        <th style="width: 20%;">Nombre</th>
                        <th style="width: 16%;">Centro Costo</th>
                        <th style="width: 13%;">Cargo</th>
                        <th style="width: 10%;">Estado</th>
                        <th style="width: 15%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['foto_perfil']) && file_exists($row['foto_perfil'])): ?>
                                <img src="<?php echo htmlspecialchars($row['foto_perfil']); ?>" alt="Foto" class="employee-photo">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px;">👤</div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['centro_costo']); ?></td>
                        <td><?php echo htmlspecialchars($row['cargo']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $row['estado']; ?>">
                                <?php echo ucfirst($row['estado']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-warning" onclick="editarEmpleado(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    ✏️ Editar
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este empleado?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>✏️ Editar Empleado</h2>
            <form method="POST">
                <input type="hidden" name="action" value="actualizar">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Cédula *</label>
                    <input type="text" name="cedula" id="edit_cedula" required pattern="[0-9]+">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nombre Completo *</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Centro de Costo *</label>
                    <input type="text" name="centro_costo" id="edit_centro_costo" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Cargo *</label>
                    <input type="text" name="cargo" id="edit_cargo" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nueva Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" id="edit_password" minlength="6" placeholder="Mínimo 6 caracteres">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Estado *</label>
                    <select name="estado" id="edit_estado" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editarEmpleado(empleado) {
            document.getElementById('edit_id').value = empleado.id;
            document.getElementById('edit_cedula').value = empleado.cedula;
            document.getElementById('edit_nombre').value = empleado.nombre;
            document.getElementById('edit_centro_costo').value = empleado.centro_costo;
            document.getElementById('edit_cargo').value = empleado.cargo;
            document.getElementById('edit_estado').value = empleado.estado;
            document.getElementById('edit_password').value = '';
            
            document.getElementById('editModal').classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
</body>
</html>
