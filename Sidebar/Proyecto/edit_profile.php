<?php
session_start(); // Asegúrate de usar sesiones para identificar al usuario

$servername = "fdb1030.awardspace.net";
$username = "4512339_lala";
$password = "lalalong_1";
$dbname = "4512339_lala";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar mensaje
$response_message = "";

// Verificar si el usuario está autenticado (a través de sesión, por ejemplo)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo_electronico = $conn->real_escape_string($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];

    $sql = "UPDATE usuarios SET nombre='$nombre', correo_electronico='$correo_electronico'";

    if (!empty($contraseña)) {
        $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql .= ", contraseña='$contraseña'";
    }

    $sql .= " WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        $response_message = "Perfil actualizado";
    } else {
        $response_message = "Error: " . $conn->error;
    }
} else {
    $response_message = "No está autorizado para realizar esta acción.";
}

// Cerrar conexión
$conn->close();

// Enviar respuesta
echo $response_message;
?>

