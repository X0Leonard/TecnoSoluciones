<?php
session_start();
define('BASE_URL', '/TecnoSoluciones');
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

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm  = trim($_POST['confirm'] ?? '');

            if (empty($nombre) || empty($email) || empty($password)) {
                $error = 'Completa todos los campos.';
                require __DIR__ . '/../views/auth/register.php';
                return;
            }

            if ($password !== $confirm) {
                $error = 'Las contraseñas no coinciden.';
                require __DIR__ . '/../views/auth/register.php';
                return;
            }

            if ($this->usuario->emailExiste($email)) {
                $error = 'El email ya está registrado.';
                require __DIR__ . '/../views/auth/register.php';
                return;
            }

            $this->usuario->registrar($nombre, $email, $password);
            $success = 'Cuenta creada. Ya puedes iniciar sesión.';
            require __DIR__ . '/../views/auth/login.php';

        } else {
            require __DIR__ . '/../views/auth/register.php';
        }
    }

    public function dashboard() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . '/views/auth/login.php');
        exit;
    }
    header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index');
    exit;
}

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/views/auth/login.php');
        exit;
    }
}

// ===== EJECUTAR ACCIÓN =====
$controller = new AuthController();
$action = $_GET['action'] ?? 'login';

match($action) {
    'login'     => $controller->login(),
    'register'  => $controller->register(),
    'dashboard' => $controller->dashboard(),
    'logout'    => $controller->logout(),
    default     => $controller->login()
};