<?php

namespace App\Models\Game;

use ArrayAccess;
use Iterator;


class GameConfig implements ArrayAccess, Iterator
{
    /**
     * Stores the configuration data
     *
     * @var array|null
     */
    protected $initConfig = null;

    /**
     * Caches the configuration data
     *
     * @var array
     */
    protected $cache = array();

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $initConfig
     */
  
    public function __construct(array $initConfig)
    {
        $this->initConfig = array_merge($this->getConfigDefaults(), $initConfig);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function getConfigDefaults()
    {
        return array();
    }
    
    /**
     * Gets a configuration setting using a simple or nested key.
     * Nested keys are similar to JSON paths that use the dot
     * dot notation.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * Function for setting configuration values, using
     * either simple or nested keys.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $segs = explode('>>', $key);
        $root = &$this->initConfig;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segs)) {
            if ($cacheKey != '') {
                $cacheKey .= '>>';
            }
            $cacheKey .= $part;
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = array();
            }
            $root = &$root[$part];

            //Unset all old nested cache
            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }

            //Unset all old nested cache in case of array
            if (count($segs) == 0) {
                foreach ($this->cache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->cache[$cacheLocalKey]);
                    }
                }
            }
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    /**
     * Function for checking if configuration values exist, using
     * either simple or nested keys.
     *
     * @param  string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        // Check if already cached
        if (isset($this->cache[$key])) {
            return true;
        }

        $segments = explode('>>', $key);
        $root = $this->initConfig;

        // nested case
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $root)) {
                $root = $root[$segment];
                continue;
            } else {
                return false;
            }
        }

        // Set cache for the given key
        $this->cache[$key] = $root;

        return true;
    }

    /**
     * Get all of the configuration items
     *
     * @return array
     */
    public function all()
    {
        return $this->initConfig;
    }

    /**
     * ArrayAccess Methods
     */

    /**
     * Gets a value using the offset as a key
     *
     * @param  string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Checks if a key exists
     *
     * @param  string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Sets a value using the offset as a key
     *
     * @param  string $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value
     *
     * @param  string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }

    /**
     * Iterator Methods
     */

    /**
     * Returns the data array element referenced by its internal cursor
     *
     * @return mixed The element referenced by the data array's internal cursor.
     *     If the array is empty or there is no element at the cursor, the
     *     function returns false. If the array is undefined, the function
     *     returns null
     */
    public function current()
    {
        return (is_array($this->initConfig) ? current($this->initConfig) : null);
    }

    /**
     * Returns the data array index referenced by its internal cursor
     *
     * @return mixed The index referenced by the data array's internal cursor.
     *     If the array is empty or undefined or there is no element at the
     *     cursor, the function returns null
     */
    public function key()
    {
        return (is_array($this->initConfig) ? key($this->initConfig) : null);
    }

    /**
     * Moves the data array's internal cursor forward one element
     *
     * @return mixed The element referenced by the data array's internal cursor
     *     after the move is completed. If there are no more elements in the
     *     array after the move, the function returns false. If the data array
     *     is undefined, the function returns null
     */
    public function next()
    {
        return (is_array($this->initConfig) ? next($this->initConfig) : null);
    }

    /**
     * Moves the data array's internal cursor to the first element
     *
     * @return mixed The element referenced by the data array's internal cursor
     *     after the move is completed. If the data array is empty, the function
     *     returns false. If the data array is undefined, the function returns
     *     null
     */
    public function rewind()
    {
        return (is_array($this->initConfig) ? reset($this->initConfig) : null);
    }

    /**
     * Tests whether the iterator's current index is valid
     *
     * @return bool True if the current index is valid; false otherwise
     */
    public function valid()
    {
        return (is_array($this->initConfig) ? key($this->initConfig) !== null : false);
    }
}
