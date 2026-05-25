<?php
$mi_municipio = "Santa Catarina";
session_start();
require_once "conexion.php";

mysqli_report(MYSQLI_REPORT_OFF);

/* =====================
   REGISTRO
===================== */
if(isset($_POST["registro"])){
    $nombre = trim($_POST["Name"]);
    $correo = trim($_POST["Hotmail"]);
    $password = $_POST["Password"];

    if(empty($nombre) || empty($correo) || empty($password)){
        echo "Todos los campos son obligatorios";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre,correo,password,role) VALUES (?,?,?,'user')");
    $stmt->bind_param("sss",$nombre,$correo,$passwordHash);

    if($stmt->execute()){
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["nombre"] = $nombre;
        $_SESSION["role"] = "user";
        echo "Registro exitoso";
    } else {
        echo ($conn->errno == 1062) ? "Ese correo ya está registrado" : "Error al registrar";
    }
    exit();
}

/* =====================
   LOGIN
===================== */
if(isset($_POST["login"])){
    $correo = trim($_POST["Hotmail"]);
    $password = $_POST["Password"];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo=?");
    $stmt->bind_param("s",$correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user["password"])){
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nombre"] = $user["nombre"];
            $_SESSION["role"] = $user["role"];
            echo "Login exitoso";
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
    exit();
}

/* =====================
   GUARDAR PARQUE (ADMIN)
===================== */
if(isset($_POST["add_parque"])){
    if(!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin"){
        echo "No autorizado";
        exit();
    }

    $titulo = $_POST["titulo"];
    $precio = $_POST["precio"];
    $direccion = $_POST["direccion"];
    $contacto = $_POST["contacto"];

    // Procesar Imagen
    $nombreImg = $_FILES['imagen']['name'];
    $rutaTemporal = $_FILES['imagen']['tmp_name'];
    $carpetaDestino = "uploads/";
    
    if(!file_exists($carpetaDestino)){ mkdir($carpetaDestino, 0777, true); }
    
    $rutaFinal = $carpetaDestino . time() . "_" . $nombreImg;

    if(move_uploaded_file($rutaTemporal, $rutaFinal)){
       // Busca tu INSERT y déjalo así:
$stmt = $conn->prepare("INSERT INTO parques (titulo, precio, direccion, info1, imagen, municipio) VALUES (?,?,?,?,?,?)");
// Usamos la variable $mi_municipio que definimos arriba
$stmt->bind_param("ssssss", $titulo, $precio, $direccion, $contacto, $rutaFinal, $mi_municipio);
        
        if($stmt->execute()){
            echo "Parque agregado correctamente";
        } else {
            echo "Error en BD";
        }
    } else {
        echo "Error al subir imagen";
    }
    exit();
}
/* ===================================
   ACCIONES DE ADMINISTRADOR: ELIMINAR
   =================================== */
