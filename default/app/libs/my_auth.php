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
* @package Libs
* @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
* @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
*/
class MyAuth {

    protected static $_clave_sesion = PUBLIC_PATH;

    public static function autenticar($user, $pass) {
        Session::set(self::$_clave_sesion . '_sesion_activa', false);
        $pass = md5($pass);
        $auth = new Auth('class: usuarios',
                        'login: ' . $user,
                        'clave: ' . $pass,
                        "activo: 1");
        if ($auth->authenticate()) {
            Session::set(self::$_clave_sesion . '_sesion_activa', true);
        }
        return self::es_valido();
    }

    public static function es_valido() {
        return Session::get(self::$_clave_sesion . '_sesion_activa');
    }

    public static function cerrar_sesion() {
        Auth::destroy_identity();
        Session::delete(self::$_clave_sesion . '_sesion_activa');
    }

}

