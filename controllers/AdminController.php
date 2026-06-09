<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index');
    exit;
}

require_once __DIR__ . '/../models/Usuario.php';

class AdminController {

    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    public function index() {
        $usuarios    = $this->usuario->obtenerTodos();
        $page_title  = 'Gestión de Usuarios';
        $active_page = 'admin';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/admin/index.php';
    }

    public function create() {
        $page_title  = 'Nuevo Usuario';
        $active_page = 'admin';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/admin/create.php';
    }

    public function store() {
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm'] ?? '');
        $rol      = in_array($_POST['rol'] ?? '', ['admin', 'trabajador']) ? $_POST['rol'] : 'trabajador';

        $page_title  = 'Nuevo Usuario';
        $active_page = 'admin';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';

        if (empty($nombre) || empty($email) || empty($password)) {
            $error = 'completa todos los campos.';
            require __DIR__ . '/../views/admin/create.php';
            return;
        }
        if ($password !== $confirm) {
            $error = 'las contraseñas no coinciden.';
            require __DIR__ . '/../views/admin/create.php';
            return;
        }
        if ($this->usuario->emailExiste($email)) {
            $error = 'el email ya está registrado.';
            require __DIR__ . '/../views/admin/create.php';
            return;
        }

        $this->usuario->registrar($nombre, $email, $password, $rol);
        header('Location: ' . BASE_URL . '/controllers/AdminController.php?action=index&success=1');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === (int)$_SESSION['usuario_id']) {
            header('Location: ' . BASE_URL . '/controllers/AdminController.php?action=index&error=self');
            exit;
        }
        $this->usuario->eliminar($id);
        header('Location: ' . BASE_URL . '/controllers/AdminController.php?action=index&success=2');
        exit;
    }
}

// ===== ejecutar acción =====
$controller = new AdminController();
$action = $_GET['action'] ?? 'index';

match($action) {
    'index'  => $controller->index(),
    'create' => $controller->create(),
    'store'  => $controller->store(),
    'delete' => $controller->delete(),
    default  => $controller->index()
};