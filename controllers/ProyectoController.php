<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../models/Proyecto.php';
require_once __DIR__ . '/../models/Cliente.php';

class ProyectoController {

    private $proyecto;
    private $cliente;

    public function __construct() {
        $this->proyecto = new Proyecto();
        $this->cliente  = new Cliente();
    }

    public function index() {
        $proyectos   = $this->proyecto->obtenerTodos();
        $page_title  = 'Proyectos';
        $active_page = 'proyectos';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/proyectos/index.php';
    }

    public function create() {
        $clientes    = $this->cliente->obtenerTodos();
        $page_title  = 'Nuevo Proyecto';
        $active_page = 'proyectos';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/proyectos/create.php';
    }

    public function store() {
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $estado       = $_POST['estado'] ?? 'pendiente';
        $cliente_id   = $_POST['cliente_id'] ?? 0;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin    = $_POST['fecha_fin'] ?? null;

        if (empty($nombre) || empty($cliente_id)) {
            $clientes    = $this->cliente->obtenerTodos();
            $error       = 'Nombre y cliente son obligatorios.';
            $page_title  = 'Nuevo Proyecto';
            $active_page = 'proyectos';
            $css_path    = BASE_URL . '/public/css/style.css';
            $base_path   = BASE_URL . '/';
            require __DIR__ . '/../views/proyectos/create.php';
            return;
        }

        $this->proyecto->crear($nombre, $descripcion, $estado, $cliente_id, $fecha_inicio, $fecha_fin);
        header('Location: ' . BASE_URL . '/controllers/ProyectoController.php?action=index&success=1');
        exit;
    }

    public function edit() {
        $id       = $_GET['id'] ?? 0;
        $proyecto = $this->proyecto->obtenerPorId($id);

        if (!$proyecto) {
            header('Location: ' . BASE_URL . '/controllers/ProyectoController.php?action=index');
            exit;
        }

        $clientes    = $this->cliente->obtenerTodos();
        $page_title  = 'Editar Proyecto';
        $active_page = 'proyectos';
        $css_path    = BASE_URL . '/public/css/style.css';
        $base_path   = BASE_URL . '/';
        require __DIR__ . '/../views/proyectos/edit.php';
    }

    public function update() {
        $id           = $_POST['id'] ?? 0;
        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $estado       = $_POST['estado'] ?? 'pendiente';
        $cliente_id   = $_POST['cliente_id'] ?? 0;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin    = $_POST['fecha_fin'] ?? null;

        $this->proyecto->actualizar($id, $nombre, $descripcion, $estado, $cliente_id, $fecha_inicio, $fecha_fin);
        header('Location: ' . BASE_URL . '/controllers/ProyectoController.php?action=index&success=2');
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->proyecto->eliminar($id);
        header('Location: ' . BASE_URL . '/controllers/ProyectoController.php?action=index&success=3');
        exit;
    }
}

// ===== EJECUTAR ACCIÓN =====
$controller = new ProyectoController();
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