<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['login_name'])) {
    header('Location: index.php');
    exit();
}

// Incluye el archivo de conexión a la base de datos
include('php/conexion_be.php');

$usuario_id = $_SESSION['login_id']; // ID del usuario en sesión

// Procesa el formulario de agregar o editar contacto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['contact_name'];
    $telefono = $_POST['contact_phone'];
    $fecha_hora_actual = date('Y-m-d H:i:s');

    if ($_POST['action'] == 'add') {
        // Lógica para agregar un contacto
        if (!empty($nombre) && !empty($telefono)) {
            $nombre = mysqli_real_escape_string($conexion, $nombre);
            $telefono = mysqli_real_escape_string($conexion, $telefono);

            $queryAmigo = "SELECT id FROM usuarios WHERE numero_tel = '$telefono'";
            $resultadoAmigo = mysqli_query($conexion, $queryAmigo);

            if ($resultadoAmigo && mysqli_num_rows($resultadoAmigo) > 0) {
                $rowAmigo = mysqli_fetch_assoc($resultadoAmigo);
                $usuario_amigo_id = $rowAmigo['id'];

                $query = "INSERT INTO contactos (usuario_id, usuario_amigo_id, numero_telefonico, nombre, fecha) 
                          VALUES ('$usuario_id', '$usuario_amigo_id', '$telefono', '$nombre', '$fecha_hora_actual')";
            } else {
                echo "<script>alert('No se encontró un usuario registrado con este número.'); window.location.href='contactos.php';</script>";
            }

            if (mysqli_query($conexion, $query)) {
                echo "<script>alert('Contacto agregado con éxito.'); window.location.href='contactos.php';</script>";
            } else {
                echo "<script>alert('Error al agregar contacto.');</script>";
            }
        }
    } elseif ($_POST['action'] == 'edit' && isset($_POST['contact_id'])) {
        // Lógica para editar un contacto
        $contact_id = $_POST['contact_id'];
        $query = "UPDATE contactos SET nombre = '$nombre', numero_telefonico = '$telefono' WHERE id = '$contact_id'";
        if (mysqli_query($conexion, $query)) {
            echo "<script>alert('Contacto editado con éxito.'); window.location.href='contactos.php';</script>";
        } else {
            echo "<script>alert('Error al editar contacto.');</script>";
        }
    }
}

// Lógica para eliminar contacto
if (isset($_GET['delete'])) {
    $contact_id = $_GET['delete'];
    $query = "DELETE FROM contactos WHERE id = '$contact_id'";
    if (mysqli_query($conexion, $query)) {
        echo "<script>alert('Contacto eliminado.'); window.location.href='contactos.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar contacto.');</script>";
    }
}

// Consulta para obtener los contactos del usuario
$resultado = $conexion->query("SELECT id, nombre, numero_telefonico FROM contactos WHERE usuario_id = '$usuario_id' ORDER BY nombre ASC");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos - Mensajería Instantánea</title>
    <link rel="stylesheet" href="assets/css/estilos.css?v=1.0"> <!-- Carga la versión css más reciente -->
    <script>
        function toggleSection(section, isEdit = false) {
            const sections = document.querySelectorAll('.form-container, .contacts-container');
            sections.forEach(sec => sec.classList.remove('active'));
            document.querySelector(`.${section}`).classList.add('active');

            // Cambiar el título según si es para agregar o editar contacto
            const formTitle = document.querySelector('.form-container h2');
            if (isEdit) {
                formTitle.textContent = 'Editar Contacto';
            } else {
                formTitle.textContent = 'Agregar Contacto';
            }
        }

        function fillEditForm(id, name, phone) {
            // Cambia el formulario a modo edición
            document.getElementById('contact-id').value = id;
            document.getElementById('contact-name').value = name;
            document.getElementById('contact-phone').value = phone;
            document.getElementById('form-action').value = 'edit'; // Cambia el valor de acción a "editar"
            
            // Llama a la función toggleSection y pasa 'true' para que se muestre "Editar Contacto"
            toggleSection('form-container', true);

            // Ocultar botones "Agregar Contacto" y "Regresar"
            document.getElementById('add-contact-btn').style.display = 'none';
            document.getElementById('regresar').style.display = 'none';
        }

        function cancelEdit() {
            // Limpia los campos del formulario
            document.getElementById('contact-id').value = '';
            document.getElementById('contact-name').value = '';
            document.getElementById('contact-phone').value = '';
            document.getElementById('form-action').value = 'add'; // Vuelve a poner la acción en 'add'

            // Mostrar botones "Agregar Contacto" y "Regresar"
            document.getElementById('add-contact-btn').style.display = 'inline-block';
            document.getElementById('regresar').style.display = 'inline-block';

            // Regresar a la vista de "Mis Contactos"
            toggleSection('contacts-container', false);
        }
    </script>
</head>
<body class="fondo-contenedor">
    <div class="container">
        <img src="assets/imagenes/contactos.png" alt="Contactos" class="contact-image">
        <h1>Contactos</h1>
        <div class="buttons">
            <button class="button-menu" onclick="toggleSection('contacts-container')">Mis Contactos</button>
            <button id="add-contact-btn" class="button-menu" onclick="toggleSection('form-container', false)">Agregar Contacto</button>
            <a href="menu_principal.php" id="regresar" class="button-menu">Regresar</a>
        </div>

        <!-- Sección de Mis Contactos -->
        <div class="contacts-container">
            <h2>Mis Contactos</h2>
            <ul class="contact-list">
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <li class="contact-item">
                            <div class="contact-avatar">
                                <?php echo strtoupper(substr(htmlspecialchars($row['nombre']), 0, 1)); ?>
                            </div>
                            <div class="contact-info">
                                <span class="contact-name"><?php echo htmlspecialchars($row['nombre']); ?></span>
                                <span class="contact-phone"><?php echo htmlspecialchars($row['numero_telefonico']); ?></span>
                            </div>
                            <div class="contact-actions">
                                <button onclick="fillEditForm('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars($row['nombre']); ?>', '<?php echo htmlspecialchars($row['numero_telefonico']); ?>')" class="contact-edit">Editar</button>
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este contacto?')" class="contact-delete">Eliminar</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No tienes contactos guardados.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Sección para Agregar/Editar Contacto -->
        <div class="form-container">
            <h2>Agregar Contacto</h2> <!-- Este título será actualizado dinámicamente -->
            <form method="post" action="">
                <input type="hidden" id="form-action" name="action" value="add"> <!-- Por defecto, el valor es 'add' -->
                <input type="hidden" id="contact-id" name="contact_id"> <!-- Este campo se llena al editar -->
                <div class="input-group">
                    <label for="contact-name">Nombre del Contacto:</label>
                    <input type="text" id="contact-name" name="contact_name" required>
                </div>
                <div class="input-group">
                    <label for="contact-phone">Número de Teléfono:</label>
                    <input type="text" id="contact-phone" name="contact_phone" required>
                </div>
                <button type="submit" class="button-menu">Guardar</button>
                <button type="button" class="button-menu" onclick="cancelEdit()">Cancelar</button>
                <a href="contactos.php" class="button-menu">Regresar</a>
            </form>
        </div>        
    </div>
</body>
</html>

<?php
$conexion->close();
?>
