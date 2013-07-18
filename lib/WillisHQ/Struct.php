<?php

namespace WillisHQ;

use JsonSerializable, ArrayAccess;

use Symfony\Component\Validator\Validation;
/**
 * Class Struct
 *
 *
 * @author Andrew Willis <andrew@willisilliw.com>
 * @version 0.1
 * @package WillisHQ
 */
abstract class Struct implements JsonSerializable, ArrayAccess
{
    /**
     * Valid properties for this Struct
     *
     * @var array
     */
    protected $validProperties = [];

    /**
     * Holds the property values
     *
     * @var array
     */
    protected $properties = [];

    /**
     * An key => value array of filters using Symfony Validator
     *
     * @var array
     */
    protected $validate = [];

    /**
     * Create the Struct from an array (set to null if the key isn't set in the array)
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        foreach ($this->validProperties as $key => $value) {
            $this->properties[$key] = null;
        }
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * Repopulate the Struct with new values (or null if the key isn't set in the array)
     *
     * @param array $properties
     * @return Struct
     */
    public function __invoke($properties = [])
    {
        foreach ($this->validProperties as $key => $value) {
            $this->properties[$key] = null;
        }
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }

        return $this;
    }
    /**
     * Check a property is allowed and (optionally) process and validate a value before assigning it to the
     * properties array
     *
     * @param string $property
     * @param mixed $value
     * @return mixed
     * @throws StructException
     */
    public function __set($property, $value)
    {

        if (isset($this->validate[$property]) && isset($this->validate[$property]['assert'])) {
            $validate = $this->validate[$property];
            $namespace = '\\Symfony\\Component\\Validator\\Constraints\\';

            if (is_array($validate['assert'])) {
                $constraints = [];

                foreach ($validate['assert'] as $key => $assert) {

                    $class = $namespace . ucfirst($assert);
                    $options = null;

                    if (isset($validate['options'][$key])) {
                        $options = $validate['options'][$key];
                    }

                    $constraints[] = new $class($options);
                }
            } else {

                $class =  $namespace . ucfirst($validate['assert']);
                $options = null;

                if (isset($validate['options'])) {
                    $options = $validate['options'];
                }

                $constraints = new $class($options);

            }

            $errors = Validation::createValidator()->validateValue($value, $constraints);

            if ($errors->count()) {
                throw new StructException('Trying to set an invalid value for \'' . $property . '\' - \'' . $value . '\'');
            }
        }


        // if property name is valid for this struct
        if (in_array($property, $this->validProperties)) {
            $process = 'process' . ucfirst($property);
            // if there is a process method for the property
            if (method_exists($this, $process)) {
                $value = $this->$process($value);
            }

            $this->properties[$property] = $value;
            // return the value assigned (which may have been processed)
            return $this->properties[$property];
        }
        throw new StructException('Trying to set an invalid property \'' . $property . '\' to the struct \'' . get_called_class(
        ) . '\'');
    }

    /**
     * Check the property is allowed on the Struct, then return it
     *
     * @param string $property The property to get the value of
     * @return mixed
     * @throws StructException
     */
    public function __get($property)
    {
        // if the property name is valid for this struct
        if (in_array($property, $this->validProperties)) {
            return $this->properties[$property];
        }
        throw new StructException('Trying to access an invalid property \'' . $property . '\' in the Struct \'' . get_called_class(
        ) . '\'');
    }

    /**
     * Unset a property
     *
     * @param $property
     * @throws StructException
     */
    public function __unset($property)
    {
        if (in_array($property, $this->validProperties)) {
            //we just want to set it to null as the parameter still needs to exist
            $this->properties[$property] = null;
        } else {
            throw new StructException('Trying to unset an invalid property \'' . $property . '\' in the struct \'' . get_called_class(
            ) . '\'');
        }
    }

    /**
     * Check if a property has been set yet (if it is not null)
     *
     * @param $property
     * @return bool
     * @throws StructException
     */
    public function __isset($property)
    {
        if (in_array($property, $this->validProperties)) {
            return !is_null($this->properties[$property]);
        }
        throw new StructException('checking if an invalid property \'' . $property . '\' is set in the struct \'' . get_called_class(
        ) . '\'');
    }

    /**
     * Return the correct data when json_encode() is called on the class
     *
     * @return array The values set on the Struct
     */
    public function jsonSerialize()
    {
        return $this->properties;
    }

    /**
     * @see __get()
     */
    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    /**
     * @see __set()
     */
    public function offsetSet($property, $value)
    {
        return $this->__set($property, $value);
    }

    /**
     * @see __isset()
     */
    public function offsetExists($property)
    {
        return $this->__isset($property);
    }

    /**
     * @see __unset()
     */
    public function offsetUnset($property)
    {
        $this->__unset($property);
    }

    /**
     * If the object is treated as a string, return json_encoded data
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}

class StructException extends \Exception
{
}