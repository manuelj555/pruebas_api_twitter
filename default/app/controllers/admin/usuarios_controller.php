<?php
/**
* Backend - KumbiaPHP Backend
* PHP version 5
* LICENSE
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
* @package Controller
* @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
* @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
*/
Load::models('usuarios');

class UsuariosController extends AdminController {
    
    protected function before_filter() {
        if ( Input::isAjax() ){
            View::select(NULL, NULL);
        }
    }

    public function index($pagina = 1) {
        try {
            $usr = new Usuarios();
            $this->usuarios = $usr->obtener_usuarios($pagina);
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function perfil() {
        try {
            $usr = new Usuarios();
            $this->usuario1 = $usr->find_first(Auth::get('id'));
            if (Input::hasPost('usuario1')) {
                if ($usr->update(Input::post('usuario1'))) {
                    Flash::valid('Datos Actualizados Correctamente');
                    $this->usuario1 = $usr;
                }
            } else if (Input::hasPost('usuario2')) {
                if ($usr->cambiar_clave(Input::post('usuario2'))) {
                    Flash::valid('Clave Actualizada Correctamente');
                    $this->usuario1 = $usr;
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function crear() {
        try {
             if (Input::hasPost('usuario')) {
                $usr = new Usuarios(Input::post('usuario'));
                if ($usr->save()) {
                    Flash::valid('El Usuario Ha Sido Agregado Exitosamente...!!!');
                    Acciones::add("Agregó al usuario {$usr->login} al sistema", 'usuarios');
                    return Router::redirect();
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function editar($id) {
        try {

            $id = Filter::get($id, 'digits');

            $usr = new Usuarios();

            $this->usuario = $usr->find_first($id);

            if (Input::hasPost('usuario')) {

                if ($usr->update(Input::post('usuario'))) {
                    Flash::valid('El Usuario Ha Sido Actualizado Exitosamente...!!!');
                    Acciones::add("Editó al usuario {$usr->login}", 'usuarios');
                    return Router::redirect();
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                    unset($this->usuario); //para que cargue el $_POST en el form
                }
            } else if (!$this->usuario) {
                Flash::warning("No existe ningun usuario con id '{$id}'");
                return Router::redirect();
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function activar($id) {
        try {
            $id = Filter::get($id, 'digits');
            $usuario = new Usuarios();
            if (!$usuario->find_first($id)){ //si no existe el usuario
                Flash::warning("No existe ningun usuario con id '{$id}'");
            }else if ($usuario->activar()) {
                Flash::valid("La Cuenta del Usuario {$usuario->login} ({$usuario->nombres}) fué activada...!!!");
                Acciones::add("Colocó al usuario {$usuario->login} como activo", 'usuarios');
            } else {
                Flash::warning('No se Pudo Activar la cuenta del Usuario...!!!');
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::toAction('');
    }

    public function desactivar($id) {
        try {
            $id = Filter::get($id, 'digits');
            $usuario = new Usuarios();
            if (!$usuario->find_first($id)){ //si no existe el usuario
                Flash::warning("No existe ningun usuario con id '{$id}'");
            }else if ($usuario->desactivar()) {
                Flash::valid("La Cuenta del Usuario {$usuario->login} ({$usuario->nombres}) fué desactivada...!!!");
                Acciones::add("Colocó al usuario {$usuario->login} como inactivo", 'usuarios');
            } else {
                Flash::warning('No se Pudo Desactivar la cuenta del Usuario...!!!');
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::toAction('');
    }

}
