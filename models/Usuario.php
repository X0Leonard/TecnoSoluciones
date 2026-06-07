<?php
require_once __DIR__ . '/Model.php';

class Usuario extends Model {

    public function registrar($nombre, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "insert into usuarios (nombre, email, password) values (:nombre, :email, :password)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':password' => $hash
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
}