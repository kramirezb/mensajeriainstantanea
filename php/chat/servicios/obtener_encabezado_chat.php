<?php

    session_start(); 

    include_once "chat.php";

    $currentChat = $_POST['chatAbierto'];

    if(isset($currentChat))
    {
        $chat = new ChatPersonal();

        $chatHeaderInfo = $chat->getContactHeaderInfo($currentChat);

        if(!empty($chatHeaderInfo)){
            echo '
                <div class="row">
                    <div class="col-2">
                        <img src="./src/img/user.png"  width="40em" height="40em" class="rounded-circle">
                    </div>
                    <div class="col-10">
                        <div class="details">
                            <span class="fw-bold text-darks">
                                '. strtoupper($chatHeaderInfo[0]['nombre_completo']).' 
                            </span>
                            <p class="text-dark">
                                '. //$row['status'].
                                '
                                Desconocido
                            </p>
                        </div>
                    </div>
                </div>';
        }
        else{
            echo 'ERROR DE SESION;';
        }
    }else{
        echo 'NO HAY SESION; ';
    }




?>
