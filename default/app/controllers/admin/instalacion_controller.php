<?php

/**
 * Description of instalacion_controller
 *
 * @author manuel
 */
Load::models('instalacion');

class InstalacionController extends AppController
{

    protected function before_filter()
    {
        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') { //seguridad
            header('HTTP/1.0 403 Forbidden');
            exit('No tienes permisos para acceder a esta dirección...!!!');
        }
        if (defined('SIN_MOD_REWRITE')) {
            Flash::error('Debes tener instalado el Mod Rewrite de Apache para poder usar KumbiaPHP');
        }
    }

    public function index($index_entorno = 0)
    {
        $inst = new Instalacion();
        $this->entornos_bd = $inst->entornosConexion();
        $this->entorno = $inst->entorno($index_entorno);
        $this->database = $inst->configuracionEntorno($index_entorno);

        if (Input::hasPost('database')) {
            if ($inst->guardarDatabases($_POST['entorno'], $_POST['database'])) {
                return Router::toAction('paso2');
            } else {
                Flash::error('No se han podido guardar los datos de Configuración');
            }
        } elseif (Input::isAjax()) {
            View::response('ajax', NULL);
        }
    }

    public function paso2()
    {
        $inst = new Instalacion();
        $this->config = $inst->obtenerConfig();
        $this->entornos = $inst->entornosConexion();
        $this->entornos = array_combine($this->entornos, $this->entornos);
        if (Input::hasPost('config')) {
            if ($inst->guardarConfig(Input::post('config'))) {
                return Router::toAction('paso3');
            } else {
                Flash::warning('No se Pudieron guardar los Datos...!!!');
            }
        }
    }

    public function describe()
    {
        View::select(NULL);

        Config::read('bd_scheme');
        var_dump(Config::get('bd_scheme.usuarios'));
        var_dump(Db::factory()->drop_table('usuarios'));
        var_dump(Db::factory()->create_table('usuarios', Config::get('bd_scheme.usuarios')));
        var_dump(Db::factory()->describe_table('usuarios'));
        var_dump(Db::factory()->list_tables());
    }

    public function paso3()
    {
        $inst = new Instalacion();
        if ($inst->verificarConexion()) {
            $this->tablas_crear = $inst->listarTablasPorCrear();
            if (Input::hasPost('tablas')) {
                if ($inst->crearTablas(Input::post('tablas'))) {
                    return Router::toAction('instalacion_finalizada');
                } else {
                    Flash::error('No se Pudieron crear todas las Tablas...!!!');
                }
            }
            $this->tablas_existentes = $inst->listarTablasExistentes();
        }
    }

    public function instalacion_finalizada()
    {
        
    }

    public function quitar_instalacion()
    {
        Configuracion::leer();
        Configuracion::set('routes', 'Off');
        Configuracion::guardar();
        Router::redirect('/');
    }

}