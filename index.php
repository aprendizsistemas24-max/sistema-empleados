<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Empleados - Inicio de Sesión</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #333;
            margin-bottom: 5px;
            font-size: 32px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab {
            flex: 1;
            padding: 12px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            color: #666;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .admin-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .admin-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-link a:hover {
            text-decoration: underline;
        }

        .password-toggle {
            position: relative;
        }

        .toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>👤 Sistema de Empleados</h1>
            <p>Bienvenido</p>
        </div>

        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'invalid') {
                echo '<div class="alert alert-error">Cédula o contraseña incorrecta.</div>';
            } elseif ($_GET['error'] == 'inactive') {
                echo '<div class="alert alert-error">Tu cuenta está inactiva. Contacta al administrador.</div>';
            }
        }
        if (isset($_GET['success']) && $_GET['success'] == '1') {
            echo '<div class="alert alert-success">¡Registro exitoso! Ya puedes iniciar sesión.</div>';
        }
        ?>

        <div class="tabs">
            <button class="tab active" onclick="switchTab('login')">Iniciar Sesión</button>
            <button class="tab" onclick="switchTab('register')">Registrarse</button>
        </div>

        <!-- Tab de Login -->
        <div id="login-tab" class="tab-content active">
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="cedula">Número de Cédula</label>
                    <input 
                        type="text" 
                        id="cedula" 
                        name="cedula" 
                        placeholder="Ejemplo: 1143453570" 
                        required
                        pattern="[0-9]+"
                        title="Solo números"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Tu contraseña"
                            required
                        >
                        <span class="toggle-icon" onclick="togglePassword('password')">👁️</span>
                    </div>
                </div>

                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>

        <!-- Tab de Registro -->
        <div id="register-tab" class="tab-content">
            <form action="registro.php" method="POST">
                <div class="form-group">
                    <label for="reg_cedula">Número de Cédula *</label>
                    <input 
                        type="text" 
                        id="reg_cedula" 
                        name="cedula" 
                        placeholder="Ejemplo: 1143453570" 
                        required
                        pattern="[0-9]+"
                    >
                </div>

                <div class="form-group">
                    <label for="reg_nombre">Nombre Completo *</label>
                    <input 
                        type="text" 
                        id="reg_nombre" 
                        name="nombre" 
                        placeholder="Tu nombre completo"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="reg_centro">Centro de Costo *</label>
                    <input 
                        type="text" 
                        id="reg_centro" 
                        name="centro_costo" 
                        placeholder="Ejemplo: BARRANQUILLA VENTAS"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="reg_cargo">Cargo *</label>
                    <input 
                        type="text" 
                        id="reg_cargo" 
                        name="cargo" 
                        placeholder="Tu cargo"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="reg_password">Contraseña *</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="reg_password" 
                            name="password" 
                            placeholder="Mínimo 6 caracteres"
                            required
                            minlength="6"
                        >
                        <span class="toggle-icon" onclick="togglePassword('reg_password')">👁️</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reg_password2">Confirmar Contraseña *</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="reg_password2" 
                            name="password2" 
                            placeholder="Repite tu contraseña"
                            required
                            minlength="6"
                        >
                        <span class="toggle-icon" onclick="togglePassword('reg_password2')">👁️</span>
                    </div>
                </div>

                <button type="submit">Registrarse</button>
            </form>
        </div>

        <div class="admin-link">
            <a href="admin.php">Acceso Administrador →</a>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Mostrar el tab seleccionado
            if (tabName === 'login') {
                document.getElementById('login-tab').classList.add('active');
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.getElementById('register-tab').classList.add('active');
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

        // Validar que las contraseñas coincidan
        document.querySelector('#register-tab form').addEventListener('submit', function(e) {
            const pass1 = document.getElementById('reg_password').value;
            const pass2 = document.getElementById('reg_password2').value;
            
            if (pass1 !== pass2) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
        });
    </script>
</body>
</html>
