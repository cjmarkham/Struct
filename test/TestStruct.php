<?php
/**
 * Created by JetBrains PhpStorm.
 * User: andrew
 * Date: 14/07/2013
 * Time: 00:33
 * To change this template use File | Settings | File Templates.
 */

namespace WillisHQ\Test;


class TestStruct extends \WillisHQ\Struct
{
    protected $validProperties = array('username', 'email', 'id', 'key');

    protected $filters = array(
        'email' => array(
            'filter' => 'Email',
        ),
    );

    public function filterUsername($username)
    {
        if (preg_match('/[a-zA-Z0-9]+/', $username)) {
            return $username;
        }

        throw new \WillisHQ\StructException("Username isn't alphanumeric");
    }

    public function filterId($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            return (int)$id;
        }

        throw new \WillisHQ\StructException("Id is required to be an integer");
    }
}