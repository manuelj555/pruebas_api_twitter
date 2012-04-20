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
Load::models('recursos');

class RecursosController extends AdminController {

    public function index($pagina = 1) {
        try {
            $recursos = new Recursos();
            $this->recursos = $recursos->paginate("page: $pagina");
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function crear() {
        try {
            $this->titulo = 'Crear Recurso';

            if (Input::hasPost('recurso')) {
                $recurso = new Recursos(Input::post('recurso'));
                if ($recurso->save()) {
                    Flash::valid('El Recurso Ha Sido Agregado Exitosamente...!!!');
                    Acciones::add("Agregó al Recurso {$recurso->recurso} al Sistema", 'recursos');
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
            $this->titulo = 'Editar Recurso';
            View::select('crear');

            $recurso = new Recursos();
            $this->recurso = $recurso->find_first($id);

            if (Input::hasPost('recurso')) {
                if ($recurso->update(Input::post('recurso'))) {
                    Flash::valid('El Recurso ha sido Actualizado Exitosamente...!!!');
                    Acciones::add("Editó al Recurso {$recurso->recurso}", 'recursos');
                    return Router::redirect();
                } else {
                    Flash::warning('No se Pudieron Guardar los Datos...!!!');
                    unset($this->recurso); //para que cargue el $_POST en el form
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

    public function activar($id) {
        try {
            $rec = new Recursos();
            $rec->find_first($id);
            if ($rec->activar()) {
                Flash::valid("El recurso <b>{$rec->recurso}</b> Esta ahora <b>Activo</b>...!!!");
                Acciones::add("Colocó al Recurso {$rec->recurso} como activo", 'recursos');
            } else {
                Flash::warning("No se Pudo Activar el Recurso <b>{$rec->recurso}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function desactivar($id) {
        try {
            $rec = new Recursos();
            $rec->find_first($id);
            if ($rec->desactivar()) {
                Flash::valid("El recurso <b>{$rec->recurso}</b> Esta ahora <b>Inactivo</b>...!!!");
                Acciones::add("Colocó al Recurso {$rec->recurso} como inactivo", 'recursos');
            } else {
                Flash::warning("No se Pudo Desactivar el Recurso <b>{$rec->recurso}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function eliminar($id) {
        try {
            $rec = new Recursos();
            $rec->find_first($id);
            if ($rec->delete()) {
                Flash::valid("El recurso <b>{$rec->recurso}</b> ha sido Eliminado...!!!");
                Acciones::add("Eliminó al Recurso {$rec->recurso} del Sistema", 'recursos');
            } else {
                Flash::warning("No se Pudo Eliminar el Recurso <b>{$rec->recurso}</b>...!!!");
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
        return Router::redirect();
    }

    public function escaner($pagina = 1) {
        try {
            $recurso = new Recursos();
            $this->recursos = $recurso->obtener_recursos_nuevos($pagina);
            if (Input::hasPost('guardar')) {
                if ($recurso->guardar_nuevos()) {
                    $this->recursos = $recurso->obtener_recursos_nuevos($pagina);
                    Input::delete();
                    Flash::valid('Los Recursos Fueron Guardados Exitosamente...!!!');
                    Acciones::add('Agrego Nuevos Recursos al Sistema', 'recursos');
                } else {
                    Flash::warning('Por favor Complete los datos requeridos he intente guardar nuevamente');
                }
            }
        } catch (KumbiaException $e) {
            View::excepcion($e);
        }
    }

}
