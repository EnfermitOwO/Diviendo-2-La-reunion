<?php

function guardarLog($conn, $user_id, $accion){

    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("INSERT INTO logs (user_id, accion, ip) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $accion, $ip);
    $stmt->execute();

}

?>