<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Contactos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $id_contacto;

    /**
     *
     * @var integer
     */
    public $id_usuario;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $numero;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $status;

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
        $this->hasMany('id_contacto', 'Cola', 'id_contacto', array('alias' => 'Cola'));
        $this->hasMany('id_contacto', 'Entrantes', 'id_contacto', array('alias' => 'Entrantes'));
        $this->hasMany('id_contacto', 'GruposContactos', 'id_contacto', array('alias' => 'GruposContactos'));
        $this->hasMany('id_contacto', 'Salientes', 'id_contacto', array('alias' => 'Salientes'));
        $this->belongsTo('id_usuario', 'Usuarios', 'id_usuario', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contactos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contactos[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contactos
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
