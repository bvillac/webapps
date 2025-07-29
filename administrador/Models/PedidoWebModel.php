<?php
class PedidoWebModel extends MysqlPedidos
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
            $where = ["a.tcped_est_log <> 4"]; // Excluir anulados

            // Tiendas disponibles para el usuario
            if (!empty($idsTie)) {
                $idsTie = implode(",", $idsTie);
                $where[] = "a.tie_id IN ($idsTie)";
            }

            // Extraer filtros del array
            $filtros = $criterio[0] ?? [];

            if (!empty($filtros)) {
                if (!empty($filtros['est_log']) && $filtros['est_log'] !== "0") {
                    $where[] = "a.tcped_est_log = :est_log";
                    $params[':est_log'] = $filtros['est_log'];
                }

                if (!empty($filtros['tie_id']) && (int) $filtros['tie_id'] > 0) {
                    $where[] = "a.tie_id = :tie_id";
                    $params[':tie_id'] = $filtros['tie_id'];
                }

                if (!empty($filtros['f_ini']) && !empty($filtros['f_fin'])) {
                    $where[] = "DATE(a.tcped_fec_cre) BETWEEN :f_ini AND :f_fin";
                    $params[':f_ini'] = date('Y-m-d', strtotime($filtros['f_ini']));
                    $params[':f_fin'] = date('Y-m-d', strtotime($filtros['f_fin']));
                }
            }

            // Construcción del SQL
            $sql = "
            SELECT 
                a.tcped_id AS Ids,
                a.tie_id AS tieid,
                a.tcped_total AS total,
                DATE(a.tcped_fec_cre) AS fechapedido,
                (
                    SELECT MAX(m.cped_id)
                    FROM {$this->db_name}.cab_pedido m
                    WHERE m.tcped_id = a.tcped_id
                ) AS cped_id,
                b.tie_nombre AS nombretienda,
                b.tie_direccion AS direcciontienda,
                concat(e.per_nombre,' ',e.per_apellido)  AS nombrepersona,
                LPAD(a.tcped_id, 9, '0') AS numero,
                a.tcped_est_log AS Estado,
                a.tcped_est_env AS estenv
            FROM {$this->db_name}.temp_cab_pedido a
            INNER JOIN {$this->db_name}.tienda b ON a.tie_id = b.tie_id
            INNER JOIN {$this->db_name}.usuario_tienda c ON c.utie_id = a.utie_id
            INNER JOIN {$this->db_nameAdmin}.usuario d ON c.usu_id = d.usu_id
            INNER JOIN {$this->db_nameAdmin}.persona e ON d.per_id = e.per_id
            WHERE " . implode(" AND ", $where) . "
            ORDER BY a.tcped_id DESC
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

    /**
     * Recupera los IDs de tienda asociados al usuario en sesión.
     *
     * @return int[] Array de IDs de tienda. Vacío si no hay ninguno.
     */
    public function recuperarIdsTienda(): array
    {
        $utieId = retornarDataSesion('Utie_id');
        $usuId = retornarDataSesion('Usu_id');
        $rolNombre = retornarDataSesion('RolNombre');

        //if (empty($utieId) && $rolNombre !== "supervisortienda") {
        //    return []; // No hay utieId y no es supervisor -> no tiene sentido buscar
        //}

        $params = [];
        if ($rolNombre === "supervisortienda") {
            $sql = "
            SELECT a.tie_id
            FROM {$this->db_name}.usuario_tienda AS a
            WHERE a.utie_est_log = 1
              AND a.usu_id = :usu_id
        ";
            $params = [':usu_id' => $usuId];
        } else {
            $sql = "
            SELECT a.tie_id
            FROM {$this->db_name}.usuario_tienda AS a
            WHERE a.utie_est_log = 1
              AND a.utie_id = :utie_id
        ";
            $params = [':utie_id' => $utieId];
        }

        $rows = $this->select_all($sql, $params);
        return array_column($rows, 'tie_id');
    }



    public function listarItemsTiendas(int $ids, int $cli_id)
    {
        try {
            $sql = "select a.artie_id,a.pcli_id,b.art_id,c.cod_art codigo, c.art_des_com nombre,b.pcli_p_venta precio,
                        '0' cantidad,'0' total,c.art_i_m_iva iva,'' observacion,a.artie_est_log estado
                        from {$this->db_name}.articulo_tienda a
                            inner join ({$this->db_name}.precio_cliente b
                                inner join {$this->db_name}.articulo c
                                    on c.art_id=b.art_id)
                            on a.pcli_id=b.pcli_id and b.pcli_est_log=1
                    where a.artie_est_log=1 and a.tie_id= :tie_id and b.cli_id = :cli_id ";
            $sql .= " order by c.art_des_com desc limit " . LIMIT_SQL;
            $resultado = $this->select_all($sql, [':tie_id' => $ids, ':cli_id' => $cli_id]);

            if ($resultado === false) {
                logFileSystem("Consulta fallida para listarItemsTiendas", "WARNING");
                return [];
            }

            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en listarItemsTiendas: " . $e->getMessage(), "ERROR");
            return [];
        }
    }


    public function insertData(array $productos, int $tienda_id, float $total)
    {
        $con = $this->getConexion();
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];
        $utieId = retornarDataSesion('Utie_id');
        $cliId = retornarDataSesion('Cli_Id');
        $Usuario = retornaUser();

        try {
            $con->beginTransaction();

            // Verificar si ya existe una cabecera de pedido activa
            /*$sqlCheckCab = "SELECT tcped_id FROM {$this->db_name}.temp_cab_pedido
                        WHERE tie_id = :tie_id AND cli_id = :cli_id AND tcped_est_log = 1";
            $stmtCheckCab = $con->prepare($sqlCheckCab);
            $stmtCheckCab->execute([
                ":tie_id" => $tienda_id,
                ":cli_id" => $cliId
            ]);
            $cabeceraExistente = $stmtCheckCab->fetch(PDO::FETCH_ASSOC);

            if ($cabeceraExistente) {
                $idcab = $cabeceraExistente['tcped_id'];

                // Actualizar total en la cabecera
                $sqlUpdateCab = "UPDATE {$this->db_name}.temp_cab_pedido
                             SET tcped_total = :total, tcped_fec_mod = CURRENT_TIMESTAMP
                             WHERE tcped_id = :id";
                $stmtUpdateCab = $con->prepare($sqlUpdateCab);
                $stmtUpdateCab->execute([
                    ":total" => $total,
                    ":id" => $idcab
                ]);
            } else {
                // Insertar nueva cabecera
                $request = $this->InsertarCabListPedTemp($con, $tienda_id, $utieId, $cliId, $total, $Usuario);
                if ($request["status"] == false) {
                    return ["status" => false, "numero" => 0, "message" => $request["message"]];
                }
                $idcab = $request["numero"];
            }*/

            // Insertar nueva cabecera
            $request = $this->InsertarCabListPedTemp($con, $tienda_id, $utieId, $cliId, $total, $Usuario);
            if ($request["status"] == false) {
                return ["status" => false, "numero" => 0, "message" => $request["message"]];
            }
            $idcab = $request["numero"];

            // Procesar productos
            foreach ($productos as $producto) {
                if ((float) $producto['cantidad'] <= 0)
                    continue;

                /*$sqlCheckDet = "SELECT tdped_id FROM {$this->db_name}.temp_det_pedido
                            WHERE tcped_id = :tcped_id AND art_id = :art_id AND cli_id = :cli_id";
                $stmtCheckDet = $con->prepare($sqlCheckDet);
                $stmtCheckDet->execute([
                    ":tcped_id" => $idcab,
                    ":art_id" => $producto['art_id'],
                    ":cli_id" => $cliId
                ]);
                $detalleExistente = $stmtCheckDet->fetch(PDO::FETCH_ASSOC);

                if ($detalleExistente) {
                    // Actualizar detalle existente
                    $sqlUpdateDet = "UPDATE {$this->db_name}.temp_det_pedido SET
                                 tdped_can_ped = :cantidad,
                                 tdped_p_venta = :precio,
                                 tdped_t_venta = :total,
                                 tdped_i_m_iva = :iva,
                                 tdped_est_log = 1,
                                 tdped_fec_mod = CURRENT_TIMESTAMP
                                 WHERE tdped_id = :tdped_id";
                    $stmtUpdateDet = $con->prepare($sqlUpdateDet);
                    $stmtUpdateDet->execute([
                        ":cantidad" => $producto['cantidad'],
                        ":precio" => $producto['precio'],
                        ":total" => $producto['total'],
                        ":iva" => $producto['iva'],
                        ":tdped_id" => $detalleExistente['tdped_id']
                    ]);
                } else {
                    // Insertar nuevo detalle
                    $sqlInsert = "INSERT INTO {$this->db_name}.temp_det_pedido (
                                tcped_id, artie_id, art_id, tdped_can_ped, tdped_p_venta,
                                tdped_t_venta, tdped_i_m_iva, tdped_est_aut, tdped_observa,
                                tdped_est_log, tdped_fec_cre, cli_id, tie_id
                              ) VALUES (
                                :tcped_id, :artie_id, :art_id, :cantidad, :precio,
                                :total, :iva, 1, '', 1, CURRENT_TIMESTAMP, :cli_id, :tie_id
                              )";
                    $stmtInsert = $con->prepare($sqlInsert);
                    $stmtInsert->execute([
                        ":tcped_id" => $idcab,
                        ":artie_id" => $producto['artie_id'],
                        ":art_id" => $producto['art_id'],
                        ":cantidad" => $producto['cantidad'],
                        ":precio" => $producto['precio'],
                        ":total" => $producto['total'],
                        ":iva" => $producto['iva'],
                        ":cli_id" => $cliId,
                        ":tie_id" => $tienda_id
                    ]);
                }*/

                // Insertar nuevo detalle
                $sqlInsert = "INSERT INTO {$this->db_name}.temp_det_pedido (
                    tcped_id, artie_id, art_id, tdped_can_ped, tdped_p_venta,
                    tdped_t_venta, tdped_i_m_iva, tdped_est_aut, tdped_observa,
                    tdped_est_log, tdped_fec_cre, cli_id, tie_id
                  ) VALUES (
                    :tcped_id, :artie_id, :art_id, :cantidad, :precio,
                    :total, :iva, 1, '', 1, CURRENT_TIMESTAMP, :cli_id, :tie_id
                  )";
                $stmtInsert = $con->prepare($sqlInsert);
                $stmtInsert->execute([
                    ":tcped_id" => $idcab,
                    ":artie_id" => $producto['artie_id'],
                    ":art_id" => $producto['art_id'],
                    ":cantidad" => $producto['cantidad'],
                    ":precio" => $producto['precio'],
                    ":total" => $producto['total'],
                    ":iva" => $producto['iva'],
                    ":cli_id" => $cliId,
                    ":tie_id" => $tienda_id
                ]);
            }

            $con->commit();
            return ["status" => true, "numero" => $idcab, "message" => "Registros guardados correctamente."];

        } catch (Exception $e) {
            $con->rollBack();
            logFileSystem("Error en insertData: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
        }
    }



    public function InsertarCabListPedTemp($con, $tieId, $utieId, $cliId, $total, $Usuario)
    {
        // Validar si la tienda ya existe para este cliente (opcional)
        /*
        $sqlCheck = "SELECT 1 FROM {$this->db_name}.temp_cab_pedido 
                     WHERE tie_id = :tie_id AND cli_id = :cli_id";
        $paramsCheck = [":tie_id" => $tieId, ":cli_id" => $cliId];
        if (!empty($this->select($sqlCheck, $paramsCheck))) {
            return ["status" => false, "message" => "Ya existe un registro con esta tienda para este cliente."];
        }
        */

        $sqlInsert = "INSERT INTO {$this->db_name}.temp_cab_pedido (
                            tdoc_id, tie_id, utie_id, tcped_total, tcped_est_log, tcped_fec_cre,
                            cli_id, tcped_receptor, usuario
                      ) VALUES (
                            :tdoc_id, :tie_id, :utie_id, :tcped_total, :tcped_est_log, CURRENT_TIMESTAMP(),
                            :cli_id, :tcped_receptor, :usuario
                      );";

        $paramsInsert = [
            ":tdoc_id" => 4,
            ":tie_id" => $tieId,
            ":utie_id" => $utieId,
            ":tcped_total" => $total,
            ":tcped_est_log" => 1,
            ":cli_id" => $cliId,
            ":tcped_receptor" => '', // Si luego se usará, reemplazar por valor real
            ":usuario" => $Usuario
        ];

        $resInsert = $this->insertConTrans($con, $sqlInsert, $paramsInsert);

        if ($resInsert > 0) {
            return ["status" => true, "numero" => $resInsert];
        } else {
            return ["status" => false, "message" => "Error al insertar cabecera temporal"];
        }
    }

    public function recuperarSaldoTienda(int $idTienda, int $idCliente): float
    {
        $sql = "SELECT SUM(tcped_total) AS Total 
                FROM {$this->db_name}.temp_cab_pedido 
                WHERE cli_id = :cli_id 
                  AND tie_id = :tie_id
                  AND MONTH(tcped_fec_cre) = MONTH(CURRENT_DATE())
                  AND YEAR(tcped_fec_cre) = YEAR(CURRENT_DATE())";

        $rows = $this->select_all($sql, [':cli_id' => $idCliente, ':tie_id' => $idTienda]);

        if (!empty($rows) && isset($rows[0]['Total'])) {
            return (float) $rows[0]['Total'];
        }

        return 0.00;
    }



    public function sendMailPedidosTemp(int $ids)
    {
        try {
            $sql = "select a.tcped_id pedid,concat(repeat( '0', 9 - length(a.tcped_id) ),a.tcped_id) numero,
                        a.tcped_total valorneto,date(a.tcped_fec_cre) fechapedido,b.tie_nombre nombretienda,
                        concat(e.per_nombre,' ',e.per_apellido) nombrepersona,d.usu_correo correopersona,
                        concat(h.per_nombre,' ',h.per_apellido) nombreuser,g.usu_correo correouser
                        from {$this->db_name}.temp_cab_pedido a
                                inner join {$this->db_name}.tienda b
                                        on a.tie_id=b.tie_id
                                inner join ({$this->db_name}.usuario_tienda c
                                                inner join ({$this->db_nameAdmin}.usuario d
                                                                inner join {$this->db_nameAdmin}.persona e
                                                                        on d.per_id=e.per_id)
                                                        on c.usu_id=d.usu_id)
                                       on c.utie_id=a.utie_id
                                inner join ({$this->db_name}.usuario_tienda f
                                                inner join ({$this->db_nameAdmin}.usuario g
                                                                inner join {$this->db_nameAdmin}.persona h
                                                                        on g.per_id=h.per_id)
                                                        on f.usu_id=g.usu_id)
                                        on f.utie_id=a.utie_id
                where a.tcped_id= :tcped_id ;";
            $resultado = $this->select_all($sql, [':tcped_id' => $ids]);
            if ($resultado === false) {
                logFileSystem("Consulta fallida para sendMailPedidosTemp", "WARNING");
                return [];
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en sendMailPedidosTemp: " . $e->getMessage(), "ERROR");
            return [];
        }
    }


    public function recuperarUserCorreoTiendaSUP($idTie, $idRol, $cli_Id)
    {
        try {
            $sql = "select concat(e.per_nombre,' ',e.per_apellido) as usu_nombre,b.usu_correo 
                    from {$this->db_name}.usuario_tienda a
                        inner join ({$this->db_nameAdmin}.usuario b
                            inner join {$this->db_nameAdmin}.persona e
                                on b.per_id=e.per_id)
                        on b.usu_id=a.usu_id
                    where a.cli_id=:cli_id and a.tie_id=:tie_id and rol_id=:rol_id and utie_est_log=1;";


            $arrParams = [":cli_id" => $cli_Id, ":tie_id" => $idTie, ":rol_id" => $idRol];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida recuperarUserCorreoTiendaSUP", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en recuperarUserCorreoTiendaSUP: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }


    public function cabeceraPedidoTemp($ids)
    {
        try {
            $sql = "select a.tcped_id pedid,concat(repeat( '0', 9 - length(a.tcped_id) ),a.tcped_id) numero,b.tie_id tieid,
                        a.tcped_total total,date(a.tcped_fec_cre) fechapedido,b.tie_nombre nombretienda, a.tcped_receptor receptor
                        from {$this->db_name}.temp_cab_pedido a
                                inner join {$this->db_name}.tienda b
                                        on a.tie_id=b.tie_id
                    where a.tcped_id=:tcped_id ;";
            $arrParams = [":tcped_id" => $ids];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida cabeceraPedidoTemp", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en cabeceraPedidoTemp: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }

    public function detallePedidoTemp($ids)
    {
        try {
            $sql = "select a.artie_id detid,a.art_id artid,a.tdped_can_ped cantidad,a.tdped_p_venta precio,
                        a.tdped_t_venta totvta,a.tdped_est_aut estaut,a.tdped_observa observacion,b.cod_art codigo,
                        b.art_des_com nombre,b.art_i_m_iva imiva
                        from {$this->db_name}.temp_det_pedido a
                                inner join {$this->db_name}.articulo b
                                        on a.art_id=b.art_id
                where a.tcped_id=:tcped_id ";
            $arrParams = [":tcped_id" => $ids];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida detallePedidoTemp", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en detallePedidoTemp: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }



    public function updateData(array $productos, int $tienda_id, float $total, int $cabId)
    {
        $con = $this->getConexion();
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];
        $utieId = retornarDataSesion('Utie_id');
        $cliId = retornarDataSesion('Cli_Id');
        $Usuario = retornaUser();

        try {
            $con->beginTransaction();
            $this->actualizaCabListPedTemp($con, $total, $cabId);
            $this->deleteDetListPedTemp($con, $cabId);

            // Procesar productos
            foreach ($productos as $producto) {
                if ((float) $producto['cantidad'] <= 0)
                    continue;

                // Insertar nuevo detalle
                $sqlInsert = "INSERT INTO {$this->db_name}.temp_det_pedido (
                    tcped_id, artie_id, art_id, tdped_can_ped, tdped_p_venta,
                    tdped_t_venta, tdped_i_m_iva, tdped_est_aut, tdped_observa,
                    tdped_est_log, tdped_fec_cre, cli_id, tie_id
                  ) VALUES (
                    :tcped_id, :artie_id, :art_id, :cantidad, :precio,
                    :total, :iva, 1, '', 1, CURRENT_TIMESTAMP, :cli_id, :tie_id
                  )";
                $stmtInsert = $con->prepare($sqlInsert);
                $stmtInsert->execute([
                    ":tcped_id" => $cabId,
                    ":artie_id" => $producto['artie_id'],
                    ":art_id" => $producto['art_id'],
                    ":cantidad" => $producto['cantidad'],
                    ":precio" => $producto['precio'],
                    ":total" => $producto['total'],
                    ":iva" => $producto['iva'],
                    ":cli_id" => $cliId,
                    ":tie_id" => $tienda_id
                ]);
            }

            $con->commit();
            return ["status" => true, "numero" => $cabId, "message" => "Registros guardados correctamente."];

        } catch (Exception $e) {
            $con->rollBack();
            logFileSystem("Error en actualizarLista: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la operación: " . $e->getMessage()];
        }
    }



    private function actualizaCabListPedTemp($con, $total, $cabId)
    {
        $sqlUpdate = "UPDATE {$this->db_name}.temp_cab_pedido SET tcped_total=:Total WHERE tcped_id=:tcped_id";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':tcped_id' => $cabId,
            ':Total' => $total
        ]);
    }


    private function deleteDetListPedTemp($con, $cabId)
    {
        $sqlUpdate = "DELETE FROM {$this->db_name}.temp_det_pedido WHERE tcped_id=:tcped_id";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':tcped_id' => $cabId
        ]);
    }

    public function anularPedidoTemp(int $ids)
    {
        $con = $this->getConexion(); // Obtiene la conexión a la base de datos
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $usuario = retornaUser();
            $con->beginTransaction(); // Inicia una transacción
            $sqlUpdate = "UPDATE {$this->db_name}.temp_cab_pedido 
                              SET tcped_est_log= :tcped_est_log, 
                              usuario=:usuario,tcped_fec_mod = CURRENT_TIMESTAMP()
                              WHERE tcped_id =:tcped_id ";
            $stmtUpdate = $con->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':tcped_id' => $ids,
                ':tcped_est_log' => 4,
                ':usuario' => $usuario
            ]);

            $con->commit(); // Confirma la transacción
            return ["status" => true, "message" => "Registro Anulado correctamente."];

        } catch (Exception $e) {
            $con->rollBack(); // Revierte la transacción en caso de error
            logFileSystem("Error en anularPedidoTemp: " . $e->getMessage(), "ERROR");
            return ["status" => false, "message" => "Error en la Anulado: " . $e->getMessage()];
        }
    }


    public function autorizarPedidoTemp(int $ids)
    {
        $con = $this->getConexion();
        $arroout = ["status" => false, "message" => "No se realizó ninguna operación."];

        try {
            $usuario = retornaUser();
            $cliId = retornarDataSesion('Cli_Id');

            $con->beginTransaction();

            $cabFact = $this->buscarCabPedidosTemp($con, $ids);
            if (empty($cabFact)) {
                throw new Exception("No se encontró la cabecera del pedido temporal.");
            }

            $request = $this->InsertarCabFactura($con, $cabFact, $cliId, $usuario);
            if (!$request["status"]) {
                throw new Exception("Error al insertar la cabecera de la factura.");
            }

            $idCab = $request["numero"];

            $detFact = $this->buscarDetPedidosTemp($con, $ids);
            if (empty($detFact)) {
                throw new Exception("No se encontraron detalles del pedido temporal.");
            }

            $resDetalle = $this->InsertarDetFactura($con, $detFact, $idCab, $cliId);
            if (!$resDetalle["status"]) {
                throw new Exception("Error al insertar los detalles de la factura.");
            }
            $this->actTemCabPed($con, $ids);
            $con->commit();
            return [
                "status" => true,
                "numero" => $idCab,
                "message" => "Registro Autorizado correctamente."
            ];

        } catch (Exception $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            logFileSystem("Error en autorizarPedidoTemp: " . $e->getMessage(), "ERROR");
            return [
                "status" => false,
                "message" => "Error al autorizar: " . $e->getMessage()
            ];
        }
    }




    private function buscarCabPedidosTemp($con, $ids)
    {
        try {
            $sql = "select * from {$this->db_name}.temp_cab_pedido 
                        where tcped_id=:tcped_id and tcped_est_log in(1,5)";
            $arrParams = [":tcped_id" => $ids];
            $resultado = $this->select($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida cabeceraPedidoTemp", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en buscarCabPedidosTemp: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }

    private function buscarDetPedidosTemp($con, $ids)
    {
        try {
            $sql = "select * from {$this->db_name}.temp_det_pedido 
                        where tcped_id=:tcped_id and tdped_est_aut=1";
            $arrParams = [":tcped_id" => $ids];
            $resultado = $this->select_all($sql, $arrParams);
            if ($resultado === false) {
                logFileSystem("Consulta fallida buscarDetPedidosTemp", "WARNING");
                return []; // Retornar un array vacío en lugar de false para evitar errores en la vista
            }
            return $resultado;
        } catch (Exception $e) {
            logFileSystem("Error en cabeceraPedidoTemp: " . $e->getMessage(), "ERROR");
            return []; // En caso de error, retornar un array vacío
        }
    }


    private function InsertarCabFactura($con, $cabData, $cliId, $Usuario)
    {
        // cabData es un array que debe tener las columnas necesarias
        $sqlInsert = "INSERT INTO {$this->db_name}.cab_pedido (
                tdoc_id,tie_id,tcped_id,cped_fec_ped,cped_val_bru,cped_por_des,cped_val_des,
                cped_por_iva,cped_val_iva,cped_bas_iva, cped_bas_iv0,
                cped_val_fle,cped_val_net,cped_est_ped,cped_est_log,utie_id_ped,utie_id,cli_id,usuario
            ) VALUES (
                :tdoc_id,:tie_id,:tcped_id,CURRENT_TIMESTAMP(),:cped_val_bru,0,0,0,0,0,0,0,
                :cped_val_net,:cped_est_ped,1,:utie_id_ped,:utie_id,:cli_id,:usuario
            )";

        $paramsInsert = [
            ':tdoc_id' => 5, // documento fijo
            ':tie_id' => $cabData['tie_id'],
            ':tcped_id' => $cabData['tcped_id'], // este es el número o ID de pedido
            ':cped_val_bru' => $cabData['tcped_total'],
            ':cped_val_net' => $cabData['tcped_total'],
            ':cped_est_ped' => 2, // AUTORIZADO
            ':utie_id_ped' => $cabData['utie_id'],
            ':utie_id' => $cabData['utie_id'],
            ':cli_id' => $cliId,
            ':usuario' => $Usuario
        ];

        $resInsert = $this->insertConTrans($con, $sqlInsert, $paramsInsert);

        if ($resInsert > 0) {
            return ["status" => true, "numero" => $resInsert];
        } else {
            return ["status" => false, "message" => "Error al insertar cabecera temporal"];
        }
    }


    private function actTemCabPed($con, $ids)
    {
        $sqlUpdate = "UPDATE {$this->db_name}.temp_cab_pedido SET tcped_est_log=:tcped_est_log WHERE tcped_id=:tcped_id";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':tcped_id' => $ids,
            ':tcped_est_log' => 2//AUTORIZADO
        ]);
    }


    private function InsertarDetFactura($con, $detFact, $idCab, $cliId)
    {
        try {

            $sql = "INSERT INTO {$this->db_name}.det_pedido (
                    cped_id, art_id, tie_id, dped_can_ped, dped_p_venta, dped_i_m_iva, 
                    dped_val_des, dped_por_des, dped_t_venta, dped_observa, 
                    dped_fec_cre, dped_est_log, cli_id
                ) VALUES (
                    :cped_id, :art_id, :tie_id, :dped_can_ped, :dped_p_venta, :dped_i_m_iva, 
                    :dped_val_des, :dped_por_des, :dped_t_venta, :dped_observa, 
                    CURRENT_TIMESTAMP(), 2, :cli_id
                )";
            $stmt = $con->prepare($sql);

            foreach ($detFact as $detalle) {
                //  if (empty($detalle['art_id']) || empty($detalle['tdped_can_ped']) || empty($detalle['tdped_p_venta'])) {
                //      throw new Exception("Faltan datos obligatorios para insertar el detalle.");
                //  }

                // $sql = "INSERT INTO {$this->db_name}.det_pedido (
                //         cped_id, art_id, tie_id, dped_can_ped, dped_p_venta, dped_i_m_iva, 
                //         dped_val_des, dped_por_des, dped_t_venta, dped_observa, 
                //         dped_fec_cre, dped_est_log, cli_id
                //         ($idCab,
                //      '" . $detalle['art_id'] . "','" . $detalle['tie_id'] . "','" . $detalle['tdped_can_ped'] . "',
                //      '" . $detalle['tdped_p_venta'] . "','0','0','0',
                //      '" . $detalle['tdped_t_venta'] . "','" . $detalle['tdped_observa'] . "',CURRENT_TIMESTAMP(),'1','$cliId');";

                $stmt->execute([
                    ':cped_id' => $idCab,
                    ':art_id' => $detalle['art_id'],
                    ':tie_id' => $detalle['tie_id'],
                    ':dped_can_ped' => $detalle['tdped_can_ped'],
                    ':dped_p_venta' => $detalle['tdped_p_venta'],
                    ':dped_i_m_iva' => 0,
                    ':dped_val_des' => 0,
                    ':dped_por_des' => 0,
                    ':dped_t_venta' => $detalle['tdped_t_venta'],
                    ':dped_observa' => $detalle['tdped_observa'] ?? '',
                    ':cli_id' => $cliId
                ]);
            }

            return [
                "status" => true,
                "message" => "Detalle insertado correctamente."
            ];
        } catch (Exception $e) {
            logFileSystem("Error en InsertarDetFactura: " . $e->getMessage(), "ERROR");
            return [
                "status" => false,
                "message" => "Error al insertar detalle: " . $e->getMessage()
            ];
        }
    }












}
