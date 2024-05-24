<?php

namespace App\Model;

use ArrayAccess;
use JsonSerializable;
use Webmozart\Assert\Assert;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Ward implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
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
     * @var array
     */
    protected static $caches = [];

    public function __construct(array $attributes = [])
    {
        foreach (array_keys(get_object_vars($this)) as $key) {
            if (array_key_exists($key, $attributes)) {
                $this->{$key} = $attributes[$key];
            }
        }
    }

    public function getCode(): string
    {
        return sprintf('%05s', $this->code ?: '0');
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

    public function getDistrict(): District
    {
        return District::getByCode($this->getParentCode());
    }

    /**
     * @param  District|string|int  $district
     * @param  null  $province
     * @return Collection|static[]
     */
    public static function district($district, $province = null): Collection
    {
        if (!$district instanceof District) {
            $district = District::getByCode(static::normalizeDistrictCode($district), $province);
        }

        if (array_key_exists($district->getCode(), static::$caches)) {
            return static::$caches[$district->getCode()];
        }

        $jsonPath = base_path(sprintf('vendor/madnh/hanhchinhvn/dist/xa-phuong/%s.json', $district->getCode()));
        Assert::fileExists($jsonPath);

        $json = json_decode(file_get_contents($jsonPath), true);

        if (json_last_error()) {
            throw JsonEncodingException::forModel(self::class, json_last_error_msg());
        }

        return static::$caches[$district->getCode()] = (new Collection($json))
            ->mapInto(self::class);
    }

    /**
     * @param  string  $code
     * @param  District|string|int  $district
     * @param  null  $province
     * @return self
     */
    public static function getByCode(string $code, $district, $province = null): self
    {
        $code = sprintf('%05s', $code);

        $wards = static::district($district, $province);

        if (!$ward = $wards->get($code)) {
            $ward = $wards->firstWhere('code', $code);
        }

        if (!$ward) {
            throw (new ModelNotFoundException)->setModel(self::class, $code);
        }

        return $ward;
    }

    /**
     * @param  District|string|int  $district
     * @return string
     */
    protected static function normalizeDistrictCode($district): string
    {
        if ($district instanceof District) {
            return $district->getCode();
        }

        if (is_int($district)) {
            return sprintf('%03s', $district);
        }

        return (string) $district;
    }
}
