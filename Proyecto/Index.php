<?php
session_start();
require_once "conexion.php";
require_once "logs.php";

mysqli_report(MYSQLI_REPORT_OFF);

// =========================
// REGISTRO
// =========================
if(isset($_POST["registro"])){

    $nombre = trim($_POST["Name"]);
    $correo = trim($_POST["Hotmail"]);
    $password = $_POST["Password"];

    // Validar campos vacíos
    if(empty($nombre) || empty($correo) || empty($password)){
        echo "Todos los campos son obligatorios";
        exit();
    }

    // Validar formato del correo
    if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        echo "Correo no válido";
        exit();
    }

    // Validar contraseña fuerte
    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)){
        echo "La contraseña debe tener mínimo 8 caracteres, incluir mayúscula, minúscula, número y símbolo";
        exit();
    }

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $nombre, $correo, $passwordHash);

    if($stmt->execute()){

        // LOGIN AUTOMÁTICO
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["nombre"] = $nombre;
        $_SESSION["role"] = "user";

        echo "Registro exitoso";

    } else {

        if($conn->errno == 1062){
            echo "Ese correo ya está registrado";
        } else {
            echo "Error al registrar";
        }

    }

    exit();
}

    // =========================
    // LOGIN
    // =========================
    if(isset($_POST["login"])){

        $correo = trim($_POST["Hotmail"]);
        $password = $_POST["Password"];

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){

            $user = $result->fetch_assoc();

            if(password_verify($password, $user["password"])){

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["nombre"] = $user["nombre"];
                $_SESSION["role"] = $user["role"];
                  $_SESSION["foto"] = $user["foto"];
                  guardarLog($conn, $user["id"], "Inicio de sesión");

                echo "Login exitoso";

            } else {
                echo "Contraseña incorrecta";
            }

        } else {
            echo "Usuario no encontrado";
        }

        exit();
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dividendo</title>

<link rel="stylesheet" href="lala.css">
<link rel="stylesheet" href="mongus.css">



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<header>
<div class="container"> 
<div class="logo" onclick="toggleSidebar()">
<?php if(isset($_SESSION['user_id'])): ?>
    Hola, <?php echo $_SESSION['nombre']; ?>
<?php else: ?>
    Dividendo Beta
<?php endif; ?>
</div>

<nav>
<ul>
<li><a href="#inicio" class="btn-inicio-style"><b>Inicio</b></a></li>
<li><a href="dividendo.php"><b>Donar</b></a></li>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<li><a href="users.php"><b>Usuarios</b></a></li>
<?php endif; ?>

<?php if(isset($_SESSION['user_id'])): ?>
<li><a href="logout.php"><b>Cerrar sesión</b></a></li>
<?php else: ?>
<li><a href="#" class="btn-green-style btnOpenLogin"><b>Iniciar sesión</b></a></li>
<li><a href="#" class="btn-green-style btnOpenRegistro"><b>Registrarse</b></a></li>
<?php endif; ?>

</ul>
</nav>
</div>
</header>

<aside class="sidebar" id="sidebar">
    <div class="user-profile-header">
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="profile-pic-container">
                <img src="uploads/<?php echo $_SESSION['foto'] ?? 'quefeo.png'; ?>" alt="Foto de Perfil" class="sidebar-profile-img">
            </div>
            <h4 class="sidebar-username"><?php echo $_SESSION['nombre']; ?></h4>
            <div class="profile-actions">
                <a href="perfil.php" class="profile-link">Mi Perfil</a>
                <a href="logout.php" class="logout-btn">Cerrar sesión</a>
            </div>
        <?php else: ?>
            <h3 class="sidebar-logo">Dividendo</h3>
        <?php endif; ?>
    </div>

    <hr class="sidebar-divider">

   <nav class="sidebar-nav">


<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<a href="users.php"><i class="icon"></i> Usuarios</a>
<?php endif; ?>

<?php if(!isset($_SESSION['user_id'])): ?>
<li><a href="#" id="openLogin"><b>Iniciar sesión</b></a></li>
<a href="#" class="openModalBtn">Registrarse</a>
<?php endif; ?>

</nav>
</aside>

