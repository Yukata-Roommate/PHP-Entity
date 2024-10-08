<?php

namespace YukataRm\Entity;

/**
 * Base Entity
 * 
 * @package YukataRm\Entity
 */
abstract class BaseEntity
{
    /**
     * data
     * 
     * @var array|object|null
     */
    protected array|object|null $_data = null;

    /**
     * set data
     * 
     * @param array|object $data
     * @return void
     */
    protected function setData(array|object $data): void
    {
        $this->_data = $data;
    }

    /**
     * get property
     * 
     * @param string $name
     * @return mixed
     */
    abstract public function get(string $name): mixed;

    /**
     * set property
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    abstract public function set(string $name, mixed $value): void;

    /**
     * isset data
     * 
     * @param string $name
     * @return bool
     */
    abstract public function isset(string $name): bool;

    /**
     * unset data
     * 
     * @param string $name
     * @return void
     */
    abstract public function unset(string $name): void;

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
     * Method
     *----------------------------------------*/

    /**
     * get all properties
     * 
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $reflector = new \ReflectionClass($this);
        $reflectorClassName = $reflector->getName();

        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if ($property->class !== $reflectorClassName) continue;
            if ($property->isInitialized($this) === false) continue;
            if ($property->isStatic()) continue;

            $name = $property->getName();

            $all[$name] = $this->{$name};
        }

        return $all;
    }

    /**
     * to array
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * get only properties with keys
     * 
     * @param string|array<string> ...$keys
     * @return array<string, mixed>
     */
    public function only(string|array ...$keys): array
    {
        $keys = $this->mergeKeys(...$keys);

        $all = $this->all();

        return array_filter($all, fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }

    /**
     * get except properties with keys
     * 
     * @param string|array<string> ...$keys
     * @return array<string, mixed>
     */
    public function except(string|array ...$keys): array
    {
        $keys = $this->mergeKeys(...$keys);

        $all = $this->all();

        return array_filter($all, fn($key) => !in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }

    /*----------------------------------------*
     * Property
     *----------------------------------------*/

    /**
     * get property as nullable string
     * 
     * @param string $name
     * @return string|null
     */
    public function nullableString(string $name): string|null
    {
        $property = $this->get($name);

        return is_string($property) ? strval($property) : null;
    }

    /**
     * get property as string
     * 
     * @param string $name
     * @return string
     */
    public function string(string $name): string
    {
        $property = $this->nullableString($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable int
     * 
     * @param string $name
     * @return int|null
     */
    public function nullableInt(string $name): int|null
    {
        $property = $this->get($name);

        return is_numeric($property) ? intval($property) : null;
    }

    /**
     * get property as int
     * 
     * @param string $name
     * @return int
     */
    public function int(string $name): int
    {
        $property = $this->nullableInt($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable float
     * 
     * @param string $name
     * @return float|null
     */
    public function nullableFloat(string $name): float|null
    {
        $property = $this->get($name);

        return is_numeric($property) ? floatval($property) : null;
    }

    /**
     * get property as float
     * 
     * @param string $name
     * @return float
     */
    public function float(string $name): float
    {
        $property = $this->nullableFloat($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable bool
     * 
     * @param string $name
     * @return bool|null
     */
    public function nullableBool(string $name): bool|null
    {
        $property = $this->get($name);

        if (intval($property) === 1 || intval($property) === 0) $property = boolval($property);

        return is_bool($property) ? boolval($property) : null;
    }

    /**
     * get property as bool
     * 
     * @param string $name
     * @return bool
     */
    public function bool(string $name): bool
    {
        $property = $this->nullableBool($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable array
     * 
     * @param string $name
     * @return array|null
     */
    public function nullableArray(string $name): array|null
    {
        $property = $this->get($name);

        return is_array($property) ? $property : null;
    }

    /**
     * get property as array
     * 
     * @param string $name
     * @return array
     */
    public function array(string $name): array
    {
        $property = $this->nullableArray($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable object
     * 
     * @param string $name
     * @return object|null
     */
    public function nullableObject(string $name): object|null
    {
        $property = $this->get($name);

        if (is_string($property)) $property = json_decode($property);

        return is_object($property) ? $property : null;
    }

    /**
     * get property as object
     * 
     * @param string $name
     * @return object
     */
    public function object(string $name): object
    {
        $property = $this->nullableObject($name);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /**
     * get property as nullable enum
     * 
     * @param string $name
     * @param string $enumClass
     * @return \UnitEnum|null
     */
    public function nullableEnum(string $name, string $enumClass): \UnitEnum|null
    {
        $property = $this->get($name);

        if (is_null($property)) return null;

        return enum_exists($enumClass) ? $enumClass::tryFrom($property) : null;
    }

    /**
     * get property as enum
     * 
     * @param string $name
     * @param string $enumClass
     * @return \UnitEnum
     */
    public function enum(string $name, string $enumClass): \UnitEnum
    {
        $property = $this->nullableEnum($name, $enumClass);

        if (is_null($property)) $this->throwRequiredException($name);

        return $property;
    }

    /*----------------------------------------*
     * Protected
     *----------------------------------------*/

    /**
     * throw required exception
     * 
     * @param string $key
     * @return void
     */
    protected function throwRequiredException(string $key): void
    {
        throw new \RuntimeException("{$key} is required.");
    }

    /**
     * merge keys
     * 
     * @param string|array<string> ...$args
     * @return array<string>
     */
    protected function mergeKeys(string|array ...$args): array
    {
        $keys = [];

        foreach ($args as $key) {
            if (is_array($key)) {
                $keys = array_merge($keys, $key);
            } else {
                $keys[] = $key;
            }
        }

        return $keys;
    }
}
