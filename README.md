# Sistema de Gestión de Empleados v2.0 🚀

Sistema completo en PHP con autenticación, registro de empleados, subida de fotos de perfil y generación de tarjetas PDF personalizadas.

## ✨ Nuevas Características v2.0

- ✅ **Sistema de Login**: Los empleados pueden iniciar sesión con su cédula y contraseña
- ✅ **Autoregistro**: Los empleados pueden registrarse por sí mismos
- ✅ **Foto de Perfil**: Subida y previsualización de foto de perfil
- ✅ **Foto en PDF**: La tarjeta PDF incluye la foto del empleado
- ✅ **Gestión de contraseñas**: Contraseñas hasheadas de forma segura
- ✅ **Sesiones de usuario**: Sistema completo de autenticación
- ✅ **Panel de perfil personal**: Cada empleado tiene su propio perfil

## 📋 Características Completas

### Para Empleados:
- 🔐 Inicio de sesión con cédula y contraseña
- 📝 Registro de nueva cuenta
- 👤 Subida de foto de perfil con previsualización
- 📄 Generación de tarjeta PDF con foto incluida
- 🔒 Sesión personal segura

### Para Administradores:
- ✏️ Registro de empleados (con contraseña)
- 📊 Vista de todos los empleados con fotos
- 🔄 Actualización de datos
- 🔑 Cambio de contraseñas
- 🗑️ Eliminación de empleados
- 📷 Visualización de fotos en la lista

## 🚀 Instalación

### Requisitos previos:
- Servidor web (Apache/Nginx)
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Extensión MySQLi habilitada
- Extensión GD habilitada (para manejo de imágenes)

### Pasos de instalación:

1. **Copiar los archivos al servidor web**
   ```bash
   # Copiar todos los archivos a la carpeta del servidor web
   # Ejemplo en XAMPP: C:\xampp\htdocs\sistema-empleados\
   # Ejemplo en Linux: /var/www/html/sistema-empleados/
   ```

2. **Configurar permisos de la carpeta uploads**
   ```bash
   # En Linux/Mac:
   chmod -R 777 uploads/fotos/
   
   # En Windows:
   # Click derecho en la carpeta -> Propiedades -> Seguridad
   # Dar permisos de escritura al usuario del servidor web
   ```

3. **Crear la base de datos**
   - Abrir phpMyAdmin o consola MySQL
   - Importar el archivo `database.sql`
   
   El script creará:
   - Base de datos `sistema_empleados`
   - Tabla `empleados` con campos para foto y contraseña
   - Empleados de ejemplo con contraseña: **123456**

4. **Configurar la conexión a la base de datos**
   Editar el archivo `config.php` con tus credenciales:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'tu_usuario');
   define('DB_PASS', 'tu_contraseña');
   define('DB_NAME', 'sistema_empleados');
   ```

5. **Acceder al sistema**
   - Página principal: `http://localhost/sistema-empleados/`
   - Panel de administración: `http://localhost/sistema-empleados/admin.php`

## 📁 Estructura de archivos

```
sistema-empleados/
│
├── config.php              # Configuración de base de datos
├── database.sql            # Script SQL (ACTUALIZADO con password y foto)
├── index.php               # Login y registro
├── login.php               # Procesa el login
├── registro.php            # Procesa el registro
├── perfil.php              # Perfil del empleado (con subida de foto)
├── logout.php              # Cerrar sesión
├── admin.php               # Panel de administración
├── generar_pdf.php         # Genera tarjeta PDF con foto
├── .htaccess               # Configuración de seguridad
├── README.md               # Este archivo
│
└── uploads/
    └── fotos/              # Carpeta para fotos de perfil
        └── (fotos se guardan aquí)
```

## 🎯 Flujo de Uso

### Para Empleados Nuevos:

1. **Registrarse**:
   - Ir a la página principal
   - Click en "Registrarse"
   - Llenar formulario con:
     - Cédula
     - Nombre completo
     - Centro de costo
     - Cargo
     - Contraseña (mínimo 6 caracteres)
   - Click en "Registrarse"

2. **Iniciar Sesión**:
   - Ingresar cédula y contraseña
   - Click en "Iniciar Sesión"

3. **Subir Foto**:
   - Una vez en el perfil, click en "📷 Subir Foto"
   - Seleccionar imagen (JPG, PNG, GIF)
   - La foto se previsualizará y subirá automáticamente

4. **Descargar Tarjeta PDF**:
   - Click en "📄 Descargar Tarjeta PDF"
   - El PDF se abrirá con la foto incluida
   - Usar Ctrl+P (Cmd+P en Mac) y "Guardar como PDF"

### Para Empleados Existentes:

1. **Login**:
   - Cédula: Tu número de cédula
   - Contraseña: **123456** (contraseña por defecto para datos de ejemplo)
   
2. **Cambiar foto y descargar PDF**:
   - Seguir pasos 3 y 4 de arriba

### Para Administradores:

1. **Acceder al panel**:
   - Ir a `admin.php`

2. **Registrar empleado**:
   - Llenar formulario incluyendo contraseña
   - Click en "Registrar Empleado"

3. **Editar empleado**:
   - Click en "✏️ Editar" en la fila del empleado
   - Modificar datos
   - Opcionalmente cambiar contraseña
   - Click en "Guardar Cambios"

4. **Eliminar empleado**:
   - Click en "🗑️ Eliminar"
   - Confirmar eliminación
   - La foto del empleado también se eliminará

## 🔐 Seguridad

