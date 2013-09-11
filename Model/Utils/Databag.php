<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Model\Utils;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class Databag implements \Countable, \IteratorAggregate, \ArrayAccess
{

    protected $bag;

    /**
     * Constructor.
     *
     * @param array $bag An array of data
     */
    public function __construct($bag = array())
    {
        $this->bag = $bag;
    }

    /**
     * Set a data in this bag
     * 
     * @param mixed $offset The offset to set the data to.
     * @param mixed $value The value to be associated at this offset
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->bag[] = $value;
        } else
        {
            $this->bag[$offset] = $value;
        }
    }

    /**
     * Check the existence of some data at this offset
     * 
     * @param mixed $offset The offset
     * @return boolean True is there is a value at this offset, false otherwise
     */
    public function offsetExists($offset)
    {
        return isset($this->bag[$offset]);
    }

    /**
     * Unset the value at the given offset.
     * 
     * @param mixed $offset The offset
     */
    public function offsetUnset($offset)
    {
        unset($this->bag[$offset]);
    }

    /**
     * Get the value at the specified offset if exists.
     * 
     * @param mixed $offset The offset
     * @return mixed The data associated to this offset if it exists, null otherwise
     */
    public function offsetGet($offset)
    {
        return isset($this->bag[$offset]) ? $this->bag[$offset] : null;
    }

    /**
     * Returns the number of data in this bag.
     *
     * @return int The number of data in this bag
     */
    public function count()
    {
        return count($this->bag);
    }

    /**
     * Returns an iterator for databags.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->bag);
    }

}