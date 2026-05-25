<?php session_start(); ?>
   <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dividendo</title>

    <link rel="stylesheet" href="Css/dividendo.css">
   

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
                <li><a href="Index.php"><b>Inicio</b></a></li>
                <li><a href="#ods"><b>ODS13</b></a></li>
            
          <?php if(isset($_SESSION['user_id'])): ?>
    <li><a href="logout.php"><b>Cerrar sesión</b></a></li>
<?php else: ?>
    <li><a href="#" id="openLogin"><b>Iniciar sesión</b></a></li>
    <li><a href="#" class="openModalBtn"><b>Registrarse</b></a></li>
<?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- SIDEBAR -->
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
            <h3 class="sidebar-logo">Dividendo Beta</h3>
        <?php endif; ?>
    </div>

    <hr class="sidebar-divider">

    <nav class="sidebar-nav">
       
      
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="#" class="openModalBtn">Registrarse</a>
        <?php endif; ?>
    </nav>
</aside>

<div class="overlay" onclick="toggleSidebar()"></div>

<section id="inicio" class="hero">
   
   <img src="uploads/flores.jpg " class = "video-bd">
        </div>

    
</section>

<section class="arbustos">

    <h2 class="titulo-seccion">Fabiantarina</h2>

    <div class="circulos-container">

        <div class="circulo-item">
            <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/09/aa/04/2d/img-20151120-171511-largejpg.jpg?w=1400&h=-1&s=1" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://i.pinimg.com/736x/03/4c/54/034c542113b41653e88b3c6d5a2b8f04.jpg" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweqAV6FwSkmanKw1Q0yMXcSfbSYLqF3vZD96SXspDmUTJNTxYB203OlDqqMrFlDvuJg7XcRVKz-b8wiAcooS4cSs7njBWDiUnufh_o6r7oGE6dwAv6trn6Z0P_tj8FFyzmAcStA=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweoRsPkclQSr-BoOubQp7LC1UOROGbwDnmuOJcvKuQmXrp6dqNfDqGHvBxem2TAH6bCnwinM_bkiakrlzzwpWYxwDJt_0diuXGkeJKgNBv8y9MK8pLLhYbnqW36ZPA0LA1v9fCghaA=s1360-w1360-h1020-rw" alt="">
        </div>
    


    </div>
    <div class="btn-center">
        <a href="Santa Catarina.php" class="main-btn">Ver más</a>
    </div>

<!-- MODAL DE REGISTRO -->
<div id="registroModal" class="modal">
    <div class="modal-content">
        <span id="closeModal">X</span>
        <h2>Registrarse</h2>

        <form id="registro-form">
            <input type="text" name="Name" placeholder="Nombre" required>
            <input type="email" name="Hotmail" placeholder="Correo" required>
            <input type="password" name="Password" placeholder="Contraseña" required>
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
            <input type="email" name="Hotmail" placeholder="Correo" required>
            <input type="password" name="Password" placeholder="Contraseña" required>
            <input type="hidden" name="login" value="1">
            <button type="submit">Entrar</button>
        </form>

        <div id="mensajeLogin"></div>
    </div>
</div>
<script>
// Sidebar
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
}

// Scroll efecto header
var header = document.querySelector('header');
window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Abrir modal
$(".openModalBtn").click(function(e){
    e.preventDefault();
    $("#registroModal").fadeIn();
});

// Cerrar modal
$("#closeModal").click(function(){
    $("#registroModal").fadeOut();
});

// Cerrar si clic afuera
$(window).click(function(e){
    if($(e.target).is("#registroModal")){
        $("#registroModal").fadeOut();
    }
});

// Enviar registro
$("#registro-form").submit(function(e){
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "register.php",
        data: $(this).serialize(),
        success: function(response){
            $("#mensaje").html(response);
            $("#registro-form")[0].reset();
        }
    });
});

// abrir login
$("#openLogin").click(function(e){
    e.preventDefault();
    $("#loginModal").fadeIn();
});

// cerrar login
$("#closeLogin").click(function(){
    $("#loginModal").fadeOut();
});

// enviar login
$("#login-form").submit(function(e){
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "register.php", // usa el mismo endpoint
        data: $(this).serialize(),
        success: function(response){
            $("#mensajeLogin").html(response);

            if(response === "Login exitoso"){
                location.reload();
            }
        }
    });
});
</script>

</body>
</html>

   

 <!-- Santa catarina-->
