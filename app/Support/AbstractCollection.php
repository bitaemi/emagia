<?php

namespace App\Support;

/**
 * Class AbstractCollection
 * @package App\Support
 */
abstract class AbstractCollection implements \IteratorAggregate, CollectionInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param mixed           $item
     * @param null|string|int $key
     *
     * @return bool
     * @throws \Exception
     */
    public function add($item, $key = null)
    {
        if ($key !== null) {
            if ($this->has($key)) {
                throw new \Exception('The ' . $key . ' key already exists.');
            } else {
                $this->items[$key] = $item;
            }
        } else {
            $this->items[] = $item;
        }

        return true;
    }

    /**
     * @param int|string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param int|string $key
     *
     * @return bool
     * @throws \Exception
     */
    public function delete($key)
    {
        if (!$this->has($key)) {
            throw new \Exception('The ' . $key . ' key is not valid.');
        }

        unset($this->items[$key]);

        return true;
    }

    /**
     * @param int|string $key
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \Exception('The ' . $key . ' key is not valid.');
        }

        return $this->items[$key];
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
