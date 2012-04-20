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
 * @package Helper
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
Load::models('auditorias');

class LectorClases {

    protected static $_clase = NULL;
    protected static $_metodos_publicos = array();
    protected static $_metodos_protegidos = array();
    protected static $_metodos_privados = array();
    private static $_archivo = NULL;

    public static function leerDir($dir) {
        if (!file_exists($dir))
            throw new KumbiaException('No existe el archivo en el directorio ' . $dir);
        self::$_archivo = file_get_contents($dir);
        self::$_archivo = preg_replace('/(\/\*)(.*?)(\*\/)/ms', '', self::$_archivo);
        self::$_archivo = preg_replace('/(\/\/).*/', '', self::$_archivo);
        self::obtenerClase();
        self::obtenerMetodosPublicos();
        self::obtenerMetodosProtegidos();
        self::obtenerMetodosPrivados();
        self::$_metodos_publicos = array_diff(self::$_metodos_publicos, self::$_metodos_protegidos, self::$_metodos_privados);
        return self::getEstructuraCompleta();
    }

    protected static function obtenerClase() {
        preg_match('/class\s+?(.+?)\s/', self::$_archivo, $array);
        self::$_clase = $array[1];
    }

    protected static function obtenerMetodosPublicos() {
        if (preg_match_all('/function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_publicos = $array[1];
        }
    }

    protected static function obtenerMetodosProtegidos() {
        if (preg_match_all('/protected\s+?function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_protegidos = $array[1];
        }
    }

    protected static function obtenerMetodosPrivados() {
        if (preg_match_all('/private\s+?function\s+?(.+?)\(/', self::$_archivo, $array)) {
            self::$_metodos_privados = $array[1];
        }
    }

    public static function getClase() {
        return self::$_clase;
    }

    public static function getMetodosPublicos() {
        return self::$_metodos_publicos;
    }

    public static function getMetodosProtegidos() {
        return self::$_metodos_protegidos;
    }

    public static function getMetodosPrivados() {
        return self::$_metodos_privados;
    }

    public static function getEstructuraCompleta() {
        return array(
            'clase' => self::$_clase,
            'metodos_publicos' => self::$_metodos_publicos,
            'metodos_protegidos' => self::$_metodos_protegidos,
            'metodos_privados' => self::$_metodos_privados
        );
    }

}

