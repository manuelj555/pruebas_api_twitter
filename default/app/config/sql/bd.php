<?php

function getEsquema()
{

    $db = Db::factory();

    return array(
        'roles' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'rol' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50,
                'not_null' => TRUE,
                'unique_index' => TRUE,
            ),
            'padres' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 200,
            ),
            'plantilla' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50,
            ),
            'activo' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '1'",
            ),
        ),
        'recursos' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'modulo' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50
            ),
            'controlador' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50,
                'not_null' => TRUE,
            ),
            'accion' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50
            ),
            'recurso' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 200,
                'not_null' => TRUE,
            ),
            'descripcion' => array(
                'type' => 'text',
            ),
            'activo' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '1'",
            ),
        ),
        'roles_recursos' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'roles_id' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
            ),
            'recursos_id' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
            )
        ),
        'usuarios' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'roles_id' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
            ),
            'login' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50,
                'unique_index' => TRUE,
                'not_null' => TRUE,
            ),
            'clave' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 40,
                'not_null' => TRUE,
            ),
            'nombres' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 100,
                'not_null' => TRUE,
            ),
            'email' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 100,
                'not_null' => TRUE,
            ),
            'activo' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '1'",
            ),
        ),
        'menus' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'menus_id' => array(
                'type' => $db::TYPE_INTEGER,
            ),
            'recursos_id' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
            ),
            'nombre' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 100,
                'not_null' => TRUE,
            ),
            'url' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 100,
                'not_null' => TRUE,
            ),
            'posicion' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '100'",
            ),
            'clases' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 50,
            ),
            'visible_en' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '1'",
            ),
            'activo' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
                'extra' => "DEFAULT '1'",
            ),
        ),
        'auditorias' => array(
            'id' => array(
                'type' => $db::TYPE_INTEGER,
                'primary' => TRUE,
                'auto' => TRUE,
                'not_null' => TRUE,
            ),
            'usuarios_id' => array(
                'type' => $db::TYPE_INTEGER,
                'not_null' => TRUE,
            ),
            'fecha_at' => array(
                'type' => $db::TYPE_DATE,
                'not_null' => TRUE,
            ),
            'accion_realizada' => array(
                'type' => 'text',
                'not_null' => TRUE,
            ),
            'tabla_afectada' => array(
                'type' => $db::TYPE_VARCHAR,
                'size' => 150,
            ),
        ),
    );
}

function getDataTable($tabla)
{
    $datas = array(
        'roles' => "INSERT INTO roles (id, rol, padres, plantilla, activo) VALUES
                    (1, 'usuario comun', NULL, NULL, '1'),
                    (2, 'usuario administrador', '1', NULL, '1'),
                    (4, 'administrador del sistema', '1,2', NULL, '1')",
        'recursos' => "INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES
                    (1, 'admin', 'usuarios', NULL, 'admin/usuarios/*', 'modulo para la administracion de los usuarios del sistema', '1'),
                    (2, 'admin', 'roles', NULL, 'admin/roles/*', 'modulo para la gestion de los roles de la aplicacion\r\n', '1'),
                    (3, 'admin', 'recursos', NULL, 'admin/recursos/*', 'modulo para la gestion de los recursos de la aplicacion', '1'),
                    (4, 'admin', 'menu', NULL, 'admin/menu/*', 'modulo para la administracion del menu en la app', '1'),
                    (5, 'admin', 'privilegios', NULL, 'admin/privilegios/*', 'modulo para la administracion de los privilegios que tendra cada rol', '1'),
                    (11, NULL, 'index', NULL, 'index/*', 'modulo inicial del sistema, donde se loguean los usuarios y donde se desloguean', '1'),
                    (14, 'admin', 'usuarios', 'perfil', 'admin/usuarios/perfil', 'modulo para la configuracion del perfil del usuario', '1'),
                    (15, 'admin', 'index', NULL, 'admin/index/*', 'modulo para la configuración del sistema', '1'),
                    (17, 'admin', 'usuarios', 'index', 'admin/usuarios/index', 'modulo para listar los usuarios del sistema, lo usará¡ el menu administracion', '1'),
                    (18, 'admin', 'auditorias', NULL, 'admin/auditorias/*', 'Modulo para revisar las acciones que los usuarios han realizado en el sistema', '1'),
                    (19, NULL, 'index', 'index', 'index/index', 'recurso que no necesita permisos, es solo de prueba :-)', '1')",
        'usuarios' => "INSERT INTO usuarios (id, login, clave, nombres, email, roles_id, activo) VALUES
                    (2, 'usuario', '202cb962ac59075b964b07152d234b70', 'usuario del sistema', 'asd', 1, '1'),
                    (3, 'admin', '202cb962ac59075b964b07152d234b70', 'usuario administrador del sistema', 'manuel_j555@hotmail.com', 4, '1')",
        'roles_recursos' => "INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES
                    (636, 1, 1),
                    (633, 1, 2),
                    (630, 1, 3),
                    (624, 1, 4),
                    (627, 1, 5),
                    (645, 1, 11),
                    (642, 1, 14),
                    (621, 1, 15),
                    (639, 1, 17),
                    (618, 1, 18),
                    (637, 2, 1),
                    (634, 2, 2),
                    (631, 2, 3),
                    (625, 2, 4),
                    (628, 2, 5),
                    (646, 2, 11),
                    (643, 2, 14),
                    (622, 2, 15),
                    (640, 2, 17),
                    (619, 2, 18),
                    (638, 4, 1),
                    (635, 4, 2),
                    (632, 4, 3),
                    (626, 4, 4),
                    (629, 4, 5),
                    (647, 4, 11),
                    (644, 4, 14),
                    (623, 4, 15),
                    (641, 4, 17),
                    (620, 4, 18)",
        'menus' => "INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, visible_en, activo) VALUES
                    (1, 18, 1, 'Usuarios', 'admin/usuarios', 10, NULL, 2, '1'),
                    (3, 18, 2, 'Roles', 'admin/roles', 20, NULL, 2, '1'),
                    (4, 18, 3, 'Recursos', 'admin/recursos', 30, NULL, 2, '1'),
                    (5, 18, 4, 'Menu', 'admin/menu', 100, NULL, 2, '1'),
                    (7, 18, 5, 'Privilegios', 'admin/privilegios', 90, NULL, 2, '1'),
                    (18, NULL, 17, 'Administración', 'admin/usuarios/index', 100, NULL, 2, '1'),
                    (19, NULL, 14, 'Mi Perfil', 'admin/usuarios/perfil', 90, NULL, 2, '1'),
                    (21, 18, 15, 'Config. Aplicacion', 'admin', 1000, NULL, 2, '1'),
                    (22, 18, 18, 'Auditorias', 'admin/auditorias', 900, NULL, 2, '1')"
    );

    return array_key_exists($tabla, $datas) ? $datas[$tabla] : '';
}