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

    public function filterUsername($username)
    {
        if (preg_match('/[a-z0-9]+/i', $username)) {
            return $username;
        }

        throw new \WillisHQ\StructException("Username isn't alphanumeric");
    }

    public function filterEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        throw new \WillisHQ\StructException("Email is invalid");
    }

    public function filterId($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            return (int)$id;
        }

        throw new \WillisHQ\StructException("Id is required to be an integer");
    }
}