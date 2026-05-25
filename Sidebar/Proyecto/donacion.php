<?php
// Datos de conexión
$servername = "localhost";
$username = "root"; // Cambia si tienes otro usuario
$password = "";     // Cambia si tienes contraseña
$dbname = "usuarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $tarjeta = $_POST['tarjeta'];
    $date = $_POST['date'];
    $cvv = $_POST['cvv'];
    $monto = $_POST['monto'];

    // Preparar la consulta según tu tabla 'donacion'
    // La columna 'id' es AUTO_INCREMENT, así que no la ponemos
    $sql = "INSERT INTO donacion (Name, Tarjeta, Date, CVV, monto) 
            VALUES ('$name', '$tarjeta', '$date', '$cvv', '$monto')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('¡Donación registrada con éxito!');
                window.location.href='Santa Catarina.html';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>