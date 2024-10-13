<?php

    include_once "chat.php";
    session_start(); 

    $emisor_id = $_SESSION['login_id'];
    $receptor_id = $_COOKIE['Chat_Abierto'];

    if (isset($receptor_id)) 
    {
        $chat = new ChatPersonal();

        $mensajes = $chat->getChatMessage($emisor_id, $receptor_id);

        //print_r($mensajes);
        
        $output = '';
        if(!empty($mensajes))
        {
            foreach($mensajes as $mensaje)
            {
                if($mensaje['usr_emisor_id'] == $emisor_id)
                {
                    $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>' . $mensaje['contenido'] . '</p>
                                    </div>
                                </div>';
                }
                else
                {
                    $output .= '<div class="chat incoming">
                    <img src="./src/img/user.png"  width="40em" height="40em" class="rounded-circle">
                                <div class="details">
                                    <p>' . $mensaje['contenido'] . '</p>
                                </div>
                            </div>';
                }

            }
        }
        else
        {
            $output .= '<div class="text">No hay mensajes disponibles. Una vez que envíe el mensaje, aparecerán aquí.</div>';
        }

        echo $output;

    }
    else
    {
        echo '<div class="text">Debes seleccionar una conversación.</div>';
    }


?>