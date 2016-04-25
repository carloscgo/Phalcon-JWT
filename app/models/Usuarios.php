<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Usuarios extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_usuario;

    /**
     *
     * @var string
     */
    public $id_device;

    /**
     *
     * @var integer
     */
    public $id_smtp;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $tipo;

    /**
     *
     * @var string
     */
    public $fecha_crea;

    /**
     *
     * @var string
     */
    public $fecha_mod;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id_usuario', 'Cola', 'id_usuario', array('alias' => 'Cola'));
        $this->hasMany('id_usuario', 'Contactos', 'id_usuario', array('alias' => 'Contactos'));
        $this->hasMany('id_usuario', 'Entrantes', 'id_usuario', array('alias' => 'Entrantes'));
        $this->hasMany('id_usuario', 'Estadisticas', 'id_usuario', array('alias' => 'Estadisticas'));
        $this->hasMany('id_usuario', 'Grupos', 'id_usuario', array('alias' => 'Grupos'));
        $this->hasMany('id_usuario', 'Salientes', 'id_usuario', array('alias' => 'Salientes'));
        $this->hasMany('id_usuario', 'UserTemplates', 'id_usuario', array('alias' => 'UserTemplates'));
        $this->belongsTo('id_device', 'Dispositivos', 'id_device', array('alias' => 'Dispositivos'));
        $this->belongsTo('id_smtp', 'Smtp', 'id_smtp', array('alias' => 'Smtp'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuarios';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