if(isset($_POST["eliminar_parque"])){
    // Verificación de seguridad: solo el admin puede entrar aquí
    if(!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin"){
        echo "Acceso denegado";
        exit();
    }

    $id_parque = intval($_POST["id"]);

    // 1. Buscamos la ruta de la imagen antes de borrar el registro
    $stmt = $conn->prepare("SELECT imagen FROM parques WHERE id = ?");
    $stmt->bind_param("i", $id_parque);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();

    if($datos){
        $ruta_imagen = $datos['imagen'];

        // 2. Borramos el archivo físico de la carpeta 'uploads'
        if(!empty($ruta_imagen) && file_exists($ruta_imagen)){
            unlink($ruta_imagen);
        }

        // 3. Borramos el registro de la Base de Datos
        $stmt_del = $conn->prepare("DELETE FROM parques WHERE id = ?");
        $stmt_del->bind_param("i", $id_parque);
        
        if($stmt_del->execute()){
            echo "success"; // Enviamos respuesta de éxito al AJAX
        } else {
            echo "Error al eliminar de la base de datos";
        }
    } else {
        echo "El parque no existe";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santa Catarina</title>
    <link rel="stylesheet" href="Css/Santa.css">
    <link rel="stylesheet" href="Css/botons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .modal {
            display: none; 
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 10001;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #ffffff;
            width: 90%;
            max-width: 400px;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-content span {
            position: absolute; top: 15px; right: 20px;
            font-size: 24px; color: #999; cursor: pointer;
        }
        .modal-content input {
            width: 100%; display: block;
            padding: 12px 15px; margin: 10px 0;
            border-radius: 8px; border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .modal-content button {
            width: 100%; padding: 14px; margin-top: 15px;
            border: none; border-radius: 8px;
            font-weight: bold; color: #ffffff;
            background: #2c2c2c; cursor: pointer;
            transition: 0.3s;
        }
        .modal-content button:hover {
            background-color: #ff00aa; color: #000;
        }
        .btn-add-parque {
            position: fixed; bottom: 30px; right: 30px;
            width: 60px; height: 60px;
            background-color: #711531; color: #000;
            border-radius: 50%; display: flex;
            justify-content: center; align-items: center;
            font-size: 40px; font-weight: bold;
            cursor: pointer; z-index: 999; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0, 255, 68, 0.5);
        }
        .btn-add-parque:hover { transform: scale(1.1) rotate(90deg); }
        .btn-delete {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 35px;
    height: 35px;
    background: #ff4d4d;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    z-index: 50;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
    border: 2px solid white;
}

.btn-delete:hover {
    background: #ff0000;
    transform: scale(1.1) rotate(90deg);
}
    </style>
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <?php echo isset($_SESSION["user_id"]) ? "Hola, ".$_SESSION["nombre"] : "Dividendo Beta"; ?>
        </div>
        <nav>
            <ul>
                <li><a href="Index.php">Inicio</a></li>
               
                <?php if(isset($_SESSION["user_id"])): ?>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="#" id="openLogin">Iniciar sesión</a></li>
                    <li><a href="#" id="openRegistro">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<section class="cards-section">
    <?php
   $stmt_read = $conn->prepare("SELECT * FROM parques WHERE municipio = ? ORDER BY id DESC");
$stmt_read->bind_param("s", $mi_municipio);
$stmt_read->execute();
$resultado_parques = $stmt_read->get_result();
    ?>
    <div class="cards-container">
        <?php while($row = mysqli_fetch_assoc($resultado_parques)): ?>
            <div class="card" style="position: relative; overflow: hidden;">
                
                <?php if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"): ?>
                    <div class="btn-delete" data-id="<?php echo $row['id']; ?>" title="Eliminar este parque">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                <?php endif; ?>

                <div class="card-image" style="background: url('<?php echo $row['imagen']; ?>') center/cover;"></div>
                
                <div class="card-content">
                    <p class="tag"><?php echo htmlspecialchars($row['titulo']); ?></p>
                    <h2 class="price"><?php echo htmlspecialchars($row['precio']); ?></h2>
                    <p class="address"><?php echo htmlspecialchars($row['direccion']); ?></p>
                </div>

                <div class="card-realtor">
                    <div class="realtor-info-wrapper">
                        <div class="realtor-img"></div>
                        <div>
                            <p class="realtor-name"><?php echo htmlspecialchars($row['info1']); ?></p>
                        </div>
                    </div>
                    <a href="pago.html" class="btn-donate-small">Donar</a>
                </div>
            </div> <?php endwhile; ?>
    </div>
</section>

<?php if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"): ?>
    <div id="openAddParque" class="btn-add-parque">+</div>
<?php endif; ?>

<div id="addParqueModal" class="modal">
    <div class="modal-content">
        <span id="closeAddParque">X</span>
        <h2>Agregar Parque</h2>
        <form id="add-parque-form" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Nombre del parque" required>
            <input type="number" name="precio" placeholder="Precio (Ej: 750000)" required min="1" step="1">
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="contacto" placeholder="Contacto (Nombre/Tel)" required>
            <input type="file" name="imagen" accept="image/*" required>
            <input type="hidden" name="add_parque" value="1">
            <button type="submit">Publicar</button>
        </form>
        <div id="mensajeAdd"></div>
    </div>
</div>

<div id="registroModal" class="modal">
    <div class="modal-content">
        <span id="closeRegistro">X</span>
        <h2>Registrarse</h2>
        <form id="registro-form">
            <input type="text" name="Name" placeholder="Nombre" required>
            <input type="email" name="Hotmail" placeholder="Correo" required>
            <input type="password" name="Password" placeholder="Contraseña" required>
            <input type="hidden" name="registro" value="1">
            <button type="submit">Registrar</button>
        </form>
        <div id="mensaje"></div>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span id="closeLogin">X</span>
        <h2>Iniciar sesión</h2>
        <form id="login-form">
            <input type="email" name="Hotmail" placeholder="Correo" required>
            <input type="password" name="Password" placeholder="Contraseña" required>
            <input type="hidden" name="login" value="1">
            <button type="submit">Entrar</button>
        </form>
        <div id="mensajeLogin"></div>
    </div>
</div>

<script>
/* MODAL ADD PARQUE */
$("#openAddParque").click(function(){ $("#addParqueModal").css("display", "flex").hide().fadeIn(); });
$("#closeAddParque").click(function(){ $("#addParqueModal").fadeOut(); });

$("#add-parque-form").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "", type: "POST", data: formData,
        contentType: false, processData: false,
        success: function(res){
            $("#mensajeAdd").html(res);
            if(res.includes("correctamente")) location.reload();
        }
    });
});

/* OTROS MODALES */
$("#openRegistro").click(function(e){ e.preventDefault(); $("#registroModal").css("display", "flex").hide().fadeIn(); });
$("#openLogin").click(function(e){ e.preventDefault(); $("#loginModal").css("display", "flex").hide().fadeIn(); });
$("#closeRegistro").click(function(){ $("#registroModal").fadeOut(); });
$("#closeLogin").click(function(){ $("#loginModal").fadeOut(); });

$("#registro-form").submit(function(e){
    e.preventDefault();
    $.post("", $(this).serialize(), function(res){
        $("#mensaje").html(res);
        if(res==="Registro exitoso") location.reload();
    });
});

$("#login-form").submit(function(e){
    e.preventDefault();
    $.post("", $(this).serialize(), function(res){
        $("#mensajeLogin").html(res);
        if(res==="Login exitoso") location.reload();
    });
});
</script>
<script>
    $(document).ready(function() {
        
        /* CÓDIGO PARA ELIMINAR */
        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            var idParque = $(this).data("id");
            var cardElemento = $(this).closest(".card");

            if(confirm("¿Esta seguro de eliminarlo?")){
                $.post("", { eliminar_parque: 1, id: idParque }, function(res) {
                    if(res.trim() === "success") {
                        cardElemento.fadeOut(400, function() { $(this).remove(); });
                    } else {
                        alert("Error: " + res);
                    }
                });
            }
        });

      

    });
</script>
</body>
</html>