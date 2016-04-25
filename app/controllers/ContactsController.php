<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

//use Phalcon\Logger;
//use Phalcon\Logger\Adapter\File as FileAdapter;

class ContactsController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->disable();

        if ($this->request->isAjax()) {

            //obtenemos los headers
            $headers = $this->request->getHeaders();

            //comprobamos si existe el header Authorization y no está vacío
            if (!isset($headers["Authorization"]) || empty($headers["Authorization"])) {
                //devolvemos un 403, Forbidden
                $this->response->setStatusCode(403, "Forbidden");
                $this->response->send();
                die();
            }

            $token = explode(" ", $headers["Authorization"]);
            $token = trim($token[1], '"');

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

            //obtenemos los registros
            $contactos = Contactos::find(
                array(
                    "conditions"=> "id_usuario = :id:",
                    "bind"      => array("id" => $user->id_usuario),
                    "order"     => "nombre ASC",
                    "limit"     => 10
                )
            );

            //comprobamos si hay filas
            if ($contactos->count() > 0) {
                $data = array();

                foreach ($contactos as $contacto) {
                    $data[] = array(
                        "id_contacto"   => $contacto->id_contacto,
                        "nombre"        => $contacto->nombre,
                        "numero"        => $contacto->numero,
                        "email"         => $contacto->email,
                        "status"        => $contacto->status,
                        "fecha_crea"    => $contacto->fecha_crea,
                        "fecha_mod"     => $contacto->fecha_mod
                    );
                }

                $user = array(
                    "id_usuario"=> $user->id_usuario,
                    "username"  => $user->username,
                    "password"  => $user->password
                );

                $getConfig = $this->getConfigToken();

                //devolvemos los registros y el token
                $this->response->setJsonContent(array(
                    "res"       => "success",
                    "code"      => 0,
                    "contactos" => $data,
                    "token"     => JWT::encode($getConfig + $user, $this->getConfigApp()->key)
                ));

                //devolvemos un 200, todo ha ido bien
                $this->response->setStatusCode(200, "OK");
            } else {
                $this->response->setStatusCode(204, "No Content");
            }

            $this->response->send();
        } else {
            $this->response->setStatusCode(405, "Method Not Allowed");
            $this->response->send();
        }
    }
}
