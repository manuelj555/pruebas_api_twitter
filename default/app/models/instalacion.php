<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of instalacion
 *
 * @author maguirre
 */
class Instalacion
{

    public function __construct()
    {
        Config::read('config');
        Config::read('databases');
    }

    public function entornosConexion()
    {
        return array_keys(Config::get('databases'));
    }

    public function entorno($pos_entorno = 0)
    {
        $entornos = $this->entornosConexion();

        return $entornos[$pos_entorno];
    }

    public function configuracionEntorno($pos_entorno)
    {
        return Config::get("databases.{$this->entorno($pos_entorno)}");
    }

    public function guardarDatabases($pos_entorno, $data)
    {
        Config::set("databases.{$this->entorno($pos_entorno)}", $data);
        return MyConfig::save('databases');
    }

    public function obtenerConfig()
    {
        return Configuracion::leer();
    }

    public function guardarConfig($data)
    {
        foreach ($data as $variable => $valor) {
            Configuracion::set($variable, $valor);
        }
        return Configuracion::guardar();
    }

    public function listarTablasExistentes()
    {
        $tablas_existentes = array();
        foreach ((array) Db::factory()->list_tables() as $t) {
            $tablas_existentes[] = $t[0];
        }
        return $tablas_existentes;
    }

    public function listarTablasPorCrear()
    {
        require_once APP_PATH . 'config/sql/bd.php';
        return array_keys(getEsquema());
    }

    public function crearTablas($tablas)
    {
        require_once APP_PATH . 'config/sql/bd.php';
        $esquema = getEsquema();
        $exito = TRUE;
        $db = Db::factory();
        foreach ($tablas as $key => $valor) {
            $db->drop_table($valor);
            if ($db->create_table($valor, $esquema[$valor])) {
                $data_tabla = getDataTable($valor);
                if ($data_tabla) {
                    if (!$db->query(getDataTable($valor))) {
                        Flash::error("No se pudieron crear los registros inicales para la tabla <b>$valor</b>");
                        $exito = FALSE;
                    }
                }
            } else {
                Flash::error("No se pudo crear la tabla <b>$valor</b>");
                $exito = FALSE;
            }
        }
        return $exito;
    }

    public function verificarConexion()
    {
        try {
            ob_start();
            $con = Db::factory();
            ob_clean();
        } catch (KumbiaException $e) {
            ob_clean();
            View::response('error');
            Flash::error('No se Pudo Conectar a la Base de datos');
            Flash::info('Verifica que el Nombre de usuario y contraseña de conexion a la BD son Correctos');
            View::excepcion($e);
            return FALSE;
        }
        return TRUE;
    }

}