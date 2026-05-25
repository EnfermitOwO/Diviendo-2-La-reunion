<?php
session_start();
// Si no hay sesión, mandarlo al inicio
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Dividendo</title>
    <link rel="stylesheet" href="Css/perfil.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="perfil-card">
    <div class="avatar-section">
        <img src="uploads/<?php echo $_SESSION['foto'] ?? 'quefeo.png'; ?>" alt="Foto de Perfil" class="main-avatar">
        <div class="online-status"></div>
    </div>

    <h1><?php echo $_SESSION['nombre']; ?></h1>
    <p class="role-tag">Usuario de Dividendo</p>

    <form action="ActualizarPerfil.php" method="POST" enctype="multipart/form-data" class="upload-form">
        <label for="foto" class="custom-file-upload">
            Seleccionar Nueva Foto
        </label>
        <input type="file" name="foto_usuario" id="foto" accept="image/*" required onchange="updateFileName()">
        <span id="file-name">Ningún archivo seleccionado</span>

        <div class="button-group">
            <button type="submit" class="save-btn">Actualizar Perfil</button>
            <a href="Index.php.php" class="back-link">Volver al Inicio</a>
        </div>
    </form>
</div>

<script>
function updateFileName() {
    var input = document.getElementById('foto');
    var fileName = input.files[0].name;
    document.getElementById('file-name').textContent = fileName;
}
</script>

</body>
</html>