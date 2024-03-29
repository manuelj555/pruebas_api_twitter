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
class Menus extends ActiveRecord {
//    public $debug = true;
//    public $logger = true;

    /**
     * Constante que define que el menu es visible desde app
     */
    const VISIBILIDAD_APP = 1;

    /**
     * Constante que define que el menu es visible desde el backend
     */
    const VISIBILIDAD_BACKEND = 2;

    /*
     * Constante que define que el menu es visible desde cualquier lado
     */
    const VISIBILIDAD_TODAS = 3;

    public function initialize() {
        $this->has_many('menus');
        //validaciones
        $this->validates_presence_of('recursos_id', 'message: Debe seleccionar un <b>Recurso</b> al cual se va acceder');
        $this->validates_presence_of('nombre', 'message: Debe escribir el <b>Texto a Mostrar</b> en el menu');
        $this->validates_presence_of('url', 'message: Debe escribir la <b>URL</b> en el menu');
    }

    public function before_validation_on_create() {
        $this->validates_uniqueness_of('nombre', 'message: Ya hay un menu con el <b>mismo Nombre</b>');
    }

    public function obtener_menu_por_rol($id_rol, $entorno) {
        $select = 'm.' . join(',m.', $this->fields) . ',re.recurso';
        $from = 'menus as m';
        $joins = "INNER JOIN roles_recursos AS rr ON m.recursos_id = rr.recursos_id ";
        $joins .= " AND ( " . $this->obtener_condicion_roles_padres($id_rol) . " ) ";
        $joins .= 'INNER JOIN recursos AS re ON re.activo = 1 AND re.id = rr.recursos_id ';
        $condiciones = " m.menus_id is NULL AND m.activo = 1 ";
        $condiciones .= " AND visible_en IN ('3','$entorno') ";
        $orden = 'm.posicion';
        $agrupar_por = 'm.' . join(',m.', $this->fields) . ',re.recurso';
        return $this->find_all_by_sql("SELECT $select FROM $from $joins WHERE $condiciones GROUP BY $agrupar_por ORDER BY $orden");
    }

    public function get_sub_menus($id_rol, $entorno) {
        $campos = 'menus.' . join(',menus.', $this->fields) . ',r.recurso';
        $join = 'INNER JOIN recursos as r ON r.id = menus.recursos_id AND r.activo = 1 ';
        $join .= 'INNER JOIN roles_recursos as rr ON r.id = rr.recursos_id ';
        $join .= ' AND (rr.roles_id = \'' . $id_rol . '\' OR ' . $this->obtener_condicion_roles_padres($id_rol) . ')';
        $condiciones = "menus.menus_id = '{$this->id}' AND menus.activo = 1 ";
        $condiciones .= " AND visible_en IN ('3','$entorno') ";
        $agrupar_por = 'menus.' . join(',menus.', $this->fields) . ',r.recurso';
        return $this->find($condiciones, "join: $join", "columns: $campos", 'order: menus.posicion', "group: $agrupar_por");
    }

    public function menus_paginados($pagina) {
        $cols = 'menus.' . join(',menus.', $this->fields) . ",r.recurso,m2.nombre as padre";
        $joins = 'INNER JOIN recursos as r ON r.id = recursos_id ';
        $joins .= 'LEFT JOIN menus as m2 ON m2.id = menus.menus_id ';
        return $this->paginate("page: $pagina", "columns: $cols", "join: $joins");
    }

    public function before_save() {
        $this->posicion = !empty($this->posicion) ? $this->posicion : '100';
    }

    protected function obtener_condicion_roles_padres($id_rol) {
        $rol = Load::model('roles')->find_first($id_rol);
        $roles = array("rr.roles_id = '{$rol->id}'");
        if ($rol->padres) {
            foreach (explode(',', $rol->padres) as $e) {
                $roles[] = "rr.roles_id = '$e'";
            }
        }
        return join(' OR ', $roles);
    }

}

