<?php
require_once __DIR__ . '/Model.php';

class Cliente extends Model {

    public function obtenerTodos() {
        $sql = "select * from clientes order by created_at desc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "select * from clientes where id = :id limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $email, $telefono, $empresa) {
        $sql = "insert into clientes (nombre, email, telefono, empresa)
                values (:nombre, :email, :telefono, :empresa)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':telefono' => $telefono,
            ':empresa'  => $empresa
        ]);
    }

    public function actualizar($id, $nombre, $email, $telefono, $empresa) {
        $sql = "update clientes set nombre = :nombre, email = :email,
                telefono = :telefono, empresa = :empresa where id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'       => $id,
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':telefono' => $telefono,
            ':empresa'  => $empresa
        ]);
    }

    public function eliminar($id) {
        $sql = "delete from clientes where id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function contar() {
        $stmt = $this->db->prepare("select count(*) as total from clientes");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}