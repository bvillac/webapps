<?php
class ArticuloModel extends MysqlPedidos
{
    private $db_name;
    private $db_nameAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->db_name = $this->getDbNameMysql();
        $this->db_nameAdmin = $this->getDbNameMysqlAdmin();
    }

    public function consultarProductoCodigoNombre(string $parametro, int $cli_id, int $tie_id, int $limit = 10)
    {
        // Consulta SQL base con placeholders
        $sql = "SELECT 
                a.artie_id as Ids, 
                b.art_id, 
                c.cod_art, 
                c.art_des_com, 
                b.pcli_p_venta
            FROM {$this->db_name}.articulo_tienda a
            INNER JOIN {$this->db_name}.precio_cliente b 
                ON a.pcli_id = b.pcli_id AND b.pcli_est_log = 1
            INNER JOIN {$this->db_name}.articulo c 
                ON c.art_id = b.art_id
            WHERE a.artie_est_log != 0 
                AND b.cli_id = :cli_id 
                AND a.tie_id = :tie_id";

        // Parámetros para la consulta
        $params = [
            ':cli_id' => $cli_id,
            ':tie_id' => $tie_id
        ];

        // Verificar si el parámetro es numérico o alfanumérico
        if (!empty($parametro)) {
            if (ctype_digit($parametro)) {
                // Si el parámetro es numérico, buscar solo por código de artículo exacto
                $sql .= " AND c.cod_art LIKE :parametro";
                $params[':parametro'] = "%{$parametro}%";
            } else {
                // Si es alfanumérico, buscar en código y descripción
                $sql .= " AND (c.cod_art LIKE :parametro OR c.art_des_com LIKE :parametro)";
                $params[':parametro'] = "%{$parametro}%";
            }
        }

        // Agregar límite de registros
        $sql .= " LIMIT :limit";
        $params[':limit'] = (int) $limit; // Convertir explícitamente a entero por seguridad

        // Ejecutar consulta y devolver resultados
        return $this->select_all($sql, $params);
    }




    public function retornarBusArticulo(string $parametro, int $limit = 10)
    {
        // Consulta SQL base con placeholders
        $sql = "select art_id,cod_art,art_des_com as des_com,art_i_m_iva as i_m_iva,art_p_venta as p_venta"
            . " from {$this->db_name}.articulo "
            . "where art_est_log !=0 ";

        // Verificar si el parámetro es numérico o alfanumérico
        if (!empty($parametro)) {
            if (ctype_digit($parametro)) {
                // Si el parámetro es numérico, buscar solo por código de artículo exacto
                $sql .= " AND cod_art LIKE :parametro";
                $params[':parametro'] = "%{$parametro}%";
            } else {
                // Si es alfanumérico, buscar en código y descripción
                $sql .= " AND (cod_art LIKE :parametro OR art_des_com LIKE :parametro)";
                $params[':parametro'] = "%{$parametro}%";
            }
        }

        // Agregar límite de registros
        $sql .= " LIMIT {$limit}";
        //$params[':limit'] = (int) $limit; // Convertir explícitamente a entero por seguridad
        // Ejecutar consulta y devolver resultados
        return $this->select_all($sql, $params);
    }

    public function guardarProductosCliente(array $productos, $clienteId)
    {
        $con = $this->getConexion(); // Obtiene la conexión a la base de datos
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $con->beginTransaction(); // Inicia una transacción

            foreach ($productos as $producto) {
                $artId = $producto['art_id'];
                $codArt = $producto['cod_art'];
                $precioVenta = $producto['p_venta'];
                $i_m_iva = $producto['i_m_iva'];
                $por_des = $producto['por_des'];
                $val_des = $producto['val_des'];

                // Verificar si el producto ya existe para el cliente
                $sqlCheck = "SELECT pcli_id FROM {$this->db_name}.precio_cliente WHERE art_id = :art_id AND cli_id = :cli_id";
                $stmtCheck = $con->prepare($sqlCheck);
                $stmtCheck->execute([
                    ':art_id' => $artId,
                    ':cli_id' => $clienteId
                ]);
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    // Actualizar si ya existe
                    $sqlUpdate = "UPDATE {$this->db_name}.precio_cliente 
                              SET pcli_p_venta = :pcli_p_venta, 
                                  pcli_fec_mod = CURRENT_TIMESTAMP 
                              WHERE pcli_id = :pcli_id";
                    $stmtUpdate = $con->prepare($sqlUpdate);
                    $stmtUpdate->execute([
                        ':pcli_p_venta' => $precioVenta,
                        ':pcli_id' => $result['pcli_id']
                    ]);
                } else {
                    // Insertar si no existe
                    $sqlInsert = "INSERT INTO {$this->db_name}.precio_cliente 
                              (cli_id, art_id, cod_art, pcli_p_venta, pcli_est_log, pcli_fec_cre,pcli_i_m_iva,pcli_por_des,pcli_val_des) 
                              VALUES (:cli_id, :art_id, :cod_art, :pcli_p_venta, 1, CURRENT_TIMESTAMP,:pcli_i_m_iva,:pcli_por_des,:pcli_val_des)";
                    $stmtInsert = $con->prepare($sqlInsert);
                    $stmtInsert->execute([
                        ':cli_id' => $clienteId,
                        ':art_id' => $artId,
                        ':cod_art' => $codArt,
                        ':pcli_p_venta' => $precioVenta,
                        ':pcli_i_m_iva' => $i_m_iva,
                        ':pcli_por_des' => $por_des,
                        ':pcli_val_des' => $val_des
                    ]);
                }
            }

            $con->commit(); // Confirma la transacción
            return ["status" => true,"numero"=>0, "message" => "Registros guardados correctamente."];

        } catch (Exception $e) {
            $con->rollBack(); // Revierte la transacción en caso de error
            logFileSystem("Error en guardarProductosCliente: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
        }
    }

    public function consultarProductosCliente($cli_id)
    {
        try {
            $sql = "SELECT a.pcli_id, a.cli_id, a.art_id as art_id, a.cod_art as cod_art, b.art_des_com as des_com, 
                           a.pcli_p_venta as p_venta, a.pcli_i_m_iva as i_m_iva, a.pcli_por_des as por_des, a.pcli_val_des as val_des,
                           a.pcli_est_log
                    FROM {$this->db_name}.precio_cliente a
                    INNER JOIN {$this->db_name}.articulo b ON a.cod_art = b.cod_art
                    WHERE a.pcli_est_log != 0 AND a.cli_id = :cli_id 
                    ORDER BY b.art_des_com ASC";

            $arrParams = [":cli_id" => $cli_id];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida para cli_id: $cli_id", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en consultarProductosCliente: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }


    public function eliminarItemCliente($ids, $clienteId)
    {
        $con = $this->getConexion(); // Obtiene la conexión a la base de datos
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $usuario = retornaUser();
            $con->beginTransaction(); // Inicia una transacción

            $sqlUpdate = "UPDATE {$this->db_name}.precio_cliente 
                              SET pcli_est_log = :pcli_est_log, 
                                  pcli_fec_mod = CURRENT_TIMESTAMP 
                              WHERE pcli_id = :pcli_id";
            $stmtUpdate = $con->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':pcli_est_log' => 0,
                ':pcli_id' => $ids
            ]);

            $con->commit(); // Confirma la transacción
            return ["status" => true, "message" => "Registro Eliminado correctamente."];

        } catch (Exception $e) {
            $con->rollBack(); // Revierte la transacción en caso de error
            logFileSystem("Error en eliminarItemCliente: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la Eliminación: " . $e->getMessage()];
        }
    }


    public function guardarProductoTienda(array $productos, $tienda_id)
    {
        $con = $this->getConexion(); // Obtiene la conexión a la base de datos
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $con->beginTransaction(); // Inicia una transacción
            foreach ($productos as $producto) {
                // Verificar si el producto ya existe para el cliente
                $sqlCheck = "SELECT artie_id FROM {$this->db_name}.articulo_tienda WHERE pcli_id = :pcli_id AND tie_id = :tie_id";
                $stmtCheck = $con->prepare($sqlCheck);
                $stmtCheck->execute([
                    ':pcli_id' => $producto,
                    ':tie_id' => $tienda_id
                ]);
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    // Actualizar si ya existe
                    $sqlUpdate = "UPDATE {$this->db_name}.articulo_tienda 
                              SET artie_est_log = 1, 
                                  artie_fec_mod = CURRENT_TIMESTAMP 
                              WHERE artie_id = :artie_id";
                    $stmtUpdate = $con->prepare($sqlUpdate);
                    $stmtUpdate->execute([
                        ':artie_id' => $result['artie_id']
                    ]);
                } else {
                    // Insertar si no existe
                    $sqlInsert = "INSERT INTO {$this->db_name}.articulo_tienda 
                              (tie_id, pcli_id,  artie_est_log, artie_fec_cre) 
                              VALUES (:tie_id, :pcli_id,  1, CURRENT_TIMESTAMP)";
                    $stmtInsert = $con->prepare($sqlInsert);
                    $stmtInsert->execute([
                        ':tie_id' => $tienda_id,
                        ':pcli_id' => $producto
                    ]);
                }
            }
            $this->actualizaItemsTiendas($con,$tienda_id,$productos);
            $con->commit(); // Confirma la transacción
            return ["status" => true, "numero" => 0,"message" => "Registros guardados correctamente."];

        } catch (Exception $e) {
            $con->rollBack(); // Revierte la transacción en caso de error
            logFileSystem("Error en guardarProductosCliente: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
        }
    }

    private function actualizaItemsTiendas($con, $tieId, $array)
    {
        $intArray = array_map('intval', $array);//lo lleva a entero 
        $result = implode( ",", $intArray);//los separa por comas
        $sqlUpdate = "UPDATE {$this->db_name}.articulo_tienda SET artie_est_log=0 WHERE tie_id=:tie_id AND pcli_id NOT IN($result)";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':tie_id' => $tieId
        ]);
    }


    public function consultarProductosTiendaCheck($tie_id)
    {
        try {
            $sql = "SELECT pcli_id FROM {$this->db_name}.articulo_tienda where artie_est_log !=0 and tie_id=:tie_id;";
            $resultado = $this->select_all($sql, [":tie_id" => $tie_id]);
            if ($resultado === false) {
                logFileSystem("Consulta fallida para tie_id: $tie_id", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return array_map(fn($item) => strval($item["pcli_id"]), $resultado);
        } catch (Exception $e) {
            logFileSystem("Error en consultarProductosTiendaCheck: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }



}
