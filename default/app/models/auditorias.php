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
class Auditorias extends ActiveRecord {

//    public $debug = true;

    public function initialize() {
        //relaciones
        $this->belongs_to('usuarios');
    }

    public function crear_filtro($filtro) {
        $condiciones = array();
        if (!empty($filtro['tabla_afectada'])) {

            $condiciones[] = "tabla_afectada LIKE '%{$filtro['tabla_afectada']}%'";
        }
        if (!empty($filtro['fecha_at'])) {
            $filtro['fecha_at'] = date('Y-m-d', strtotime($filtro['fecha_at']));
            $condiciones[] = "fecha_at LIKE '%{$filtro['fecha_at']}%'";
        }
        if (!empty($filtro['accion_realizada'])) {

            $condiciones[] = "accion_realizada LIKE '%{$filtro['accion_realizada']}%'";
        }
        return sizeof($condiciones) ? join(' AND ', $condiciones) : 'TRUE';
    }

    public function auditorias_por_usuario($id_usuario, $pagina = 1, $filtro = NULL) {
        $condiciones = 'TRUE';
        if ($filtro) {
            $condiciones = $this->crear_filtro($filtro);
        }
        $id_usuario = Filter::get($id_usuario, 'int');
        $where = "usuarios_id = '{$id_usuario}' AND {$condiciones}";
        return $this->paginate("page: $pagina", "conditions: $where", "order: id desc");
    }

    public function tablas_afectadas() {
        $tablas = array('Seleccione');
        foreach ($this->distinct('tabla_afectada') as $e) {
            $tablas["$e"] = $e;
        }
        return $tablas;
    }

}

