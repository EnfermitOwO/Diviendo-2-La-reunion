<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

if(isset($_SESSION['user_id'])){
    header("Location: Index.php");
    exit();
}

$error = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // 🔥 Ajustado a tus columnas reales
    $stmt = $conn->prepare("SELECT id, Name, Password FROM users WHERE Hotmail = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $user = $result->fetch_assoc();

        // ⚠️ OJO: Password con mayúscula
        if(password_verify($password, $user['Password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['Name'];

            header("Location: index.php");
            exit();

        } else {
            $error = "Contraseña incorrecta";
        }

    } else {
        $error = "Usuario no encontrado";
    }
}
?>