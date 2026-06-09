<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Proyecto.php';

function esAdmin() {
    return ($_SESSION['usuario_rol'] ?? '') === 'admin';
}

function soloAdmin() {
    if (!esAdmin()) {
        header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index&error=permiso');
        exit;
    }
}

class ClienteController {

    private $cliente;
    private $proyecto;

    public function __construct() {
        $this->cliente  = new Cliente();
        $this->proyecto = new Proyecto();
    }

    public function index() {
        $clientes    = $this->cliente->obtenerTodos();
        $page_title  = 'Clientes';
        $active_page = 'clientes';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/clientes/index.php';
    }

    public function create() {
        soloAdmin();
        $page_title  = 'Nuevo Cliente';
        $active_page = 'clientes';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/clientes/create.php';
    }

    public function store() {
        soloAdmin();
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $empresa  = trim($_POST['empresa'] ?? '');

        if (empty($nombre) || empty($email)) {
            $error       = 'Nombre y email son obligatorios.';
            $page_title  = 'Nuevo Cliente';
            $active_page = 'clientes';
            $css_path    = BASE_URL . '/public/css/style.css';
            $base_path   = BASE_URL . '/';
            require __DIR__ . '/../views/clientes/create.php';
            return;
        }

        $this->cliente->crear($nombre, $email, $telefono, $empresa);
        header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index&success=1');
        exit;
    }

    public function edit() {
        soloAdmin();
        $id      = $_GET['id'] ?? 0;
        $cliente = $this->cliente->obtenerPorId($id);

        if (!$cliente) {
            header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index');
            exit;
        }

        $page_title  = 'Editar Cliente';
        $active_page = 'clientes';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/clientes/edit.php';
    }

    public function update() {
        soloAdmin();
        $id       = $_POST['id'] ?? 0;
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $empresa  = trim($_POST['empresa'] ?? '');

        $this->cliente->actualizar($id, $nombre, $email, $telefono, $empresa);
        header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index&success=2');
        exit;
    }

    public function delete() {
        soloAdmin();
        $id = $_GET['id'] ?? 0;
        $this->cliente->eliminar($id);
        header('Location: ' . BASE_URL . '/controllers/ClienteController.php?action=index&success=3');
        exit;
    }
}

// ===== EJECUTAR ACCIÓN =====
$controller = new ClienteController();
$action = $_GET['action'] ?? 'index';

match($action) {
    'index'  => $controller->index(),
    'create' => $controller->create(),
    'store'  => $controller->store(),
    'edit'   => $controller->edit(),
    'update' => $controller->update(),
    'delete' => $controller->delete(),
    default  => $controller->index()
};