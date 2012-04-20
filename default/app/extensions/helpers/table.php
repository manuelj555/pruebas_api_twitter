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
class Table {

    /**
     * array donde se almacenaran las cabeceras de la tabla
     *
     * ejemplo:
     *
     * <code>
     *    array("nombres","apellidos","correo electronico")
     * </code>
     *
     * @var array
     */
    static protected $_headers = array();

    /**
     * campos del modelo que serán mostrados en la tabla
     *
     * ejemplo:
     *
     * <code>
     *    array("nombres","apellidos","correo")
     * </code>
     * 
     * @var array
     */
    static protected $_fields = array();

    /**
     *  url para el paginador, si no se especifica usa el modelo , controlador , accion actual
     *
     * @var string
     */
    static protected $_url = null;

    /**
     * Modelo con los datos a mostrar en la tabla

     * @var ActiveRecord
     */
    static protected $_model = null;

    /**
     * paginador para la tabla
     *
     * @var Paginator
     */
    static protected $_paginator = null;

    /**
     * Tipo de paginador a mostrar en la tabla
     *
     * @var string
     */
    static protected $_type_paginator = null;
    
    /**
     * Indica si se usaran los campos del modelo por defecto ó
     * si se mostraran los que el usuario especifique
     *
     * @var boolean 
     */
    static protected $_use_default_fields = true;

    /**
     * Establece|añade los nombres de las cabeceras de las columnas de la tabla
     *
     *
     * Ejemplo:
     *
     * <code>
     *  Table::headers("nombres","apellidos","cedula");
     *  Table::headers(array("direccion","telefono"));
     * </code>
     *
     */
    public static function headers() {
        $params = func_get_args();
        if (is_array($params[0])) {
            self::$_headers = array_merge(self::$_headers, $params[0]);
        } else {
            self::$_headers = array_merge(self::$_headers, $params);
        }
    }

    /**
     * Establece|añade los campos del modelo a mostrar en la tabla
     *
     *
     * Ejemplo:
     *
     * <code>
     *  Table::fields("nombres","apellidos","cedula");
     *  Table::fields(array("direccion","telefono"));
     *  Table::fields("nombres","apellidos","activo: Activo|Inactivo");
     * </code>
     *
     *
     */
    public static function fields() {
        $params = func_get_args();
        if (is_array($params[0])) {
            $params = $params[0];
        }
        self::$_fields = array_merge(self::$_fields, $params);
        self::$_use_default_fields = false;
    }

    /**
     * Genera una tabla con los datos del modelo, y muestra la informacion
     * previamente establecida con los metodos headers, fields, etc...
     *
     * @param ActiveRecord $model modelos con los datos a mostrar
     * @param string $attrs atributos opcionales para la tabla (opcional)
     * @return string tabla generada
     */
    public static function create($model, $attrs = NULL) {
        if (@isset($model->items)) {
            self::$_paginator = $model;
            $model = $model->items;
        }
        if (self::$_use_default_fields) {
            self::get_table_schema($model);
        }
        $table = "<table $attrs>";
//head de la tabla
        $table .= '<thead>';
        $table .= '<tr style="text-align:center;font-weight:bold;">';
        foreach (self::$_headers as $e) {
            $table .= "<th>$e</th>";
        }
        $table .= '</tr>';
        $table .= '</thead>';
//foot de la tabla
        if (self::$_paginator) {
            $table .= '   <tfoot><tr><th colspan="100">';
            $table .= self::paginator();
            $table .= '</th></tr></tfoot>';
        } else {
            $table .= '   <tfoot><tr><th colspan="100">';
            $table .= '<span style="float:right;margin-right:20px;"><b>Total registros: ' . count($model) . '</b></span>';
            $table .= '</th></tr></tfoot>';
        }
//body de la tabla
        $table .= '<tbody>';
        if (sizeof($model)) {
            foreach ($model as $model) {
                $table .= '<tr>';
                foreach (self::$_fields as $e) {
                    if (is_array($e)) {
                        $table .= self::option($model, $e);
                    } else {
                        $parts = explode(': ', $e);
                        $field = $parts[0];
                        if (sizeof($parts) > 1) {
                            $parts = array_reverse(explode('|', $parts[1]));
                            $table .= '<td>' . h($parts[$model->$field]) . '</td>';
                        } else {
                            $table .= '<td>' . h($model->$field) . '</td>';
                        }
                    }
                }
                $table .= '</tr>';
            }
        } else {
            $table .= '<tr><td colspan="100">La Consulta no Arrojó Ningun Registro</td></tr>';
        }
        $table .= '</tbody>';
        $table .= "</table>";
        return $table;
    }

