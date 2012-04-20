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
Load::lib('api_twitter/tmhOAuth');
Load::lib('api_twitter/tmhUtilities');

class IndexController extends AppController
{

    public function before_filter()
    {
        if (!Session::has('access_token') && $this->action_name !== 'index') {
            Flash::error('Problemas al intentar conectar ' .
                            Html::linkAction('obtener_tokens', 'Volver a Intentar'));
            $this->action_name = 'index';
        }
    }

    public function index()
    {
        $tmhOAuth = new tmhOAuth(Config::get('config.twitter'));
        if (Session::has('access_token')) {
            $this->validar_credenciales($tmhOAuth, Session::get('access_token'));
            return $this->timeline();
        } elseif (Input::hasRequest('oauth_verifier')) {
            return $this->acceso_tokens($tmhOAuth, Session::get('oauth'));
        } else {
            return $this->obtener_tokens($tmhOAuth);
        }
    }

    protected function obtener_tokens($tmhOAuth)
    {
        View::select(__FUNCTION__);

        $params['oauth_callback'] = tmhUtilities::php_self();

        $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);

        if ($code == 200) {
            Session::set('oauth', $data = $tmhOAuth->extract_params($tmhOAuth->response['response']));
            $this->link = $tmhOAuth->url("oauth/authenticate", '') . "?oauth_token={$data['oauth_token']}";
        } else {
            $this->outputError($tmhOAuth);
        }
    }

    protected function acceso_tokens($tmhOAuth, $dataTokens)
    {
        View::select(__FUNCTION__);
        $tmhOAuth->config['user_token'] = $dataTokens['oauth_token'];
        $tmhOAuth->config['user_secret'] = $dataTokens['oauth_token_secret'];

        $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
                    'oauth_verifier' => Input::request('oauth_verifier')
                ));

        if ($code == 200) {
            Session::set('access_token', $tmhOAuth->extract_params($tmhOAuth->response['response']));
            //Session::delete('oauth');
            Flash::valid('Listo :-)');
            return Router::redirect();
        } else {
            $this->outputError($tmhOAuth);
        }
    }

    protected function validar_credenciales($tmhOAuth, $dataTokens)
    {
        View::select(__FUNCTION__);
        $tmhOAuth->config['user_token'] = $dataTokens['oauth_token'];
        $tmhOAuth->config['user_secret'] = $dataTokens['oauth_token_secret'];

        $code = $tmhOAuth->request(
                        'GET',
                        $tmhOAuth->url('1/account/verify_credentials')
        );

        if ($code == 200) {
            $this->data = json_decode($tmhOAuth->response['response']);
        } else {
            $this->outputError($tmhOAuth);
        }
    }

    public function timeline()
    {

        $tmhOAuth = new tmhOAuth(Config::get('config.twitter'));
        $dataTokens = Session::get('access_token');
        $tmhOAuth->config['user_token'] = $dataTokens['oauth_token'];
        $tmhOAuth->config['user_secret'] = $dataTokens['oauth_token_secret'];
        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/statuses/home_timeline'), array(
                    'include_entities' => '1',
                ));

        if ($code == 200) {
            $this->timeline = json_decode($tmhOAuth->response['response'], true);
        } else {
            $this->timeline = array();
            $this->outputError($tmhOAuth);
        }
    }

    public function twitter()
    {
        if (Input::hasPost('texto')) {
            $tmhOAuth = new tmhOAuth(Config::get('config.twitter'));
            $dataTokens = Session::get('access_token');
            $tmhOAuth->config['user_token'] = $dataTokens['oauth_token'];
            $tmhOAuth->config['user_secret'] = $dataTokens['oauth_token_secret'];
            $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
                        'status' => Input::post('texto'),
                    ));

            if ($code == 200) {
                //$this->timeline = json_decode($tmhOAuth->response['response'], true);
                Flash::valid('El Mensaje se Envió con exito');
            } elseif ($code == 403) {
                Flash::warning('Ya has enviado un mensaje igual...!!! Deja la Vivesa');
                $this->outputError($tmhOAuth);
            } else {
                $this->timeline = array();
                $this->outputError($tmhOAuth);
            }
        }
    }

    public function amigos()
    {
        $tmhOAuth = new tmhOAuth(Config::get('config.twitter'));
        $dataTokens = Session::get('access_token');
        $tmhOAuth->config['user_token'] = $dataTokens['oauth_token'];
        $tmhOAuth->config['user_secret'] = $dataTokens['oauth_token_secret'];
        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/friends/ids'), array(
                        //'stringify_ids' => TRUE
                ));

        if ($code == 200) {
            $this->data = json_decode($tmhOAuth->response['response'], true);
            $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/users/lookup'), array(
                            'user_id' => join(',',$this->data['ids'])
                    ));
            if ($code == 200) {
                $this->data = json_decode($tmhOAuth->response['response'], true);
            } else {
                $this->outputError($tmhOAuth);
            }
        } else {
            $this->outputError($tmhOAuth);
        }
    }

    public function cerrar_sesion()
    {
        Session::delete('access_token');
        return Router::redirect();
    }

    private function outputError($tmhOAuth)
    {
        Flash::error('Error: ' . $tmhOAuth->response['response']);
//        tmhUtilities::pr($tmhOAuth);
//        var_dump($tmhOAuth);
    }

}
