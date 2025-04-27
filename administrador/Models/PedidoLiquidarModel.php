<?php
class PedidoLiquidarModel extends MysqlPedidos
{
    private $db_name;
    private $db_nameAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
        $this->db_nameAdmin = $this->getDbNameMysqlAdmin();
    }
    public function consultarDatos(array $criterio = [])
    {
        try {
            $idsTie = $this->recuperarIdsTienda();

            $params = [];
            $where = ["a.cped_est_ped <> 4"]; // Excluir anulados

            // Tiendas disponibles para el usuario
            if (!empty($idsTie)) {
                $idsTie = implode(",", $idsTie);
                $where[] = "a.tie_id IN ($idsTie)";
            }

            // Extraer filtros del array
            $filtros = $criterio[0] ?? [];

            if (!empty($filtros)) {
                if (!empty($filtros['est_log']) && $filtros['est_log'] !== "0") {
                    $where[] = "a.cped_est_ped = :est_log";
                    $params[':est_log'] = $filtros['est_log'];
                }

                if (!empty($filtros['tie_id']) && (int) $filtros['tie_id'] > 0) {
                    $where[] = "a.tie_id = :tie_id";
                    $params[':tie_id'] = $filtros['tie_id'];
                }

                if (!empty($filtros['f_ini']) && !empty($filtros['f_fin'])) {
                    $where[] = "DATE(a.cped_fec_ped) BETWEEN :f_ini AND :f_fin";
                    $params[':f_ini'] = date('Y-m-d', strtotime($filtros['f_ini']));
                    $params[':f_fin'] = date('Y-m-d', strtotime($filtros['f_fin']));
                }
            }

            // Construcción del SQL
            $sql = "
            SELECT 
                a.cped_id AS Ids,
                a.tie_id AS tieid,
                a.cped_val_net AS total,
                DATE(a.cped_fec_ped) AS fechapedido,
                a.tcped_id AS tcped_id,
                b.tie_nombre AS nombretienda,
                b.tie_direccion AS direcciontienda,
                concat(e.per_nombre,' ',e.per_apellido)  AS nombrepersona,
                LPAD(a.cped_id, 9, '0') AS numero,
                a.cped_est_ped AS Estado,
                a.cped_est_env AS estenv
            FROM {$this->db_name}.cab_pedido a
            INNER JOIN {$this->db_name}.tienda b ON a.tie_id = b.tie_id
            INNER JOIN {$this->db_name}.usuario_tienda c ON c.utie_id = a.utie_id
            INNER JOIN {$this->db_nameAdmin}.usuario d ON c.usu_id = d.usu_id
            INNER JOIN {$this->db_nameAdmin}.persona e ON d.per_id = e.per_id
            WHERE " . implode(" AND ", $where) . "
            ORDER BY a.cped_id DESC
            LIMIT " . LIMIT_SQL;



            $resultado = $this->select_all($sql, $params);

            if ($resultado === false) {
                logFileSystem("Consulta fallida para consultarDatos", "WARNING");
                return [];
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en consultarDatos: " . $e->getMessage(), "ERROR");
            return [];
        }
    }

    public function recuperarIdsTienda(): array
    {
        $utieId = retornarDataSesion('Utie_id');
        if (empty($utieId)) {
            return [];
        }
        $sql = "
                SELECT a.tie_id
                FROM {$this->db_name}.usuario_tienda AS a
                WHERE a.utie_est_log = 1
                AND a.utie_id     = :utie_id
            ";
        $rows = $this->select_all($sql, [':utie_id' => $utieId]);
        return array_column($rows, 'tie_id');
    }


    public function facturarPedido(int $ids)
    {
        $con = $this->getConexion(); // Obtiene la conexión a la base de datos
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $usuario = retornaUser();
            $con->beginTransaction(); // Inicia una transacción
            $sqlUpdate = "UPDATE {$this->db_name}.cab_pedido 
                              SET cped_est_ped= :cped_est_ped, 
                              usuario=:usuario,cped_fec_mod = CURRENT_TIMESTAMP()
                              WHERE cped_id =:cped_id ";
            $stmtUpdate = $con->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':cped_id' => $ids,
                ':cped_est_ped' => 3,
                ':usuario' => $usuario
            ]);

            $con->commit(); // Confirma la transacción
            return ["status" => true, "message" => "Registro Anulado correctamente."];

        } catch (Exception $e) {
            $con->rollBack(); // Revierte la transacción en caso de error
            logFileSystem("Error en facturarPedido: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la Facturacion: " . $e->getMessage()];
        }
    }


    public function cabeceraPedido($ids)
    {
        try {
            $sql = "select a.cped_id pedid,concat(repeat( '0', 9 - length(a.cped_id) ),a.cped_id) numero,b.tie_id tieid,
                        a.cped_val_net total,date(a.cped_fec_ped) fechapedido,b.tie_nombre nombretienda, '' receptor
                        from {$this->db_name}.cab_pedido a
                                inner join {$this->db_name}.tienda b
                                        on a.tie_id=b.tie_id
                    where a.cped_id=:cped_id ;";
            $arrParams = [":cped_id" => $ids];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida cabeceraPedido", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en cabeceraPedido: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }

    public function detallePedido($ids)
    {
        try {
            $sql = "select a.dped_id detid,a.art_id artid,a.dped_can_ped cantidad,a.dped_p_venta precio,
                        a.dped_t_venta totvta,a.dped_est_log estaut,a.dped_observa observacion,b.cod_art codigo,
                        b.art_des_com nombre,b.art_i_m_iva imiva
                        from {$this->db_name}.det_pedido a
                                inner join {$this->db_name}.articulo b
                                        on a.art_id=b.art_id
                where a.cped_id=:cped_id ";
            $arrParams = [":cped_id" => $ids];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida detallePedido", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en detallePedido: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }





    

}
