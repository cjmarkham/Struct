# Struct

[![Build Status](https://travis-ci.org/willishq/Struct.png?branch=master)](https://travis-ci.org/willishq/Struct)

An easy way to validate the data which is being sent to your functions and methods

## Installation

via Composer

    {
        "require": {
            "willishq/struct": "~1.0"
        }
    }

## Requirements

* PHP 5.4+
* [Composer](http://getcomposer.org/)

## Team

Andrew Willis - [twitter](http://twitter.com/willishq) - [website](http://willisilliw.com) - [linkedin](http://www.linkedin.com/in/willisilliw)

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

You can populate your struct via an array on construct:

    $test = new TestStruct(
        array(
            'username' => 'Andrew',
            'email' => 'andrew@willisilliw.com'
        )
    );

    echo $test->username; // 'Andrew'
    echo $test->email; // 'andrew@willisilliw.com'

And you can also re-populate a struct by invoking it (like a function)

    $test = new TestStruct(
        array(
            'username' => 'Andrew',
            'email' => 'andrew@willisilliw.com'
        )
    );

    echo $test->username; // 'Andrew'
    echo $test->email; // 'andrew@willisilliw.com'

    $test(
        array(
            'username' => 'Test',
            'email' => 'test@example.com'
        )
    )

    echo $test->username; // 'Test'
    echo $test->email; // 'test@example.com'

While using both of these methods, any missing values will be set to null

## Exceptions

When setting/getting/unsetting and checking isset on invalid properties, Struct will throw a StructException. This
applies to all methods of setting and getting data. It is also advisable to throw StructExceptions in your filters to make sure you can properly handle such events.

## Contributors

Scott Robertson - [twitter](http://twitter.com/scottymeuk) - [website](http://scottrobertson.me/)

[All contributors](https://github.com/willishq/Struct/graphs/contributors)

#### If you wish to contribute, follow the following procedure:

* Fork the repository
* Make your changes to the develop branch (or preferably a feature branch)
* Add tests for your changes
* Run ALL tests
* Add your name in the Contributors section of README.md (optional)
* Push your changes to your fork
* Issue a pull request

If the build fails on your pull request I will not merge it but I will tell you about it.
