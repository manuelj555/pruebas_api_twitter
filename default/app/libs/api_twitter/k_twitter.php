<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'tmhOAuth.php';
require_once 'tmhUtilities.php';

/**
 * Description of k_twitter
 *
 * @author maguirre
 */
class KTwitter
{

    /**
     * Objeto mediante el cual nos comunicaremos con el api de twitter
     *
     * @var tmhOAuth 
     */
    public $tmhOAuth = NULL;

    /**
     * Arreglo con la info del oauth_token
     *
     * @var array 
     */
    protected $oAuthTokens = array();

    /**
     * Arreglo con la info del access_token
     *
     * @var array 
     */
    protected $accessTokens = array();

    /**
     * Información del Usuario de Twitter que está conectado
     *
     * @var array 
     */
    public $userInfo = array();

    public function __construct($config = NULL)
    {
        $config = $config ? $config : Config::get('config.twitter');
        $this->tmhOAuth = new tmhOAuth($config);
        if (Session::has('oauth')) {
            $this->oAuthTokens = Session::get('oauth');
            $this->tmhOAuth->config['user_token'] = $this->oAuthTokens['oauth_token'];
            $this->tmhOAuth->config['user_secret'] = $this->oAuthTokens['oauth_token_secret'];
        }
        if (Session::has('access_token')) {
            $this->accessTokens = Session::get('access_token');
            $this->tmhOAuth->config['user_token'] = $this->accessTokens['oauth_token'];
            $this->tmhOAuth->config['user_secret'] = $this->accessTokens['oauth_token_secret'];
        }
    }

    /**
     * 
     * 
     * @param string $callBack direccion url a la que twitter redireccionará 
     * luego de autorizar el acceso de la app en twitter.
     * @param boolean $redirigir 
     * @return string/boolean url donde se va a validar el acceso de la app a twitter.
     */
    public function getOAuthToken($oauth_callback = NULL, $redirigir = TRUE)
    {
        $oauth_callback = $oauth_callback ? $oauth_callback : tmhUtilities::php_self();
        $code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), array(
            'oauth_callback' => $oauth_callback
                )
        );
        if ($code == 200) {
            $response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
            Session::set('oauth', $this->oAuthTokens = $response);
            if ($redirigir) {
                header("Location: {$this->tmhOAuth->url('oauth/authorize', '')}?oauth_token={$response['oauth_token']}");
            } else {
                return "{$this->tmhOAuth->url('oauth/authorize', '')}?oauth_token={$response['oauth_token']}";
            }
        } else {
            $this->error();
            return FALSE;
        }
    }

    /**
     *
     * @param string $oauth_verifier
     * @return array/boolean 
     */
    public function getAccessToken($oauth_verifier)
    {
        $code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), array(
            'oauth_verifier' => $oauth_verifier
                )
        );
        if ($code == 200) {
            $response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
            Session::set('access_token', $this->accessTokens = $response);
            return $response;
        } else {
            $this->error();
            return FALSE;
        }
    }

    public function verifyCredentials()
    {

        $code = $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1/account/verify_credentials'));

        if ($code == 200) {
            return $this->userInfo = json_decode($this->tmhOAuth->response['response']);
        } else {
            $this->error();
            return FALSE;
        }
    }

    public function tweet($text)
    {
        if ($text) {
            $code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('1/statuses/update'), array(
                'status' => $text
                    )
            );

            if ($code == 200) {
                return TRUE;
            } elseif ($code == 403) {
                $this->error();
                return FALSE;
            } else {
                $this->error();
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function timeline()
    {
        $code = $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1/statuses/home_timeline'), array(
            'include_entities' => '1',
                )
        );

        if ($code == 200) {
            return json_decode($this->tmhOAuth->response['response'], true);
        } else {
            $this->error();
            return FALSE;
        }
    }

    public function destroy()
    {
        Session::delete('oauth');
        Session::delete('access_token');
    }

    public function isIdentified()
    {
        return Session::has('oauth') && Session::has('access_token');
    }

    public static function getOAuthTokensData($data)
    {
        return $this->oAuthTokens;
    }

    public static function getAccessTokensData($data)
    {
        return $this->accessTokens;
    }

    private function error()
    {
        Flash::error('Error: ' . $this->tmhOAuth->response['response']);
    }

}

