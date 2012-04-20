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
class Recursos extends ActiveRecord {

//    public $debug = true;

    public function initialize() {
        //validaciones
        $this->validates_presence_of('controlador', 'message: Debe escribir un <b>Controlador</b>');
        $this->validates_presence_of('descripcion', 'message: Debe escribir una <b>Descripción</b>');
    }

    public function before_validation_on_create() {
        $this->validates_uniqueness_of('recurso', 'message: Este Recurso <b>ya existe</b> en el sistema');
    }

    public function obtener_recursos_por_rol($id_rol) {
        $cols = 'recursos.recurso';
        $joins = 'INNER JOIN roles_recursos as r ON r.recursos_id = recursos.id';
        $where = "r.roles_id = '$id_rol'";
        return $this->find("columns: $cols", "join: $joins", "$where");
    }

    public function before_validation() {
        if (empty($this->recurso)) {
            $this->recurso = !empty($this->modulo) ? "$this->modulo/" : '';
            $this->recurso .= "$this->controlador/";
            $this->recurso .= ! empty($this->accion) ? "$this->accion" : '*';
        }
    }

    public function obtener_recursos_nuevos($pagina = 1) {
        $recursos = LectorRecursos::obtenerRecursos();
        foreach ($recursos as $index => $re) {
            if ($this->exists('recurso = \'' . $re['recurso'] . '\'')) {
                unset($recursos[$index]);
            }
        }
        $recursos = LectorRecursos::paginar($recursos, $pagina, 6);
        $this->recursos_nuevos = $recursos->items;
        array_unshift($this->recursos_nuevos, null);
        return $recursos;
    }

    public function guardar_nuevos() {
        $recursos_a_guardar = array();
        $recursos_chequeados = Input::post('check');
        $descripciones = Input::post('descripcion');
        $activos = Input::post('activo');
        if ($recursos_chequeados) {
            foreach ($recursos_chequeados as $valor) {
                if (empty($descripciones[$valor])) {
                    Flash::error('Existen Recursos Seleccionados que no tienen especificada una Descripción');
                    return FALSE;
                }
                $data = null;
                $data = $this->recursos_nuevos[$valor];
                $data['descripcion'] = $descripciones[$valor];
                $data['activo'] = $activos[$valor];
                $recursos_a_guardar[] = $data;
            }
        } else {
            return false;
        }
        $this->begin();
        foreach ($recursos_a_guardar as $e) {
            if (!$this->save($e)) {
                $this->rollback();
                return FALSE;
            }
        }
        $this->commit();
        return TRUE;
    }

}
