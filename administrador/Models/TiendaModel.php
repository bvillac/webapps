<?php
class TiendaModel extends MysqlPedidos
{
    private $db_name;
    private $db_nameAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
        $this->db_nameAdmin = $this->getDbNameMysqlAdmin();
    }

    // 🔹 Consultar todas las tiendas
    public function consultarDatos()
    {
        $sql = "SELECT a.tie_id AS Ids, a.tie_nombre AS NombreTienda, a.tie_direccion AS Direccion,
                       a.tie_cupo AS Cupo, b.cli_razon_social AS RazonSocial, 
                       a.tie_contacto AS ContactoTienda, a.tie_est_log AS Estado
                FROM {$this->db_name}.tienda a
                INNER JOIN {$this->db_nameAdmin}.cliente b ON a.cli_id = b.cli_id
                WHERE a.tie_est_log != 0";
        return $this->select_all($sql);
    }

    // 🔹 Consultar datos de un salón por ID
    public function consultarDatosId(int $Ids)
    {
        $sql = "SELECT a.tie_id AS Ids, a.tie_nombre AS NombreTienda, a.tie_direccion AS Direccion,a.tie_telefono as Telefono,a.tie_lug_entrega as LugarEntrega,
                       a.tie_cupo AS Cupo, b.cli_razon_social AS RazonSocial, a.fec_ini_ped as FecIni, a.fec_fin_ped as FecFin,
                       a.tie_contacto AS ContactoTienda, a.tie_est_log AS Estado,date(a.tie_fec_cre) as FechaIngreso,a.cli_id as Cli_Ids
                FROM {$this->db_name}.tienda a
                INNER JOIN {$this->db_nameAdmin}.cliente b ON a.cli_id = b.cli_id
                WHERE a.tie_est_log != 0 and a.tie_id= :ids ";

        return $this->select($sql, [":ids" => $Ids]);
    }

    public function insertData(array $dataObj)
    {
        $con = $this->getConexion();
        $arroout = ["status" => false];
        try {
            // Validar si la tienda ya existe
            $sqlCheck = "SELECT 1 FROM {$this->db_name}.tienda 
                         WHERE tie_nombre = :nombre AND cli_id = :clienteID";
            $paramsCheck = [":nombre" => $dataObj['nombreTienda'], ":clienteID" => $dataObj['cliente_id']];
            if (!empty($this->select($sqlCheck, $paramsCheck))) {
                return ["status" => false, "message" => "Ya existe una Registro con este nombre para este cliente."];
            }

            // Iniciar transacción
            $con->beginTransaction();

            // Insertar nuevo registro
            $sqlInsert = "INSERT INTO {$this->db_name}.tienda 
                          ( cli_id, tie_nombre, tie_direccion, tie_telefono, tie_cupo, 
                           tie_lug_entrega, tie_contacto, fec_ini_ped, fec_fin_ped, tie_est_log, 
                           tie_fec_cre, tie_fec_mod) 
                          VALUES (:cli_id, :tie_nombre, :tie_direccion, :tie_telefono, :tie_cupo, 
                                  :tie_lug_entrega, :tie_contacto, :fec_ini_ped, :fec_fin_ped, :tie_est_log, 
                                  CURRENT_TIMESTAMP(), NULL)";

            $paramsInsert = [
                ":cli_id"         => $dataObj['cliente_id'],
                ":tie_nombre"     => $dataObj['nombreTienda'],
                ":tie_direccion"  => $dataObj['direccion'],
                ":tie_telefono"   => $dataObj['telefono'],
                ":tie_cupo"       => $dataObj['cupo'],
                ":tie_lug_entrega"=> $dataObj['lugar'],
                ":tie_contacto"   => $dataObj['contacto'],
                ":fec_ini_ped"    => $dataObj['diainicio'],
                ":fec_fin_ped"    => $dataObj['diafin'],
                ":tie_est_log"    => 1 // Estado activo
            ];

            $this->insertConTrans($con, $sqlInsert, $paramsInsert);
            $con->commit();

            return ["status" => true, "message" => "Registro ingresado exitosamente"];
        } catch (Exception $e) {
            $con->rollBack();
            putMessageLogFile("ERROR en insertData: " . $e->getMessage());
            return ["status" => false, "message" => "Error en la inserción: " . $e->getMessage()];
        }
    }

    // 🔹 Actualizar datos de una tienda
    public function updateData(array $dataObj)
    {
        putMessageLogFile($dataObj);
        try {
            $sql = "UPDATE {$this->db_name}.tienda 
                    SET cli_id = :cli_id, tie_nombre = :tie_nombre, tie_direccion = :tie_direccion, 
                        tie_telefono = :tie_telefono, tie_cupo = :tie_cupo, tie_lug_entrega = :tie_lug_entrega, 
                        tie_contacto = :tie_contacto, fec_ini_ped = :fec_ini_ped, fec_fin_ped = :fec_fin_ped, 
                        tie_est_log = :tie_est_log, tie_fec_mod = CURRENT_TIMESTAMP() 
                    WHERE tie_id = :tie_id";

            $params = [
                ":tie_id"         => $dataObj['ids'],
                ":cli_id"         => $dataObj['cliente_id'],
                ":tie_nombre"     => $dataObj['nombreTienda'],
                ":tie_direccion"  => $dataObj['direccion'],
                ":tie_telefono"   => $dataObj['telefono'],
                ":tie_cupo"       => $dataObj['cupo'],
                ":tie_lug_entrega"=> $dataObj['lugar'],
                ":tie_contacto"   => $dataObj['contacto'],
                ":fec_ini_ped"    => $dataObj['diainicio'],
                ":fec_fin_ped"    => $dataObj['diafin'],
                ":tie_est_log"    => 1 // Estado activo
            ];
            $request = $this->update($sql, $params);
            return ["status" => (bool)$request, "message" => $request ? "Tienda actualizada" : "No se pudo actualizar"];
        } catch (Exception $e) {
            putMessageLogFile("ERROR en updateData: " . $e->getMessage());
            return ["status" => false, "message" => "Fallo en la actualización: " . $e->getMessage()];
        }
    }
    // 🔹 Eliminar (deshabilitar) un salón
    public function deleteRegistro(int $Ids)
    {
        $sql = "UPDATE {$this->db_name}.tienda 
                SET tie_est_log = :estado, 
                tie_fec_mod = CURRENT_TIMESTAMP() 
                WHERE tie_id = :id";
        //sal_usuario_modificacion = :usuario, 
        $params = [
            ":id" => $Ids,
            ":estado" => 0
            //":usuario" => retornaUser()
        ];

        return $this->update($sql, arrValues: $params);
    }

   
}