    /**
     * Crea un link en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public static function link($action, $text, $boolean_field = NULL) {
        $columna = array(
            'type' => 'link',
            'action' => $action,
            'text' => $text,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    /**
     * Crea un link en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar en el link separados por |
     * @param string $confirm pregunta/s a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public static function linkConfirm($action, $text, $confirm = '¿Esta Seguro?', $boolean_field = NULL) {
        $columna = array(
            'type' => 'link_confirm',
            'action' => $action,
            'confirm' => $confirm,
            'text' => $text,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public static function img($url_img, $text = NULL, $boolean_field = NULL) {
        $columna = array(
            'type' => 'img',
            'url_img' => $url_img,
            'text' => $text,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public static function imgLink($url_img, $action, $text = NULL, $boolean_field = NULL) {
        $columna = array(
            'type' => 'img_link',
            'url_img' => $url_img,
            'action' => $action,
            'text' => $text,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    /**
     * Crea un link con una imagen en las filas la tabla, por ejemplo la
     * opcion modificar, eliminar, etc
     *
     * @param string $url_img direccion ó direcciones donde se encuentra la imagen separados por |
     * @param string $action url ó urls a donde apuntaró el link separados por |
     * @param string $text texto ó textos a mostrar al lado de la imagen separados por | (opcional)
     * * @param string $confirm pregunta/s a mostrar en el link separados por |
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la url/texto a utilizar en el link (opcional)
     */
    public static function imgLinkConfirm($url_img, $action, $text = NULL, $confirm = '¿Esta Seguro?', $boolean_field = NULL) {
        $columna = array(
            'type' => 'img_link_confirm',
            'url_img' => $url_img,
            'action' => $action,
            'text' => $text,
            'confirm' => $confirm,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    /**
     *  agrega un check a la tabla
     *
     * @param string $field_name nombre del check
     * @param string $boolean_field campo del modelo que se usará para
     * condicionar la si se muestra ó no el check (opcional)
     */
    public static function check($field_name, $boolean_field = NULL) {
        $columna = array(
            'type' => 'check',
            'field' => $field_name,
            'boolean_field' => $boolean_field
        );
        self::$_fields[] = $columna;
    }

    protected static function option($model, $data) {
        switch ($data['type']) {
            case 'link' :
                return self::td_link($model, $data);
            case 'link_confirm' :
                return self::td_link_confirm($model, $data);
            case 'img' :
                return self::td_img($model, $data);
            case 'img_link' :
                return self::td_img_link($model, $data);
            case 'img_link_confirm' :
                return self::td_img_link_confirm($model, $data);
            case 'check' :
                if ($data['boolean_field']) {
                    $field_value = $model->$data['boolean_field'];
                    if ($field_value) {
                        $check = Form::check($data['field'] . ".{$model->id}", $model->id);
                    } else {
                        $check = '';
                    }
                } else {
                    $check = Form::check($data['field'] . ".{$model->id}", $model->id);
                }
                return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                        $check .
                        '</td>';
        }
    }

    protected static function td_link($model, $data) {
        $urls = array_reverse(explode('|', $data['action']));
        $texts = array_reverse(explode('|', $data['text']));
        if ($data['boolean_field']) {
            $field_value = $model->$data['boolean_field'];
            if (sizeof($urls) == 1) {
                $url = $urls[0] . '/' . $model->id;
            } else {
                $url = $urls[$field_value] . '/' . $model->id;
            }
            if (sizeof($texts) == 1) {
                $text = $texts[0];
            } else {
                $text = $texts[$field_value];
            }
        } else {
            $url = $urls[0] . '/' . $model->id;
            $text = $texts[0];
        }
        return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                Html::link($url, $text, "title='$text'") . '</td>';
    }

    protected static function td_img($model, $data) {
        $url_img = array_reverse(explode('|', $data['url_img']));
        $texts = array_reverse(explode('|', $data['text']));
        if ($data['boolean_field']) {
            $field_value = $model->$data['boolean_field'];
            if (sizeof($url_img) == 1) {
                $url_img = $url_img[0];
            } else {
                $url_img = $url_img[$field_value];
            }
            if (sizeof($texts) == 1) {
                $text = $texts[0];
            } else {
                $text = $texts[$field_value];
            }
        } else {
            $url_img = $url_img[0];
            $text = $texts[0];
        }
        return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                Html::img($url_img, $text, 'style="width: 20px;margin-left:10px;margin-right:10px;" align="left"') . $text . '</td>';
    }

    protected static function td_img_link($model, $data) {
        $url_img = array_reverse(explode('|', $data['url_img']));
        $url_link = array_reverse(explode('|', $data['action']));
        $texts = array_reverse(explode('|', $data['text']));
        if ($data['boolean_field']) {
            $field_value = $model->$data['boolean_field'];
            if (sizeof($url_img) == 1) {
                $url_img = $url_img[0];
            } else {
                $url_img = $url_img[$field_value];
            }
            if (sizeof($url_link) == 1) {
                $url_link = $url_link[0] . "/{$model->id}";
            } else {
                $url_link = $url_link[$field_value] . "/{$model->id}";
            }
            if (sizeof($texts) == 1) {
                $text = $texts[0];
            } else {
                $text = $texts[$field_value];
            }
        } else {
            $url_img = $url_img[0];
            $url_link = $url_link[0] . "/{$model->id}";
            $text = $texts[0];
        }
        return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                Html::link($url_link, Html::img($url_img, $text, 'style="width: 20px;margin-left:10px;margin-right:10px;" align="left"')
                        . $text, "title='$text'") .
                '</td>';
    }

    protected static function td_link_confirm($model, $data) {
        $urls = array_reverse(explode('|', $data['action']));
        $texts = array_reverse(explode('|', $data['text']));
        $confirm = array_reverse(explode('|', $data['confirm']));
        if ($data['boolean_field']) {
            $field_value = $model->$data['boolean_field'];
            if (sizeof($urls) == 1) {
                $url = $urls[0] . '/' . $model->id;
            } else {
                $url = $urls[$field_value] . '/' . $model->id;
            }
            if (sizeof($texts) == 1) {
                $text = $texts[0];
            } else {
                $text = $texts[$field_value];
            }
            if (sizeof($confirm) == 1) {
                $confirm = $confirm[0];
            } else {
                $confirm = $confirm[$field_value];
            }
        } else {
            $url = $urls[0] . '/' . $model->id;
            $text = $texts[0];
            $confirm = $confirm[0];
        }
        return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                Js::link($url, $text, $confirm) . '</td>';
    }

    protected static function td_img_link_confirm($model, $data) {
        $url_img = array_reverse(explode('|', $data['url_img']));
        $urls = array_reverse(explode('|', $data['action']));
        $texts = array_reverse(explode('|', $data['text']));
        $confirm = array_reverse(explode('|', $data['confirm']));
        if ($data['boolean_field']) {
            $field_value = $model->$data['boolean_field'];
            if (sizeof($url_img) == 1) {
                $url_img = $url_img[0];
            } else {
                $url_img = $url_img[$field_value];
            }
            if (sizeof($urls) == 1) {
                $url = $urls[0] . '/' . $model->id;
            } else {
                $url = $urls[$field_value] . '/' . $model->id;
            }
            if (sizeof($texts) == 1) {
                $text = $texts[0];
            } else {
                $text = $texts[$field_value];
            }
            if (sizeof($confirm) == 1) {
                $confirm = $confirm[0];
            } else {
                $confirm = $confirm[$field_value];
            }
        } else {
            $url_img = $url_img[0];
            $url = $urls[0] . '/' . $model->id;
            $text = $texts[0];
            $confirm = $confirm[0];
        }
        return '<td style="text-align:center;padding-left:10px;padding-right:10px">' .
                Js::link($url, Html::img($url_img, $text, 'style="width: 20px;margin-left:10px;margin-right:10px;" align="left"')
                        . $text, $confirm) . '</td>';
    }

    protected static function paginator() {
        if (!self::$_url) {
            if (Router::get('module'))
                self::$_url = Router::get('module') . '/' . Router::get('controller') . '/' . Router::get('action') . '/';
            else
                self::$_url = Router::get('controller') . '/' . Router::get('action') . '/';
        }
        if (!self::$_type_paginator) {
            $html = '<div class="paginador-tabla">';
            if (self::$_paginator->count > self::$_paginator->per_page) {
                if (self::$_paginator->prev) {
                    $html .= Html::link(self::$_url . self::$_paginator->prev, 'Anterior', 'title="Ir a la p&aacute;g. anterior"');
                    $html .= '&nbsp;&nbsp;';
                }
                for ($x = 1; $x <= self::$_paginator->total; ++$x) {
                    $html .= self::$_paginator->current == $x ? '<b>' . $x . '</b>' : Html::link(self::$_url . $x, $x);
                    $html .= '&nbsp;&nbsp;';
                }
                if (self::$_paginator->next) {
                    $html .= Html::link(self::$_url . self::$_paginator->next, 'Siguiente', 'title="Ir a la p&aacute;g. siguiente"');
                }
            }
            $html .= '<span style="float:right;margin-right:20px;"><b>Total registros: ' . self::$_paginator->count . '</b></span></div>';
            $html .= '
		<style type="text/css">
					.paginador-tabla a{
						color: #777;
						text-decoration:none;
						font-weight:bold !important;
						font-size:12px !important;
					}
					</style>';
            return $html;
        } else {
            $parametros = array(
                'page' => self::$_paginator,
                'url' => substr(self::$_url, 0, strlen(self::$_url) - 1)
            );
            ob_start();
            KumbiaView::partial('paginators/' . self::$_type_paginator, false, $parametros);
            $paginador = ob_get_contents();
            ob_get_clean();
            return $paginador;
        }
    }

    /**
     * Establece la url para el paginador, si no se estable usa el
     * modulo/controlador/accion actual.
     *
     * Ejemplo:
     *
     * <code>
     *      Table::url('usuarios/index');
     * </code>
     *
     * @param string $url
     */
    public static function url($url) {
        self::$_url = "$url/";
    }

    /**
     * Establece el paginador de kumbia a utilizar en la tabla,
     * si no se estable utiliza uno interno del helper
     *
     * @param string $paginator
     */
    public static function typePaginator($paginator) {
        self::$_type_paginator = $paginator;
    }

    /**
     * Indica los nombres de las columnas y los campos a mostrar por defecto
     * del Modelo si no se especifican ningunos en ninun momento
     * 
     * @param ActiveRecord $model modelo del que se hará la lista
     */
    protected static function get_table_schema($model) {
        if ($model) {
            self::$_fields = array_merge(current($model)->fields, self::$_fields);
            self::$_headers = array_merge(current($model)->alias, self::$_headers);
        }
    }

}