<?php
session_start();
define('BASE_URL', '');
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = 'Completa todos los campos.';
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            $user = $this->usuario->login($email, $password);

            if ($user) {
                $_SESSION['usuario_id']     = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['usuario_rol']    = $user['rol'];
                header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index');
                exit;
            } else {
                $error = 'Email o contraseña incorrectos.';
                require __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/controllers/AuthController.php?action=login');
        exit;
    }
}

// ===== EJECUTAR ACCIÓN =====
$controller = new AuthController();
$action = $_GET['action'] ?? 'login';

match($action) {
    'login'  => $controller->login(),
    'logout' => $controller->logout(),
    default  => $controller->login()
};