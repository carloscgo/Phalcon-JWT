<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

//use Phalcon\Logger;
//use Phalcon\Logger\Adapter\File as FileAdapter;

class AuthController extends ControllerBase
{

    public function loginAction()
    {
        $this->view->disable();

        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $logged = Usuarios::findFirst(
                    array(
                        "conditions"=> "email = :email: AND password = :password:",
                        "bind"      => array(
                                            "email" => $this->request->getPost("username"),
                                            "password" => md5($this->request->getPost("password"))
                                        )
                    )
                );

                if ($logged != false) {
                    $user = array(
                        "id_usuario"=> $logged->id_usuario,
                        "username"  => $logged->email,
                        "password"  => $logged->password
                    );

                    $getConfig = $this->getConfigToken();

                    $this->response->setJsonContent(array(
                        "code"  => 0,
                        "res"   => "success",
                        "token" => JWT::encode($getConfig + $user, $this->getConfigApp()->key)
                    ));

                    //devolvemos un 200, todo ha ido bien
                    $this->response->setStatusCode(200, "OK");
                } else {
                    $this->response->setJsonContent(array(
                        "res" => "not found"
                    ));

                    $this->response->setStatusCode(404, "NOT FOUND" . $this->request->getPost("username"));
                }

                $this->response->send();
            } else {
                $this->response->setStatusCode(405, "Method Not Allowed");
                $this->response->send();
            }
        } else {
            $this->response->setStatusCode(405, "Method Not Allowed");
            $this->response->send();
        }
    }

    public function renewtokenAction()
    {
        $this->view->disable();

        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $data = $this->request->getRawBody();
                $data = json_decode($data);
                $token= $data->{"refresh_token"};

                //comprobamos si existe el token y no está vacío
                if (empty($token) && $data->{"grant_type"} == "refresh_token") {
                    //devolvemos un 403, Forbidden
                    $this->response->setStatusCode(403, "Forbidden");
                    $this->response->send();
                    die();
                }

                try {
                    JWT::$leeway = 60; // 60 seconds
                    $user = JWT::decode($token, $this->getConfigApp()->key, array('HS256'));
                } catch (\Firebase\JWT\ExpiredException $e) {
                    $this->response->setStatusCode(405, $e->getMessage());
                    $this->response->send();
                    die();
                }

                //comprobamos si existe el usuario
                $logged = Usuarios::findFirst(
                    array(
                        "conditions"=> "email = :email: AND password = :password:",
                        "bind"      => array(
                                            "email" => $user->username,
                                            "password" => $user->password
                                        )
                    )
                );

                //si no existe
                if ($logged == false) {
                    //no es un token correcto
                    //devolvemos un 401, Unauthorized
                    $this->response->setStatusCode(401, "Unauthorized");
                    $this->response->send();
                    die();
                }

                $user = array(
                    "id_usuario"=> $logged->id_usuario,
                    "username"  => $logged->email,
                    "password"  => $logged->password
                );

                $getConfig = $this->getConfigToken();

                $this->response->setJsonContent(array(
                    "code"  => 0,
                    "res"   => "success",
                    "token" => JWT::encode($getConfig + $user, $this->getConfigApp()->key)
                ));

                $this->response->setStatusCode(200, "OK");
                $this->response->send();
            } else {
                $this->response->setStatusCode(405, "Method Not Allowed");
                $this->response->send();
            }
        } else {
            $this->response->setStatusCode(405, "Method Not Allowed");
            $this->response->send();
        }
    }
}
