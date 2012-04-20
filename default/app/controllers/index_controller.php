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
Load::lib('api_twitter/k_twitter');

class IndexController extends AppController
{

    /**
     *
     * @var KTwitter 
     */
    private $twitter;

    public function before_filter()
    {
        $this->twitter = new KTwitter();
        if (!$this->twitter->isIdentified() &&
                !in_array($this->action_name, array(
                    'index', 'acceder', 'cerrar_sesion'
                ))) {
            Flash::error('Problemas al intentar conectar ' .
                    Html::linkAction('obtener_tokens', 'Volver a Intentar'));
            $this->action_name = 'index';
        }
    }

    public function index()
    {
        
    }

    public function acceder()
    {
        if (Session::has('access_token')) {
            return Router::toAction('hello');
        } elseif (Input::hasRequest('oauth_verifier')) {
            if ($this->twitter->getAccessToken(Input::request('oauth_verifier'))) {
                return Router::toAction('hello');
            }
        } else {
            return $this->twitter->getOAuthToken();
        }
    }

    public function hello()
    {
        if ($this->data = $this->twitter->verifyCredentials()) {
            $this->timeline = $this->twitter->timeline();
        } else {
            Flash::error('Error :-(');
            View::select(NULL);
        }
    }

    public function tweet()
    {
        if (Input::hasPost('texto')) {
            if ($this->twitter->tweet(Input::post('texto'))) {
                Flash::valid('El Tweet se envio Correctamete :-)');
            } else {
                Flash::error('No se Pudo enviar el Tweet');
            }
        }
        return Router::toAction('hello');
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
                'user_id' => join(',', $this->data['ids'])
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
        $this->twitter->destroy();
        return Router::redirect();
    }

    private function outputError($tmhOAuth)
    {
        Flash::error('Error: ' . $tmhOAuth->response['response']);
//        tmhUtilities::pr($tmhOAuth);
//        var_dump($tmhOAuth);
    }

}
