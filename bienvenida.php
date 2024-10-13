<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida - Telecomunicaciones - git</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4b0082;
        }
        p {
            color: #6a5acd;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a Telecomunicaciones!</h1>
        <p>Estamos encantados de tenerte aquí.</p>
       <?php
       include 'conexion_be.php';
        $_USUARIO['usuario'] = $usuario;
        ?>
    </div>
</body>
</html>
