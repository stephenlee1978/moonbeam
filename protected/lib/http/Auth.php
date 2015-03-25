<?php
include_once("Request.php");
include_once("Client.php");
/**
 * Description of Auth
 *
 * @author Administrator
 */
class Auth {

    private $_client;
    private $_request;
    public $error = array();

    function __construct() {
        $this->_client = new Client();
        $this->_request = new Request();
    }

    function __destruct() {
        unset($this->_client);
        unset($this->_request);
        unset($this->error);
    }

    public function getCode($url, $client_id, $redirect_uri) {
        try {
            $this->_request->url = $url;
            $this->_request->response_type = 'code';
            $this->_request->client_id = $client_id;
            $this->_request->redirect_uri = $redirect_uri;
            $response = $this->_client->redirect($this->_request);
        } catch (Exception $e) {
            $this->error = $this->_client->error;
        }
        return false;
    }

    public function getToken($url, $code, $client_id, $client_secret, $redirect_uri) {
        if (!isset($url{0}) ||
                !isset($code{0}) ||
                !isset($client_id{0}) ||
                !isset($client_secret{0}) ||
                !isset($redirect_uri{0})) {
            $this->error['code'] = 2010;
                $this->error['message'] = '参数设置不完整';
            return false;
        }


        $this->_request->url = $url;
        $this->_request->grant_type = 'authorization_code';
        $this->_request->code = $code;
        $this->_request->client_secret = $client_secret;
        $this->_request->client_id = $client_id;
        $this->_request->redirect_uri = $redirect_uri;
        $response = $this->sendRequest();
        if ($response === false)
            return false;


        $this->_request->clear();
        $this->_request->url = $url;
        $this->_request->grant_type = 'refresh_token';
        $this->_request->refresh_token = $response->refresh_token;
        $this->_request->client_secret = $client_secret;
        $this->_request->client_id = $client_id;
        $response = $this->sendRequest();
        if ($response === false)
            return false;

        return $response;
    }
    
    public function refreshToken($userInfo) {
        $this->_request->clear();
        $this->_request->url = $userInfo['url'];
        $this->_request->grant_type = 'refresh_token';
        $this->_request->refresh_token = $userInfo['refresh_token'];
        $this->_request->client_secret = $userInfo['secret'];
        $this->_request->client_id = $userInfo['id'];
        $response = $this->sendRequest();
        if ($response === false)
            return false;

        return $response;
    }

    private function sendRequest() {
        try {
            $response = $this->_client->post($this->_request);
        } catch (Exception $e) {
            $this->error = $this->_client->error;
            return false;
        }
        
        if(isset($response->error)){
            $this->error['message'] = $response->error_description;
            $this->error['code'] = $response->error;
            return false;
        }
        
        return $response;
    }

}