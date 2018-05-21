<?php

namespace Kily\API\UOn;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as Guzzle;

class Client implements \ArrayAccess
{

    protected $id = null;
    protected $requested = null;
    protected $response = null;
    protected $client = null;
    protected $request_options = [];

    protected $http_message;
    protected $http_code;
    protected $error_message;
    protected $error_code;

    protected $_metadata = [];

    protected $is_called = false;

    public function __construct($api,$options=[]) {
        $this->api = $api;
        $this->client = new Guzzle(array_replace_recursive([
            'base_uri'=>'https://api.u-on.ru/',
            'timeout'=>'300',
            'headers'=>[
                'Accept'=>'application/json',
            ],
        ],$options));
    }

    public function id($id) {
        $this->id = $id;
        return $this;
    }

    public function _request($method,$options=[]) {
        $resp = null;

        $this->http_code = null;
        $this->http_message = null;
        $this->error_code = null;
        $this->error_message = null;

        $this->request_ok = false;
        $options = array_replace_recursive($this->request_options,$options!==null?$options:[]);
        $this->request_options = [];

        array_unshift($this->requested,$this->api);
        array_unshift($this->requested,'/');
        $request_str = implode('',$this->requested);

        try {
            $resp = $this->client->request($method,$request_str,$options);
            $this->request_ok = true;
        } catch(TransferException $e) {
            if($e instanceof TransferException) {
                if($e->hasResponse() && ($resp = $e->getResponse()) ) {
                    $this->http_code = $resp->getStatusCode();
                    $this->http_message = $resp->getReasonPhrase();
                } else {
                    $this->http_code = $e->getCode();
                    $this->http_message = $e->getMessage();
                }
            } else {
                $this->http_code = $e->getCode();
                $this->http_message = $e->getMessage();
                return null;
            }
        } finally {
            $this->http_code = $resp->getStatusCode();
            $this->http_message = $resp->getReasonPhrase();
        }

        return $this->response = new Response($this,$resp);
    }

    public function __call($name,$arguments=[]) {
        $this->is_called = true;
        $this->requested[] = "/";
        $this->requested[] = $name;
        return $this;
    }

    public function offsetSet($offset, $value) {
		throw new Exception('You\'re trying to write protected object');
    }

    public function offsetExists($offset) {
        return $this->response && isset($this->response->toArray()[$offset]);
    }

    public function offsetUnset($offset) {
    }

    public function offsetGet($offset) {
        return $this->response && isset($this->response->toArray()[$offset]) ? $this->response->toArray()[$offset] : null;
    }

    // Termination methods

    public function byId($id) {
        $this->requested[] = "/";
        $this->requested[] = $id.".json";
        return $this->_request('GET',[]);
    }

    public function create($params=[]) {
        $options = [];
        $this->requested[] = "/";
        $this->requested[] = "create.json";
        if($params) {
            $options['json'] = $params;
        }
        return $this->_request('POST',$options);
    }

    public function get() {
        $options = [];
        $this->requested[] = ".json";
        return $this->_request('GET',[]);
    }

    //

    public function getHttpErrorMessage() {
        return $this->http_message;
    }

    public function getHttpErrorCode() {
        return $this->http_code;
    }

    public function getErrorMessage() {
        return $this->error_message;
    }

    public function getErrorCode() {
        return $this->error_code;
    }

    public function isOk() {
        return $this->http_code == 200;
    }
}
