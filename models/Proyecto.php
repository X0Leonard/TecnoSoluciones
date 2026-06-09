<?php
require_once __DIR__ . '/Model.php';

class Proyecto extends Model {

    public function obtenerTodos() {
        $sql = "select p.*, c.nombre as cliente_nombre
                from proyectos p
                inner join clientes c on p.cliente_id = c.id
                order by p.created_at desc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "select p.*, c.nombre as cliente_nombre
                from proyectos p
                inner join clientes c on p.cliente_id = c.id
                where p.id = :id limit 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $descripcion, $estado, $cliente_id, $fecha_inicio, $fecha_fin) {
        $sql = "insert into proyectos (nombre, descripcion, estado, cliente_id, fecha_inicio, fecha_fin)
                values (:nombre, :descripcion, :estado, :cliente_id, :fecha_inicio, :fecha_fin)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':estado'       => $estado,
            ':cliente_id'   => $cliente_id,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin'    => $fecha_fin
        ]);
    }

    public function actualizar($id, $nombre, $descripcion, $estado, $cliente_id, $fecha_inicio, $fecha_fin) {
        $sql = "update proyectos set nombre = :nombre, descripcion = :descripcion,
                estado = :estado, cliente_id = :cliente_id,
                fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin
                where id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':estado'       => $estado,
            ':cliente_id'   => $cliente_id,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin'    => $fecha_fin
        ]);
    }

    public function eliminar($id) {
        $sql = "delete from proyectos where id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function cambiarEstado($id, $estado) {
        $stmt = $this->db->prepare("update proyectos set estado = :estado where id = :id");
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    public function contar() {
        $stmt = $this->db->prepare("select count(*) as total from proyectos");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function contarPorEstado($estado) {
        $stmt = $this->db->prepare("select count(*) as total from proyectos where estado = :estado");
        $stmt->execute([':estado' => $estado]);
        return $stmt->fetch()['total'];
    }
}