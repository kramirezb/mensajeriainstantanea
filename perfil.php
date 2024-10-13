<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['login_name'])) {
    header('Location: index.php');
    exit();
}

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "mensajeria_instantanea");
if (!$conexion) {
    die('Error de conexión a la base de datos');
}

$nombre_usuario = $_SESSION['login_name'];
$usuario_id = $_SESSION['login_id'];

// Función para obtener la información del usuario
function obtenerInfoUsuario($conexion, $usuario_id) {
    $query = "SELECT estado, foto_perfil, numero_tel FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Obtener la información del usuario
$info_usuario = obtenerInfoUsuario($conexion, $usuario_id);
$estado_usuario = $info_usuario['estado'] ?? '';
$foto_perfil_url = $info_usuario['foto_perfil'] ?? 'path_to_default_image.jpg'; // Imagen predeterminada
$numero_telefono = $info_usuario['numero_tel'] ?? 'No disponible';

// Subida de la foto de perfil
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['profile_photo'];

    // Verificar el tipo de archivo
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $file_type = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        echo "Error: Solo se permiten archivos de tipo imagen.";
        exit();
    }

    // Verificar el tamaño del archivo
    if ($foto['size'] > 1024 * 1024) { // Limitar a 1MB
        echo "Error: El archivo es demasiado grande.";
        exit();
    }

    // Definir la carpeta de subida
    $directorio = 'uploads/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true); // Crear la carpeta si no existe
    }

    // Definir la ruta del archivo
    $nombre_archivo = $directorio . basename($usuario_id) . '.' . $file_type;

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($foto['tmp_name'], $nombre_archivo)) {
        // Actualizar la ruta de la foto en la base de datos
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "si", $nombre_archivo, $usuario_id);
        mysqli_stmt_execute($stmt);
        header('Location: perfil.php'); // Refrescar la página
        exit();
    } else {
        echo "Error al subir la foto.";
    }
}

// Actualizar el estado del usuario
if (isset($_POST['status'])) {
    $estado = $_POST['status'];
    $query = "UPDATE usuarios SET estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "si", $estado, $usuario_id);
    mysqli_stmt_execute($stmt);

    // Refrescar el estado del usuario
    $estado_usuario = $estado;
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #a8c0ff, #3f3f3f); 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            margin: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .profile-container:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #3f3f3f;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        p {
            color: #555555;
            font-size: 1.2rem;
        }
        .profile-image {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 20px auto;
            border-radius: 50%;
            background-color: #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .profile-image img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        .camera-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
        }
        .camera-icon img {
            width: 20px;
            height: 20px;
        }
        .status-container {
            margin-top: 20px;
            text-align: center;
        }
        .status-container input {
            padding: 10px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
        }
        .status-container p {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        .edit-icon {
            cursor: pointer;
            margin-left: 10px;
        }
        .edit-icon img {
            width: 20px;
            height: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4a90e2;
            color: #ffffff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
    </style>
    <script>
        function triggerFileInput() {
            document.getElementById('profile_photo').click();
        }
        function enableStatusEdit() {
            const statusInput = document.getElementById('status');
            statusInput.style.display = 'inline';
            statusInput.focus();
        }
    </script>
</head>
<body>
    <div class="profile-container">
        <h1>Perfil de Usuario</h1>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
        
        <div class="profile-image">
            <img src="<?php echo htmlspecialchars($foto_perfil_url); ?>" alt="Foto de Perfil">
            <form action="perfil.php" method="post" enctype="multipart/form-data">
                <label class="camera-icon" onclick="triggerFileInput()">
                    <img src="assets/imagenes/camera_icon.jpg" alt="Cambiar Foto">
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>
        </div>

        <div class="status-container">
            <p><strong>Estado:</strong> <span id="estado_texto"><?php echo htmlspecialchars($estado_usuario); ?></span>
                <span class="edit-icon" onclick="enableStatusEdit()">
                    <img src="assets/imagenes/pencil_icon.png" alt="Editar Estado">
                </span>
            </p>
            <form action="perfil.php" method="post" style="display: inline;">
                <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($estado_usuario); ?>" onblur="this.form.submit()">
            </form>
        </div>

        <p><strong>Número de Teléfono:</strong> <?php echo htmlspecialchars($numero_telefono); ?></p>

        <a href="menu_principal.php" class="button">Regresar al Menú Principal</a>
        <a href="contactos.php" class="button">Ver Mis Contactos</a>
    </div>
</body>
</html>
