<?php

    /*para dar acceso a la bd*/ 
    include 'conexion_be.php';


    /*para jalar los datos en los campos del login*/ 
    $nombre_completo = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $numero_tel = $_POST['numero_tel'];

    /*encriptar contraseÃ±a
    $contrasena = hash('sha512', $contrasena); //para encriptar contraseÃ±a sha512 algoritmo de encritamiento*/

    $query = "INSERT INTO usuarios(nombre_completo, correo, usuario,  contrasena, numero_tel) 
    VALUES('$nombre_completo', '$correo', '$usuario', '$contrasena', '$numero_tel')";

//verificar que el correo no se repita en la bd

$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo ='$correo' ");

if(mysqli_num_rows($verificar_correo) > 0){
    echo'
    <script>
        alert("Este correo ya esta registrado, intente con otro correo");
        window.location = "../index.php";  
        </script>  
    ';
    exit();
}


//verificar que el usuario no se repita en la bd

$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario ='$usuario' ");

if(mysqli_num_rows($verificar_usuario) > 0){
    echo'
    <script>
        alert("Este usuario ya esta registrado, intente con otro nombre");
        window.location = "../index.php";  
        </script>  
    ';
    exit();
}
    /* para ejecutar el query*/

    $ejecutar = mysqli_query($conexion, $query); 

    if($ejecutar){
        echo '
            <script>
                alert("El usuario se registro exitosamente");
                window.location = "../index.php";
            </script>    
            ';
        }else{
            echo '
            <script>
                alert("Intentar de nuevo el usuario no se registro exitosamente");
                window.location = "../index.php";
            </script> 
            ';
        }
       /* mysqli_close($conexion);*/

?>





