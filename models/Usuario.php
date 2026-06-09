<?php
require_once __DIR__ . '/Model.php';

class Usuario extends Model {

    public function registrar($nombre, $email, $password, $rol = 'trabajador') {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql  = "insert into usuarios (nombre, email, password, rol) values (:nombre, :email, :password, :rol)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':password' => $hash,
            ':rol'      => $rol
        ]);
    }

    public function login($email, $password) {
        $sql = "select * from usuarios where email = :email limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function emailExiste($email) {
        $sql = "select id from usuarios where email = :email limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function obtenerTodos() {
        $stmt = $this->db->prepare("select id, nombre, email, rol, created_at from usuarios order by created_at desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("delete from usuarios where id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("select id, nombre, email, rol from usuarios where id = :id limit 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}