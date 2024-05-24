<?php

namespace App\Model;

use ArrayAccess;
use JsonSerializable;
use Webmozart\Assert\Assert;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class District implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    use Helpers\Arrayable;

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
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $path_with_type;

    /**
     * @var string
     */
    public $parent_code;

    /**
     * @var Collection|Ward[]
     */
    protected $wards;

    /**
     * @var array
     */
    protected static $caches = [];

    /**
     * @var array
     */
    protected static $mapWithProvince = [];

    public function __construct(array $attributes = [])
    {
        foreach (array_keys(get_object_vars($this)) as $key) {
            if (array_key_exists($key, $attributes)) {
                $this->{$key} = $attributes[$key];
            }
        }

        $this->wards = null;
    }

    public function getCode(): string
    {
        return sprintf('%03s', $this->code ?: '0');
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

    public function getPath(): string
    {
        return $this->path ?: '';
    }

    public function getPathWithType(): string
    {
        return $this->path_with_type ?: '';
    }

    public function getParentCode(): string
    {
        return $this->parent_code ?: '';
    }

    public function getProvince(): Province
    {
        return Province::getByCode($this->getParentCode());
    }

    /**
     * @return Collection|Ward[]
     */
    public function getWards(): Collection
    {
        if (!$this->wards) {
            $this->wards = Ward::district($this);
        }

        return $this->wards;
    }

    /**
     * @param  Province|string|int  $province
     * @return Collection|static[]
     */
    public static function province($province): Collection
    {
        if (!$province instanceof Province) {
            $province = Province::getByCode(static::normalizeProvinceCode($province));
        }

        if (array_key_exists($province->getCode(), static::$caches)) {
            return static::$caches[$province->getCode()];
        }

        $jsonPath = base_path(sprintf('vendor/madnh/hanhchinhvn/dist/quan-huyen/%s.json', $province->getCode()));
        Assert::fileExists($jsonPath);

        $json = json_decode(file_get_contents($jsonPath), true);

        if (json_last_error()) {
            throw JsonEncodingException::forModel(self::class, json_last_error_msg());
        }

        $districts = (new Collection($json))->mapInto(self::class);

        // Indexes the Districts with their city code.
        $districts = $districts->each(function (self $district) use ($province) {
            static::$mapWithProvince[$district->getCode()] = $province->getCode();
        })->sortBy('code');

        return static::$caches[$province->getCode()] = $districts
            ->filter(function (District $district) {
                return $district->type === 'quan';
            })->merge(
                $districts->filter(function (District $district) {
                    return $district->type !== 'quan';
                })
            );
    }

    /**
     * @param  string  $code
     * @param  Province|mixed  $province
     * @return self
     */
    public static function getByCode(string $code, $province = null): self
    {
        $code = sprintf('%03s', $code);

        $province = $province ?: static::locateProvince($code);

        $districts = static::province($province);

        if (!$district = $districts->get($code)) {
            $district = $districts->firstWhere('code', $code);
        }

        if (!$district) {
            throw (new ModelNotFoundException)->setModel(self::class, $code);
        }

        return $district;
    }

    /**
     * @param  Province|string|int  $province
     * @return string
     */
    protected static function normalizeProvinceCode($province): string
    {
        if ($province instanceof Province) {
            return $province->getCode();
        }

        if (is_int($province)) {
            return sprintf('%02s', $province);
        }

        return (string) $province;
    }

    protected static function locateProvince(string $district)
    {
        if (array_key_exists($district, static::$mapWithProvince)) {
            return static::$mapWithProvince[$district];
        }

        foreach (Province::all() as $_city) {
            /* @var Collection $districts */
            $districts = $_city->getDistricts();

            if ($districts->firstWhere('code', $district)) {
                return $_city->getCode();
            }
        }

        throw (new ModelNotFoundException)->setModel(self::class, $district);
    }
}
