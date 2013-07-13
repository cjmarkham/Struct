<?php

namespace WillisHQ\Data;

use JsonSerializable, ArrayAccess;

/**
 * Class Struct
 *
 *
 * @author Andrew Willis <andrew@willisilliw.com>
 * @version 0.1
 * @package WillisHQ\Struct
 */
abstract class Struct implements JsonSerializable, ArrayAccess
{

    /**
     * Valid properties for this Struct
     *
     * @var array
     */
    protected $validProperties = array();

    /**
     * Holds the property values
     *
     * @var array
     */
    protected $properties = array();

    /**
     * Populate the properties array with null values
     */
    public function __construct()
    {
        foreach ($this->validProperties as $property) {
            $this->properties[$property] = null;
        }
    }

    /**
     * Check a property is allowed and (optionally) filter a value before assigning it to the
     * properties array
     *
     * @param string $property
     * @param mixed $value
     * @return mixed
     * @throws StructException
     */
    public function __set($property, $value)
    {
        // if property name is valid for this struct
        if (in_array($property, $this->validProperties)) {
            $filter = 'filter' . ucfirst($property);
            // if there is a filter method for the property
            if (method_exists($this, $filter)) {
                $this->properties[$property] = $this->$filter($value);
            } else {
                $this->properties[$property] = $value;
            }
            // return the value assigned (which may have been filtered)
            return $this->properties[$property];
        }
        throw new StructException('Trying to set an invalid property \'' . $property . '\' to the struct \'' . get_called_class() . '\'');
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
        throw new StructException('Trying to access an invalid property \'' . $property . '\' in the Struct \'' . get_called_class() . '\'');
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
            throw new StructException('Trying to unset an invalid property \'' . $property . '\' in the struct \'' . get_called_class() . '\'');
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
        throw new StructException('checking if an invalid property \'' . $property . '\' is set in the struct \'' . get_called_class() . '\'');
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
        return $this->__unset($property);
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