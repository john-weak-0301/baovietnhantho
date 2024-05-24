<?php

namespace App\Model;

use ArrayAccess;
use Illuminate\Support\Arr;
use JsonSerializable;
use Webmozart\Assert\Assert;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Province implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    use Helpers\Arrayable;

    public const TYPE_CITY = 'thanh-pho';
    public const TYPE_PROVINCE = 'tinh';

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $name_with_type;

    /**
     * @var Collection|District[]
     */
    protected $districts;

    /**
     * @var Collection|self[]
     */
    protected static $caches;

    /**
     * @return Collection|static[]
     */
    public static function all(): Collection
    {
        static::loadData();

        return static::$caches;
    }

    /**
     * @return Collection|static[]
     */
    public static function get(): Collection
    {
        return static::all();
    }

    /**
     * @return Collection|static[]
     */
    public static function active()
    {
        return static::all();
    }

    public static function getByCode(string $code): self
    {
        static::loadData();

        $code = sprintf('%02s', $code);

        if (!$city = static::$caches->get($code)) {
            $city = static::$caches->firstWhere('code', $code);
        }

        if (!$city) {
            throw (new ModelNotFoundException)->setModel(self::class, $code);
        }

        return $city;
    }

    public function __construct(array $attributes = [])
    {
        foreach (array_keys(get_object_vars($this)) as $key) {
            if (array_key_exists($key, $attributes)) {
                $this->{$key} = $attributes[$key];
            }
        }

        if ($this->isCity()) {
            $this->name = 'TP. '.$this->name;
        }

        $this->districts = null;
    }

    public function getCode(): string
    {
        return sprintf('%02s', $this->code ?: 0);
    }

    public function getName(): string
    {
        return $this->name ?: '';
    }

    public function getType(): string
    {
        return $this->type ?: '';
    }

    public function getSlug(): string
    {
        return $this->slug ?: '';
    }

    public function getNameWithType(): string
    {
        return $this->name_with_type ?: '';
    }

    public function isCity(): bool
    {
        return $this->getType() === self::TYPE_CITY;
    }

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        if (!$this->districts) {
            $this->districts = District::province($this);
        }

        return $this->districts;
    }

    protected static function loadData(): void
    {
        if (!static::$caches) {
            Assert::fileExists(
                $path = base_path('vendor/madnh/hanhchinhvn/dist/tinh_tp.json')
            );

            $json = json_decode(file_get_contents($path), true);

            if (json_last_error()) {
                throw JsonEncodingException::forModel(self::class, json_last_error_msg());
            }

            $highPriority = array_reverse(['01', 79, 48]);

            $collection = (new Collection($json))
                ->mapInto(self::class)
                ->sortBy('name', SORT_LOCALE_STRING);

            foreach ($highPriority as $code) {
                $collection->prepend(
                    $collection->pull($code),
                    $code
                );
            }

            static::$caches = $collection;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return Arr::except(
            get_object_vars($this),
            'districts'
        );
    }
}