### Contraseñas:
- ✅ Hash usando `password_hash()` con bcrypt
- ✅ Verificación segura con `password_verify()`
- ✅ Mínimo 6 caracteres requeridos
- ✅ Nunca se almacenan en texto plano

### Sesiones:
- ✅ Inicio de sesión obligatorio para ver perfil
- ✅ IDs de sesión únicos por usuario
- ✅ Cierre de sesión seguro

### Subida de archivos:
- ✅ Solo se permiten imágenes (JPG, PNG, GIF)
- ✅ Tamaño máximo: 5MB
- ✅ Nombres únicos para evitar sobrescritura
- ✅ Validación de tipo MIME

### Base de datos:
- ✅ Prepared statements contra SQL injection
- ✅ htmlspecialchars contra XSS
- ✅ Validación de entrada de datos

## 📷 Gestión de Fotos

### Formatos soportados:
- JPG / JPEG
- PNG
- GIF

### Tamaño:
- Máximo: 5MB por foto
- Recomendado: 500KB - 1MB para mejor rendimiento

### Almacenamiento:
- Ubicación: `uploads/fotos/`
- Nombre del archivo: `foto_{cedula}_{timestamp}.{extension}`
- Ejemplo: `foto_1143453570_1706918273.jpg`

### Eliminación:
- Al eliminar un empleado, su foto se borra automáticamente
- Al subir nueva foto, la anterior se reemplaza

## 🎨 Personalización

### Cambiar Colores:
Los colores principales están en gradiente:
- Color primario: `#667eea`
- Color secundario: `#764ba2`

Buscar y reemplazar estos códigos en todos los archivos PHP.

### Modificar Tamaño de Foto en PDF:
En `generar_pdf.php`, línea con `.profile-photo`:
```css
.profile-photo {
    width: 150px;  /* Cambiar este valor */
    height: 150px; /* Y este */
    border-radius: 10px;
    object-fit: cover;
}
```

### Cambiar Tamaño Máximo de Foto:
En `perfil.php`, buscar:
```php
if ($file_size <= 5000000) { // 5MB en bytes
```

### Agregar Campos Adicionales:
1. Modificar tabla en `database.sql`
2. Actualizar formularios en `admin.php` y `registro.php`
3. Añadir campos en `perfil.php`
4. Incluir en `generar_pdf.php`

## 🔧 Solución de Problemas

### Error: "No se puede subir la foto"
**Solución**:
```bash
# Verificar permisos de carpeta uploads
chmod -R 777 uploads/fotos/
```

### Error: "Contraseña incorrecta" para datos de ejemplo
**Solución**:
- La contraseña por defecto es: **123456**
- Si no funciona, resetear en phpMyAdmin o crear nuevo empleado

### Las fotos no se muestran
**Solución**:
1. Verificar que la carpeta `uploads/fotos/` existe
2. Verificar permisos de lectura
3. Comprobar ruta en base de datos (columna `foto_perfil`)

### Error al generar PDF
**Solución**:
1. La foto debe estar en formato válido (JPG, PNG, GIF)
2. Verificar que el archivo existe en `uploads/fotos/`
3. Usar Ctrl+P y "Guardar como PDF" desde el navegador

### Problemas con caracteres especiales
**Solución**:
- Verificar que la BD use UTF-8
- Confirmar charset en `config.php`
- Revisar que archivos estén en UTF-8

## 📱 Compatibilidad

### Navegadores:
- ✅ Google Chrome (recomendado)
- ✅ Mozilla Firefox
- ✅ Microsoft Edge
- ✅ Safari
- ✅ Opera

### Dispositivos:
- 💻 Computadoras de escritorio
- 📱 Tablets
- 📲 Smartphones

### Sistemas Operativos:
- Windows (XAMPP, WAMP)
- macOS (MAMP)
- Linux (LAMP)

## 📊 Datos de Ejemplo

El sistema incluye 4 empleados de ejemplo:

| Cédula      | Nombre                              | Contraseña |
|-------------|-------------------------------------|------------|
| 1143453570  | DANNAY ANDREA FERNANDEZ OROZCO      | 123456     |
| 53021071    | MARIA CRISTINA MATIZ HERNANDEZ      | 123456     |
| 1087292293  | MARIA CRISTINA SALGADO CONTRERAS    | 123456     |
| 1102822478  | ROXANA GERLEY GARRIDO ORTEGA        | 123456     |

Puedes iniciar sesión con cualquiera de estos datos para probar el sistema.

## 🆕 Novedades de v2.0

### Comparación con v1.0:

| Característica               | v1.0 | v2.0 |
|------------------------------|------|------|
| Consulta por cédula          | ✅   | ✅   |
| Panel de administración      | ✅   | ✅   |
| Generación de PDF            | ✅   | ✅   |
| Sistema de login             | ❌   | ✅   |
| Registro de empleados        | ❌   | ✅   |
| Foto de perfil               | ❌   | ✅   |
| Foto en PDF                  | ❌   | ✅   |
| Gestión de contraseñas       | ❌   | ✅   |
| Perfil personal              | ❌   | ✅   |

## 📞 Soporte

Para reportar problemas o sugerencias:
1. Revisar esta documentación
2. Verificar los archivos de configuración
3. Revisar permisos de carpetas
4. Consultar logs de PHP/Apache

## 📝 Licencia

Este proyecto es de código abierto y puede ser utilizado libremente para fines educativos y comerciales.

---

**Sistema de Gestión de Empleados v2.0**
Desarrollado con ❤️ en PHP + MySQL
© 2026 - Todos los derechos reservados
