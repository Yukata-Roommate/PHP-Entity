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
     * get property
     * 
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        return is_object($this->_data) && isset($this->_data->{$name}) ? $this->_data->{$name} : null;
    }

    /**
     * set property
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, mixed $value): void
    {
        if (!is_object($this->_data)) $this->setData(new \stdClass);

        $this->_data->{$name} = $value;
    }

    /**
     * isset data
     * 
     * @param string $name
     * @return bool
     */
    public function isset(string $name): bool
    {
        return is_object($this->_data) && isset($this->_data->{$name});
    }

    /**
     * unset data
     * 
     * @param string $name
     * @return void
     */
    public function unset(string $name): void
    {
        if (!$this->isset($name)) return;

        unset($this->_data->{$name});
    }
}
