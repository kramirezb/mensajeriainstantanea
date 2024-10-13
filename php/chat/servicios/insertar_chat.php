<?php

    include_once "chat.php";
    session_start();

    $emisor_id = $_SESSION['login_id'];
    $receptor_id = $_COOKIE['Chat_Abierto'];

    if(isset($_SESSION['login_id']))
    {
        $message = $_POST['message'];

        if(!empty($message)){
            $chat = new ChatPersonal();

            $x = $chat->insertMessage($message, $emisor_id, $receptor_id);

            echo "Mensaje enviado";
        }
        else{
            echo 'NO HAY MENSAJE PARA ENVIAR';
        }

    }
?>