<?php
session_start();

//Si ya esta logueado, redirige al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: controllers/AuthController.php?action=dashboard");
    exit();
}

//Si no, va a la página de login
header("Location: views/auth/login.php");
exit();