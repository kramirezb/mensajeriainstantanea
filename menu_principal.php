
<?php
session_start(); // Inicia la sesión

// Verifica si la variable de sesión 'login_name' está configurada
if (!isset($_SESSION['login_name'])) {
    // Si no está configurada, redirige al usuario al formulario de inicio de sesión
    header('Location: index.php');
    exit();
}

//para cerrar sesion

// Verifica si se ha solicitado cerrar sesión
if (isset($_POST['logout'])) {
    // Destruye la sesión y redirige al inicio de sesión
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

$usuarioId = $_SESSION['login_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - Telecomunicaciones</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <script>
        // Redirigir a index.php después de 1 minuto (60000 ms)
        setTimeout(function(){
            window.location.href = 'index.php';
        }, 60000);
    </script>
</head>
<body class="fondo-contenedor">
    <div class="container">
        <h1>Mensajeria Instantanea</h1>
      
        <p>Has iniciado sesión exitosamente.</p>

        <a href="perfil.php" class="button-menu">Perfil</a>
        <a href="./php/chat/index.php" class="button-menu">Chat</a>
        <a href="contactos.php" class="button-menu">Contactos</a>
        <form method="post">
        <button type="submit" name="logout" class="button-menu logout-button">Cerrar Sesión</button>
        </form>
    </div>
</body>
