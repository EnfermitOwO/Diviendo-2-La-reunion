<?php
session_start();
require_once "conexion.php"; // Asegúrate de que el nombre coincida con tu archivo de conexión

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_usuario'])) {
    $archivo = $_FILES['foto_usuario'];
    
    // Validar extensión (¡Soportamos GIF!)
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $permitidos = array("jpg", "jpeg", "png", "gif");

    if (in_array($ext, $permitidos)) {
        // Crear nombre único para evitar que se borren fotos de otros
        $nombreNuevo = "perfil_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;
        $rutaDestino = "uploads/" . $nombreNuevo;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            
            // ACTUALIZACIÓN DE LA BASE DE DATOS
            $stmt = $conn->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
            $stmt->bind_param("si", $nombreNuevo, $_SESSION['user_id']);

            if ($stmt->execute()) {
                // Actualizar la sesión para que el Sidebar cambie sin recargar todo
                $_SESSION['foto'] = $nombreNuevo;
                header("Location: perfil.php?success=1");
            } else {
                echo "Error en la BD: " . $conn->error;
            }
        } else {
            echo "Error: No se pudo mover el archivo a la carpeta 'uploads'.";
        }
    } else {
        echo "Formato no válido. Usa JPG, PNG o GIF.";
    }
}
?>