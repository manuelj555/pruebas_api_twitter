<?php

/**
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 * */
// @see Controller nuevo controller
require_once CORE_PATH . 'kumbia/controller.php';

class AdminController extends Controller {

    final protected function initialize() {
        if (MyAuth::es_valido()) {
            View::template('backend/backend');
            $acl = new MyAcl();
            if (!$acl->check()) {
                if ($acl->limiteDeIntentosPasado()) {
                    $acl->resetearIntentos();
                    return $this->intentos_pasados();
                }
                Flash::error('no posees privilegios para acceder a <b>' . Router::get('route') . '</b>');
                View::select(NULL);
                return FALSE;
            } else {
                $acl->resetearIntentos();
            }
        } elseif (Input::hasPost('login') && Input::hasPost('clave')) {
            if (MyAuth::autenticar(Input::post('login'), Input::post('clave'))) {
                Flash::info('Bienvenido al Sistema <b>' . Auth::get('nombres') . '</b>');
                return Router::route_to();
            } else {
                Flash::warning('Datos de Acceso invalidos');
                View::select(NULL, 'backend/logueo');
                return FALSE;
            }
        } else {
            View::select(NULL, 'backend/logueo');
            return FALSE;
        }
    }

    final protected function finalize() {
        
    }

    public function logout() {
        MyAuth::cerrar_sesion();
        return Router::redirect('/');
    }

    protected function intentos_pasados() {
        MyAuth::cerrar_sesion();
        Flash::warning('Has Sobrepasado el limite de intentos fallidos al tratar acceder a ciertas partes del sistema');
        return Router::redirect('/');
    }

}