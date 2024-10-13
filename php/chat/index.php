<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['login_name'])) {
    // Si no está configurado, redirige al usuario al formulario de inicio de sesión
    header('Location: index.php');
    exit();
}

$myUser = $_SESSION['login_id'];
$_SESSION['usrReceptorId'] = 300;
setcookie("Chat_Abierto", 0, time() + 3600);

include('servicios/chat.php');

$chat = new ChatPersonal();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat de Mensajería Instantanea</title>
    <!-- <link rel="stylesheet" href="/Mensajeria_instantanea/assets/css/chat.css"> -->
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
  </head>
  <body>
    <!-- Inicio menú-->
    <nav class="navbar navbar-primary bg-primary nav-tabs text">
        <div class="container-fluid">
            <a class="navbar-brand fs-3 d-none d-md-block text-light" href="#">Mensajeria Instantanea - Chat </a>
            <!-- Inicio de menú -->
            <nav class="navbar justify-content-center">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-light" aria-current="page" href="#">Bandeja de entrada</a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#">Grupos</a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../../menu_principal.php">Menú Principal</a>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
    <!-- Fin menú -->
    

    <div class="container-fluid p-0 m-0 bg-danger bandeja bg-primary">
        <div class="row m-0 p-0 bandeja">
            <main class="bg-dark col-12 col-md-4 col-lg-4 p-0 g-0 m-0 flex-shrink-0">
                <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white w-100">
                    <div class="d-flex align-items-center flex-shrink-0 p-3 link-dark border-bottom">
                        <div class="search visually-hidden">
                            <span class="text">Selecciona un usuario para iniciar el chat</span>
                            <input type="text" placeholder="Enter name to search...">
                            <button><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush border-bottom scrollarea users-list pb-5">
                        <?php 
                        /*
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
                                            <a href="#" class="list-group-item list-group-item-action py-3 lh-tight message-link" aria-current="true" data-message="Mensaje 1">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img src="../login/img/Perfiles/usr_17_FotoPerfil.png" width="60px" height="auto" class="rounded-circle">
                                                    </div>
                                                    <div class="col-10">
                                                    <div class="d-flex w-100 align-items-center justify-content-between">
                                                        <strong class="mb-1">'.strtoupper($contact['nombre_completo']).'</strong>
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
                                */
                        ?>
                        
                    </div>
                </div>
            </main>

            <!-- Div de los detalles del mensaje (o modal en dispositivos móviles) -->
             <div class="col-12 col-md-8 col-lg-8 bg-primary-subtle d-none d-md-block">
                <section class="chat-area" id="message-details">
                    <header class="encabezado shadow-sm p-1 rounded" id="chat-header">

                    </header>
                    <div class="chat-box" id="chat-box">

                    </div> 
                    <form action="#" class="typing-area" enctype="multipart/form-data">
                        <input type="text" class="incoming_id" name="incoming_id" hidden>
                        <input type="text" name="message" class="input-field" placeholder="Escribe tu mensaje aquí..." autocomplete="off" disabled>
                        <button type="submit"><i class="bi bi-send"></i></button>
                        <!--<label for="documento" style="cursor: pointer;" class="btn btn-secondary">
                            <input type="file" id="documento" name="adjunto" style="display: none;">
                            <i class="bi bi-file-image fs-5"></i>
                        </label> -->
                    </form>
                </section>
             </div>
        </div>
    </div>

     <!-- Modal para dispositivos móviles -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Detalles del Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-message-details">
                    <!-- El contenido del mensaje se mostrará aquí en el modal -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<script src="js/users.js"></script>
<script src="js/chat.js"></script>


<script>
    function AbrirChat(userId) {
      // Almacenar el ID del usuario en una cookie llamada "Chat_Abierto"
      // document.cookie = "Chat_Abierto=" + userId;
      console.log(`Entrando a abrirChat. User id: ${userId}`);
      // Puedes usar una función AJAX para cargar el contenido del chat
      
      $.ajax({
        type: "POST",
        url: "servicios/obtener_chat.php", // Nombre del archivo PHP para cargar el chat
        data: {
          incoming_id: userId
        },
        beforeSend: function() {
          // Mostrar overlay con spinner de carga
          $.LoadingOverlay("show");
        },
        success: function(response) {
          // Actualiza el contenido del chat con la respuesta del servidor
          document.getElementById("chat-box").innerHTML = response;
          actualizarEncabezadoChat(userId);
          // Obtén una referencia al elemento por su clase
          var incomingIdField = document.querySelector(".incoming_id");
          incomingIdField.setAttribute("value", userId);
          document.cookie = "Chat_Abierto=" + userId;
          
          var inputFields = document.querySelectorAll(".input-field");

          inputFields.forEach(function(inputField) {
            inputField.removeAttribute("disabled");
          });

          $.LoadingOverlay("hide");
          console.log("Chat cargado");
        },
        error: function(error) {
          $.LoadingOverlay("hide");
          console.error("Error al cargar el chat: " + error);
        }
      });
      
    }

    function actualizarEncabezadoChat(chatAbierto) {
      $.ajax({
        type: "POST",
        url: "servicios/obtener_encabezado_chat.php",
        data: {
          chatAbierto: chatAbierto
        },
        success: function(response) {
          $("#chat-header").html(response); // Actualiza el contenido del encabezado del chat
          console.log("Encabezado del chat actualizado");
        },
        error: function(error) {
          console.error("Error al obtener la información del usuario: " + error);
        }
      });
    }
  </script>
  <script>
    // Función para manejar el clic en los enlaces de la bandeja de mensajes
    const messageLinks = document.querySelectorAll('.message-link');
    const messageDetails = document.getElementById('message-details');
    const modalMessageDetails = document.getElementById('modal-message-details');
    const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));

    messageLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        console.log("Enlace de mensaje clickeado");
        const message = link.getAttribute('data-message');
        //document.cookie = "Chat_Abierto=" + message; // Almacenar el ID del usuario en una cookie
        messageDetails.innerHTML = message; // Mostrar detalles del mensaje en el div
        modalMessageDetails.innerHTML = message; // Mostrar detalles del mensaje en el modal
        if (window.innerWidth < 768) {
          messageModal.show(); // Mostrar el modal en dispositivos móviles
        }
      });
    });

    // Función para ajustar el modal en dispositivos móviles
    window.addEventListener('resize', () => {
      if (window.innerWidth < 768) {
        messageModal._config.backdrop = true;
        messageModal._config.keyboard = true;
      } else {
        messageModal._config.backdrop = 'static';
        messageModal._config.keyboard = false;
      }
    });

    // Mostrar el modal en dispositivos móviles al cargar la página si es necesario
    window.addEventListener('DOMContentLoaded', () => {
      if (window.innerWidth < 768 && messageDetails.innerHTML !== '') {
        messageModal.show();
      }
    });
  </script>

  <script>
    function getChatInfo(receptorId){
      console.log('Receptor Id: '+ receptorId);
    }

  </script>