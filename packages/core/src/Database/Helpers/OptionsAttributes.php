<?php

namespace Core\Database\Helpers;

use Countable;
use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

class OptionsAttributes implements ArrayAccess, Countable, Arrayable
{
    /**
     * The underlying model resource instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * The attribute name of the "options" column.
     *
     * @var string
     */
    protected $attributeName;

    /**
     * Store all attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor.
     *
     * @param  Model  $model
     * @param  string  $attributeName
     */
    public function __construct(Model $model, string $attributeName)
    {
        $this->model = $model;

        $this->attributeName = $attributeName;

        $this->attributes = $this->getRawAttributes();
    }

    /**
     * Create for a model.
     *
     * @param  Model  $model
     * @param  string  $attributeName
     * @return OptionsAttributes
     */
    public static function createForModel(Model $model, string $attributeName)
    {
        return new static($model, $attributeName);
    }

    /**
     * Retrive a attribute by key.
     *
     * @param  string  $name
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return data_get($this->attributes, $name, $default);
    }

    /**
     * Set a attribute value.
     *
     * @param  string|array|iterable  $attribute
     * @param  mixed|null  $value
     */
    public function set($attribute, $value = null)
    {
        if (is_iterable($attribute)) {
            foreach ($attribute as $attr => $val) {
                $this->set($attr, $val);
            }

            return;
        }

        data_set($this->attributes, $attribute, $value);

        $this->model->{$this->attributeName} = $this->attributes;
    }

    /**
     * Check if an item or items exist in attributes.
     *
     * @param  string  $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return Arr::has($this->attributes, $name);
    }

    /**
     * Forget a attribute by key name.
     *
     * @param  string  $name
     * @return $this
     */
    public function forget(string $name)
    {
        $this->model->{$this->attributeName} = Arr::except($this->attributes, $name);

        return $this;
    }

    /**
     * Get all attributes.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getRawAttributes();
    }

    /**
     * Returns the attributes count.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Returns the raw attributes.
     *
     * @return array
     */
    protected function getRawAttributes(): array
    {
        $data = $this->model->getAttributes()[$this->attributeName] ?? '{}';

        return json_decode($data, true) ?? [];
    }

    /**
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * @param  string  $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param  string  $offset
     */
    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }
}
