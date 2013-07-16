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

    protected $validate = [
        'email' => [
            'assert' => 'Email',
        ],
        'username' => [
            'assert' => ['length', 'regex'],
            'options' => [
                [
                    'min' => 5,
                    'max' => 12
                ],
                [
                    'pattern' => '/^[a-z0-9]+$/i',
                    'message' => "Username needs to be alphanumeric"
                ]
            ]
        ]
    ];

    public function processUsername($username)
    {
        return 'u_' . $username;
    }

    public function processId($id)
    {
        return (int) $id;
    }
}