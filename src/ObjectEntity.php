<?php

namespace YukataRm\Entity;

use YukataRm\Entity\BaseEntity;

/**
 * Object Entity
 * 
 * @package YukataRm\Entity
 */
class ObjectEntity extends BaseEntity
{
    /**
     * data
     * 
     * @var object|null
     */
    protected object|null $_data = null;

    /**
     * set data
     * 
     * @param object $data
     * @return void
     */
    public function set(object $data): void
    {
        $this->_data = $data;
    }

    /**
     * flush data
     * 
     * @return void
     */
    public function flush(): void
    {
        $this->_data = null;
    }

    /*----------------------------------------*
     * Magic Method
     *----------------------------------------*/

    /**
     * get property magic method
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name): mixed
    {
        return !is_null($this->_data) && isset($this->_data->{$name}) ? $this->_data->{$name} : null;
    }

    /**
     * set property magic method
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value): void
    {
        if (is_null($this->_data)) $this->set(new \stdClass);

        $this->_data->{$name} = $value;
    }
}
