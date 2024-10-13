<?php
    session_start();

    include('chat.php');

    $chat = new ChatPersonal();

    $id = $_SESSION['login_id'];

    $contacts = $chat->getAllChatContacts($id);

    if(!empty($contacts))
    {
            foreach($contacts as $contact)
            {
                $contact['ultimo_msg'] = (empty($contact['ultimo_msg'])) 
                                                ? "No has iniciado conversión"
                                                : $contact['ultimo_msg'];

                echo '
                    <a href="#" class="list-group-item list-group-item-action py-3 lh-tight message-link" aria-current="true" data-message="'.$contact['usr_id'].'" onclick="AbrirChat(' .$contact['usr_id']. '); return false;">
                        <div class="row">
                            <div class="col-2">
                                <img src="./src/img/user.png" width="30px" height="auto" class="rounded-circle">
                            </div>
                            <div class="col-10">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <strong class="mb-1">'.strtoupper($contact['nombre_contacto']).'</strong>
                                <small class="text-success"><i class="bi bi-circle-fill"></i></small>
                            </div>
                            <div class="col-10 mb-1 small text-truncate">'.$contact['ultimo_msg'].'.</div>
                            </div>
                        </div>
                    </a> 
                ';  

            }
    }
    else
    {
            echo '<span class="text-center p-3">Aún no tienes contactos agregados</span>';
    }




?>