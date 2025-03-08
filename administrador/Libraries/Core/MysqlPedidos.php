<?php

class MysqlPedidos extends ConPedidos
{
    private $conexion;
    private $db_name;
    private $db_nameAdmin = DB_NAME;

    function __construct()
    {
        parent::__construct();
        $this->conexion = $this->conect();
        $this->db_name = $this->getDbName();
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function getDbNameMysql()
    {
        return $this->db_name;
    }

    public function getDbNameMysqlAdmin()
    {
        return $this->db_nameAdmin;
    }

    // 🔹 Insertar un registro
    public function insert(string $query, array $arrValues)
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($arrValues);
            return $this->conexion->lastInsertId();
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return 0;
        }
    }

    // 🔹 Consultar un registro
    public function select(string $query, array $params = [])
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return null;
        }
    }

    // 🔹 Consultar todos los registros
    public function select_all(string $query, array $params = [])
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return [];
        }
    }

    // 🔹 Actualizar registros
    public function update(string $query, array $arrValues)
    {
        try {
            $stmt = $this->conexion->prepare($query);
            return $stmt->execute($arrValues);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 Eliminar un registro (actualiza el estado lógico en vez de hacer DELETE)
    public function delete(string $query, array $params = [])
    {
        try {
            $stmt = $this->conexion->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 Insertar con transacción
    public function insertConTrans($con, string $query, array $arrValues)
    {
        try {
            $stmt = $con->prepare($query);
            $stmt->execute($arrValues);
            return $con->lastInsertId();
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return 0;
        }
    }

    // 🔹 Actualizar con transacción
    public function updateConTrans($con, string $query, array $arrValues)
    {
        try {
            $stmt = $con->prepare($query);
            return $stmt->execute($arrValues);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR: " . $e->getMessage());
            return false;
        }
    }
}
