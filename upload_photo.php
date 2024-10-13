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

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['profile_photo'];

    // Definir la carpeta de subida
    $directorio = 'uploads/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true); // Crear la carpeta si no existe
    }

    // Definir la ruta del archivo
    $nombre_archivo = $directorio . basename($nombre_usuario) . '.jpg'; 

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($foto['tmp_name'], $nombre_archivo)) {
        echo "Archivo subido exitosamente.<br>";

        // Actualizar la ruta de la foto en la base de datos
        $query = "UPDATE usuarios SET foto_perfil = '$nombre_archivo' WHERE usuario = '$nombre_usuario'";
        if (mysqli_query($conexion, $query)) {
            echo "Base de datos actualizada correctamente.<br>";
            header('Location: perfil.php');
            exit();
        } else {
            echo "Error al actualizar la base de datos: " . mysqli_error($conexion) . "<br>";
        }
    } else {
        echo "Error al subir la foto.<br>";
    }
} else {
    echo "No se ha seleccionado ningún archivo o ha ocurrido un error.<br>";
    echo "Código de error: " . $_FILES['profile_photo']['error'] . "<br>";
}

mysqli_close($conexion); // Cerrar la conexión a la base de datos
?>