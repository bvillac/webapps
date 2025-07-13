<?php

class MysqlPedidos extends ConPedidos
{
    private PDO $conexion;
    private string $db_name;
    private string $db_nameAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->conexion = $this->conect();
        $this->db_name = $this->getDbName();
        $this->db_nameAdmin = DB_NAME;
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    public function getDbNameMysql(): string
    {
        return $this->db_name;
    }

    public function getDbNameMysqlAdmin(): string
    {
        return $this->db_nameAdmin;
    }

    /**
     * Inserta un registro y retorna el último ID insertado.
     */
    public function insert(string $query, array $arrValues): int
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($arrValues);
            return (int)$this->conexion->lastInsertId();
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [insert]: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Consulta un solo registro.
     */
    public function select(string $query, array $params = []): ?array
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [select]: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Consulta todos los registros.
     */
    public function select_all(string $query, array $params = []): array
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [select_all]: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualiza registros.
     */
    public function update(string $query, array $arrValues): bool
    {
        try {
            $stmt = $this->conexion->prepare($query);
            return $stmt->execute($arrValues);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [update]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina (lógicamente) un registro.
     */
    public function delete(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->conexion->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [delete]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Inserta un registro usando una conexión en transacción.
     */
    public function insertConTrans(PDO $con, string $query, array $arrValues): int
    {
        try {
            $stmt = $con->prepare($query);
            $stmt->execute($arrValues);
            return (int)$con->lastInsertId();
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [insertConTrans]: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Actualiza registros usando una conexión en transacción.
     */
    public function updateConTrans(PDO $con, string $query, array $arrValues): bool
    {
        try {
            $stmt = $con->prepare($query);
            return $stmt->execute($arrValues);
        } catch (PDOException $e) {
            putMessageLogFile("ERROR [updateConTrans]: " . $e->getMessage());
            return false;
        }
    }
}
