<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

/* =========================
   ELIMINAR USUARIO
========================= */
if (isset($_POST['delete_user'])) {

    $user_id = intval($_POST['user_id']);

    $delete_sql = "DELETE FROM users WHERE id = $user_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Usuario eliminado exitosamente";
    } else {
        echo "Error al eliminar usuario: " . $conn->error;
    }

    $conn->close();
    exit;
}

/* =========================
   EDITAR USUARIO
========================= */
if (isset($_POST['edit_user'])) {

    $user_id = intval($_POST['user_id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo_electronico']);

    $edit_sql = "UPDATE users 
                 SET Name='$nombre', Hotmail='$correo'
                 WHERE id = $user_id";

    if ($conn->query($edit_sql) === TRUE) {
        echo "Usuario actualizado exitosamente";
    } else {
        echo "Error al actualizar usuario: " . $conn->error;
    }

    $conn->close();
    exit;
}

/* =========================
   CREAR USUARIO
========================= */
if (isset($_POST['Name']) && isset($_POST['Hotmail']) && isset($_POST['Password'])) {

    $nombre = $conn->real_escape_string($_POST['Name']);
    $correo = $conn->real_escape_string($_POST['Hotmail']);
    $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (Name, Hotmail, Password) 
            VALUES ('$nombre', '$correo', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
    exit;
}

echo "Solicitud no válida.";
$conn->close();
?>