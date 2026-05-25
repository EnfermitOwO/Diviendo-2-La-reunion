<?php
$conn = new mysqli("localhost","root","","usuarios");
if($conn->connect_error){
die("Error de conexión");
}
?>