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
Load::models('roles');

class RolesController extends AdminController {

    public function index($pag= 1) {
        try {
            $roles = new Roles();
            $this->roles = $roles->paginate("page: $pag");
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function crear() {
        $this->titulo = 'Crear Rol (Perfil)';
        try {

            if (Input::hasPost('rol')) {
                $rol = new Roles(Input::post('rol'));
                if (Input::hasPost('roles_padres')) {
                    $rol->padres = join(',', Input::post('roles_padres'));
                }
                if ($rol->save()) {
                    Flash::valid('El Rol Ha Sido Agregado Exitosamente...!!!');
                    Acciones::add("Agregó el Rol {$rol->rol} al sistema", 'roles');
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
        $this->titulo = 'Editar Rol (Perfil)';
        try {

            $id = Filter::get($id, 'digits');

            View::select('crear');

            $rol = new Roles();

            $this->rol = $rol->find_first($id);

            if (Input::hasPost('rol')) {

                if (Input::hasPost('roles_padres')) {
                    $padres = Input::post('roles_padres');
                    sort($padres);
                    $rol->padres = join(',', $padres);
                }

                if ($rol->update(Input::post('rol'))) {
                    Flash::valid('El Rol Ha Sido Actualizado Exitosamente...!!!');
                    Acciones::add("Editó el Rol {$rol->rol}", 'roles');
                    return Router::redirect();
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                    unset($this->rol); //para que cargue el $_POST en el form
                }
            } else if (!$this->rol) {
                Flash::warning("No existe ningun rol con id '{$id}'");
                return Router::redirect();
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function agregar_padre($id) {
        View::template(NULL);
        try {
            $id = Filter::get($id, 'digits');

            $roles = new Roles();

            $this->rol = $roles->find_first($id);
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function eliminar($id) {
        try {
            $id = Filter::get($id, 'digits');

            $rol = new Roles();

            if (!$rol->find_first($id)) { //si no existe
                Flash::warning("No existe ningun rol con id '{$id}'");
            } else if ($rol->delete()) {
                Flash::valid("El rol <b>{$rol->rol}</b> fué Eliminado...!!!");
                Acciones::add("Eliminó el Rol {$rol->rol} del sistema", 'roles');
            } else {
                Flash::warning("No se Pudo Eliminar el Rol <b>{$rol->rol}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function activar($id) {
        try {
            $id = Filter::get($id, 'digits');

            $rol = new Roles();

            if (!$rol->find_first($id)) { //si no existe
                Flash::warning("No existe ningun rol con id '{$id}'");
            } else if ($rol->activar()) {
                Flash::valid("El rol <b>{$rol->rol}</b> Esta ahora <b>Activo</b>...!!!");
                Acciones::add("Colocó al Rol {$rol->rol} como activo", 'roles');
            } else {
                Flash::warning("No se Pudo Activar el Rol <b>{$rol->rol}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        Router::redirect();
    }

    public function desactivar($id) {
        try {
            $id = Filter::get($id, 'digits');

            $rol = new Roles();

            if (!$rol->find_first($id)) { //si no existe
                Flash::warning("No existe ningun rol con id '{$id}'");
            } else if ($rol->desactivar()) {
                Flash::valid("El rol <b>{$rol->rol}</b> Esta ahora <b>Inactivo</b>...!!!");
                Acciones::add("Colocó al Rol {$rol->rol} como inactivo", 'roles');
            } else {
                Flash::warning("No se Pudo Desactivar el Rol <b>{$rol->rol}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

}
