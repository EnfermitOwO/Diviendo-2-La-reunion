<?php
session_start();
require_once "conexion.php";
require_once "logs.php";


if(isset($_SESSION["user_id"])){
    guardarLog($conn, $_SESSION["user_id"], "Cierre de sesión");
}

session_destroy();

header("Location: index.php");
exit();
?>