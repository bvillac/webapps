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
                a.tcped_id AS pedid,
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
                a.tcped_est_log AS estado,
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
        $utieId = retornarDataSesion('Utie_Id');
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


    public function listarItemsTiendas(int $ids,int $cli_id)
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
            $sql.=" order by c.art_des_com desc limit ". LIMIT_SQL;

            $resultado = $this->select_all($sql, [':tie_id' => $ids,':cli_id' => $cli_id]);

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







}
