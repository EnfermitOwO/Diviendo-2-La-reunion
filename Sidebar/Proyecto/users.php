<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
   
         <style>
        body{
            background-image: url('https://i.imgur.com/foiuTzG.gif');
            background-size: contain;
            background-attachment: fixed;
        }
    </style>
        
<body>
    <section id="usuarios" class="about">
        <div class="content">
            <h2>Usuarios Registrados</h2>
            <table id="usuarios-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se cargarán los usuarios desde la base de datos -->
                </tbody>
            </table>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Cargar usuarios
            $.ajax({
                url: 'load_users.php',
                type: 'GET',
                success: function(data) {
                    $('#usuarios-table tbody').html(data);
                }
            });

            // Eliminar usuario
            $(document).on('click', '.delete-btn', function() {
                if (confirm('¿Está seguro de que desea eliminar este usuario?')) {
                    var userId = $(this).data('id');
                    $.ajax({
                        url: 'register.php',
                        type: 'POST',
                        data: { delete_user: true, user_id: userId },
                        success: function(response) {
                            alert(response);
                            location.reload(); // Recargar la página para ver los cambios
                        }
                    });
                }
            });

            // Editar usuario
            $(document).on('click', '.edit-btn', function() {
                var userId = $(this).data('id');
                var nombre = prompt("Ingrese el nuevo nombre:");
                var correo = prompt("Ingrese el nuevo correo electrónico:");

                if (nombre && correo) {
                    $.ajax({
                        url: 'register.php',
                        type: 'POST',
                   data: { edit_user: true, user_id: userId, nombre: nombre, correo: correo },
                        success: function(response) {
                            alert(response);
                            location.reload(); // Recargar la página para ver los cambios
                        }
                    });
                }
            });
        });
            
    </script>
</body>
</html>
