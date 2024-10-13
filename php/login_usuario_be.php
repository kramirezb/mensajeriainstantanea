<?php
session_start(); // Inicia la sesión
include 'conexion_be.php';

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Consulta para validar el usuario
$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena'");

if(mysqli_num_rows($validar_login) > 0){
    // Obtener datos del usuario
    $usuario = mysqli_fetch_assoc($validar_login);

    // Almacenar nombre del usuario en la sesión
    $_SESSION['login_id'] = $usuario['id'];
    $_SESSION['login_name'] = $usuario['nombre_completo'];
    $_SESSION['login_time'] = time();

    // Redirigir al menú principal
    header("Location: ../menu_principal.php");
    exit();
} else {
    // Mostrar mensaje de error y redirigir
    echo '
        <script> 
            alert("USUARIO INVALIDO, VERIFICAR DATOS INTRODUCIDOS");
            window.location = "../index.php";
        </script>
    ';
    exit();
}
?>
