# Struct

An easy way to validate the data which is being sent to your functions and methods

## Installation

via Composer

    {
        "require": {
            "willishq/struct": ">=0.1"
        }
    }

## Requirements

* PHP 5.4+
* [Composer](http://getcomposer.org/)

## Usage

Extend the class into your own Structs

    class TestStruct extends WillisHQ\Struct {
        //
    }

Use the protected `validProperties` variable toset which parameters you want to be available to use:

    protected $validProperties = array('username', 'email')

Add filters to your struct to automagically filter data when it is assigned:

    public function filterEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        throw new StructException('Invalid email provided');
    }

Create an instance of your struct:

    $test = new TestStruct();
    $test->username = 'Andrew';
    $test->email = 'andrew@willisilliw.com';

Inside of your function, you can access the data as an array or an object:

    echo $test['username']; // 'Andrew'
    echo $test->email; // 'andrew@willisilliw.com'

Treating your Struct object as a string returns a JSON encoded object:

    echo $test; // '{"username" : "Andrew", "email" : "andrew@willisilliw.com"}'

Calling json_encode on the struct does the same:

    echo json_encode($test); // '{"username" : "Andrew", "email" : "andrew@willisilliw.com"}'

## Build Status

[![Build Status](https://travis-ci.org/willishq/Struct.png?branch=master)](https://travis-ci.org/willishq/Struct)