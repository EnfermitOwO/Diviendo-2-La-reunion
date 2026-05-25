    <?php
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    // Verificar conexión
    if ($conexion->connect_error) {
        http_response_code(500);
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta
    $sql = "SELECT * FROM usuarios";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
            echo "<td>
                    <button class='edit-btn' data-id='{$fila['id']}'>Editar</button>
                    <button class='delete-btn' data-id='{$fila['id']}'>Eliminar</button>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No hay usuarios registrados</td></tr>";
    }

    $conexion->close();
    ?>