<section class="arbustos">

    <h2 class="titulo-seccion">Monterrey</h2>

    <div class="circulos-container">

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwerVM_H9sMIRUN-a_laUQ0Nj-Lume3w0X3Srn7-IxnB-ufLOxnxtP62uItzb0eNENXpNOcYYlxy0T8mguKmsvqmi011GAz8wf3pSzboVfxn-lFVS25awt2ohf2vongiuRXvDi-BuUg=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweqMsJGbjkJ0WnET0YXNLVImwdn-sYa5nguMyCYovEDePDMeteui74oMnVDcOxtXyeoDr_BQFM41s-d5EXw9byNg9bpcJ5lHTeu2qkT7VT2QLL5Y3DLoWpvQyC-lzANJ4bdAQMTC=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepWvnfS1RaQtAXiliv8e_lUVwc4RzrL3sCp_HXthi4VOMM77CSpyqQCN8DGUzP4OT-yW5DWpwHsg95ENKPgOnby4qF1xoFOVguC00tdUg6ehnFb05P4BC47knvgqDy5-3UYEG1e0g=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepSJ-Um2cOO-BNTw1_jW1UTSAMwGKF3P6T1q4sUydjozl9mVeZTfiHt8ooRl23bLwPI-5kjX9wW2qKke7_mZ5OXdIYROKjzjvpwZ3s7kipdIwPCONmgxwZNmdxWG96tAPkWxHYsdw=s1360-w1360-h1020-rw" alt="">
        </div>

    </div>
        <div class="btn-center">
        <a href="Monterrey.php" class="main-btn">Ver más</a>
    </div>

</section>

 <!-- Santa catarina-->
<section class="arbustos">

    <h2 class="titulo-seccion">Garcia</h2>

    <div class="circulos-container">

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepBk1sZtEBKlYrKpzFtO8_fWrRaq3QubA3DJ6W_4MjQZtZMwnRBDBPPpq8ruELrOpD28F4INlfNlbTtlKfdQffN0lp83BPUj-A4zAQwfaPOQvjQZ50i96cFtgM-o9BsEn8favfsgw=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweqP1HWgd4b-3ALClAP5AQfS_iG7knls7VICBI4x4eg9DxrhK8ItviIxg__I0zS4DAlryUBtlq1uiLjBORJMXMPJP-EkI7-LHZArZTQbNyojDRYAr4NTtRYg7nsZPgzl8hTHNNQ=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepJFMlgrhduXr6DA2Bn-yf9GMaJZU3IDezm5I_iC5OdFo1zUQChSzhyLlopk2n5DdnDeLIwPuG_DGXFvHPU_9tfu1QzhPEmsJN67LkNRLyVy2_tM777wKN3QhAqVzhJKfsG0pXc=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepwpbuMlYEdH82BqbXSsuRi_ReAbsmQhO94yipDjROFWbGSDoxlZL_SQSj7Lcbuq6BiS8Mnoohz67lpuamp_5653cpbnalG3vOSSih0NQrpV9WEk1zSU4s0f5KSriBEMPFrpYm3Iw=s1360-w1360-h1020-rw" alt="">
        </div>

    </div>
    <div class="btn-center">
        <a href="Garcia.php" class="main-btn">Ver más</a>
    </div>
</section>

 <!-- San pEdro-->
<section class="arbustos">

    <h2 class="titulo-seccion">San Pedro</h2>

    <div class="circulos-container">

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweqWTJ_smGjdTH-eZatP4hU4UXd7SBfFsqXjFfIr924S68aocpQFiZPlPdoS2ccaSjwjnXQjVQ0OmeVV2tXrs9tmvTOCap2hOjpMik90Yne0MmOregIi1cPigDRyDTBiyKDd9RRuL0SBdaU=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweo1oHnzQ_n_SeMHg9hAS49sxqoyReFVPqqEPxKTnMzIOgyL1ZV0EAYqN1ozaZr6ZuIqBfKSleQgIS1EcE-Kz_EbImuIGM64OA_JgHEP9xRIuzHW_3H6ubA2lZ-Z3ttE9Fu69K0eUbqd8Fo=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAweq8QpBr5CMOlv5QjaFLXWRUr0awLvAXoGKxInNV6T_qRLgTtZCOLMK5RIN1diWZMf3A5v07InTiIRNKvVHWBmjdtrg7TooIuJeFJKA-CEMGYnXWNs78n8azldqYb_igtwf2cyh9Lw=s1360-w1360-h1020-rw" alt="">
        </div>

        <div class="circulo-item">
            <img src="https://lh3.googleusercontent.com/gps-cs-s/AHVAwepWvnfS1RaQtAXiliv8e_lUVwc4RzrL3sCp_HXthi4VOMM77CSpyqQCN8DGUzP4OT-yW5DWpwHsg95ENKPgOnby4qF1xoFOVguC00tdUg6ehnFb05P4BC47knvgqDy5-3UYEG1e0g=s1360-w1360-h1020-rw" alt="">
        </div>

    </div>
        <div class="btn-center">
        <a href="SanPedro.php" class="main-btn">Ver más</a>
    </div>

</section>

<div id="registroModal" class="modal">
    <div class="modal-content">
        <span id="closeModal" style="cursor:pointer;">X</span>
        <h2>Registrarse</h2>

        <form id="registro-form">
            <input type="text" name="Name" placeholder="Nombre" required><br><br>
            <input type="email" name="Hotmail" placeholder="Correo" required><br><br>
            <input type="password" name="Password" placeholder="Contraseña" required><br><br>
            <button type="submit">Registrar</button>
        </form>

        <div id="mensaje"></div>
    </div>
</div>

<!-- SCRIPTS -->

</script>




    </body>
    </head>
    </html>