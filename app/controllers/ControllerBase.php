<?php

use Phalcon\Mvc\Controller;
use \Phalcon\Config;

class ControllerBase extends Controller
{

    public function getConfigToken()
    {
        $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + 1;             //Adding 1 seconds
        $expire     = $notBefore + 10;            // Adding 10 seconds
        $serverName = $_SERVER['SERVER_NAME'];

        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire            // Expire
        ];

        return $data;
    }

    public function getConfigApp()
    {
        $config = include APP_PATH . "/app/config/config.php";

        return $config;
    }
}
