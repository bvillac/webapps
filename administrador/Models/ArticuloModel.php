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
        $sql = "select art_id,cod_art,art_des_com"
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
        putMessageLogFile( $sql);
        // Ejecutar consulta y devolver resultados
        return $this->select_all($sql, $params);
    }




}