<!-- HERO -->
<section id="inicio" class="hero">
<img src="https://i.pinimg.com/1200x/e4/d4/0d/e4d40db20a6abb1ad042cb71358dd629.jpg" class="video-bg">
<div class="hero-content">
<h1>Dividendo</h1>
<p>Innovando en el manejo de residuos para reducir la contaminación</p>
</div>
</section>

<!-- ODS -->
<section id="ods" class="about">
<video autoplay muted loop playsinline class="video-bg">
<source src="Gato trabajando.mp4" type="video/mp4">
</video>
<div class="content">
<img src="https://www.pactomundial.org/wp-content/uploads/2021/10/13-accion-por-el-clima-3.jpg">
<div class="text">
<h2>ODS 13. Acción por Maduro</h2>
<p>
El cambio climático es uno de los mayores problemas actuales. En Dividendo, buscamos soluciones para reducir la contaminación mediante el manejo adecuado de residuos y el uso de tecnologías sostenibles.
</p>

<p>
Promovemos una cultura ecológica donde todos participen en el cuidado del planeta.
</p>
<a href="https://www.gob.mx/imjuve/es/articulos/objetivo-13-accion-por-el-clima-jovenes-el-presente-del-planeta?idiom=es" class="btn">Más sobre la ODS</a>
</div>
</div>
</section>

<!-- KEM -->
<section id="kem" class="products">
<div class="product-grid">
<div class="product-card">
<img src="https://i.pinimg.com/1200x/a2/fd/63/a2fd63750fb0bc6de252897601afccfa.jpg">
<p>No hay planeta B</p>
</div>
<div class="product-card">
<img src="https://i.pinimg.com/1200x/df/8d/01/df8d0153dbbfde677ed0208283e4eed8.jpg">
<p>Cuidemos el planeta</p>
</div>
<div class="product-card">
<img src="https://i.pinimg.com/736x/bb/7b/c0/bb7bc0a421d86ca8fef42627f6529c09.jpg">
<p>Actúa ahora</p>
</div>
</div>
<div class="btn-center">
<a href="Dividendo.php" class="main-btn">Ver Todos</a>
</div>
</section>



<!-- MODAL REGISTRO -->
<div id="registroModal" class="modal">
<div class="modal-content">
<span id="closeModal" style="cursor:pointer;">X</span>
<h2>Registrarse</h2>

<form id="registro-form">
<input type="text" name="Name" placeholder="Nombre" required><br><br>
<input type="email" name="Hotmail" placeholder="Correo" required><br><br>
<input type="password" 
name="Password" 
placeholder="Contraseña"
pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$"
title="Debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo"
required>
<input type="hidden" name="registro" value="1">
<button type="submit">Registrar</button>
</form>

<div id="mensaje"></div>
</div>
</div>

<!-- MODAL LOGIN -->
<div id="loginModal" class="modal">
<div class="modal-content">
<span id="closeLogin" style="cursor:pointer;">X</span>
<h2>Iniciar sesión</h2>

<form id="login-form">
<input type="email" name="Hotmail" placeholder="Correo" required><br><br>
<input type="password" name="Password" placeholder="Contraseña" required><br><br>
<input type="hidden" name="login" value="1">
<button type="submit">Entrar</button>
</form>

<div id="mensajeLogin"></div>
</div>
</div>

<script>
function toggleSidebar() {
document.getElementById("sidebar").classList.toggle("active");
}

$(".openModalBtn").click(function(e){
e.preventDefault();
$("#registroModal").addClass("show");
});

$("#openLogin").click(function(e){
e.preventDefault();
$("#loginModal").addClass("show");
});

$("#closeModal").click(function(){
    $("#registroModal").removeClass("show");
});

$("#closeLogin").click(function(){
    $("#loginModal").removeClass("show");
});

$("#registro-form").submit(function(e){
e.preventDefault();

$.post("", $(this).serialize(), function(response){

$("#mensaje").html(response);

if(response === "Registro exitoso"){
$("#registroModal").fadeOut();

setTimeout(function(){
location.reload();
}, 800);

}

});
});

$("#login-form").submit(function(e){
e.preventDefault();
$.post("", $(this).serialize(), function(response){
$("#mensajeLogin").html(response);
if(response === "Login exitoso"){
location.reload();
}
});
});
</script>

</body>
</html>