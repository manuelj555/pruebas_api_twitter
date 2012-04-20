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
 * @package Modelos
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
class RolesRecursos extends ActiveRecord {

//    public $debug = true;

    public function initialize() {
        $this->belongs_to('roles');
    }

    public function obtener_privilegios() {
        $privilegios = array();
        foreach ($this->find() as $e) {
            $privilegios["{$e->roles_id}-{$e->recursos_id}"] = $e->id;
        }
        return $privilegios;
    }

    public function eliminarTodos() {
        return $this->delete_all();
    }

    /**
     * Elimina todos los registros por id ejemplo 
     * 
     * @example
     * 
     * eliminarPorIds("1,2,3,4");
     * 
     * elimina los registros con id 1,2,3 y 4
     *
     * @param string $ids
     * @return boolean 
     */
    public function eliminarPorIds($ids) {
        if (!empty($ids)) {
            $ids = str_replace('"', "'", Util::encomillar($ids));
            return $this->delete_all("id IN ($ids)");
        }else{
            return true;
        }
    }

    public function guardar($rol, $recurso) {
        if ($this->existe($rol, $recurso))
            return true;
        return $this->create(array(
            'roles_id' => $rol,
            'recursos_id' => $recurso
        ));
    }

    public function editarPrivilegios($privilegios, $privilegios_a_eliminar) {
        $this->begin();
        //elimino todo de la bd
        if (!$this->eliminarPorIds($privilegios_a_eliminar)) {
            $this->rollback();
            return false;
        }
        foreach ((array) $privilegios as $e) {
            $data = explode('/', $e); //el formato es 1/4 = rol/recurso
            if (!$this->guardar($data[0], $data[1])) {
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
    }

    public function existe($rol, $recurso) {
        return $this->exists("roles_id = '$rol' AND recursos_id = '$recurso'");
    }

}

