<?php

namespace App\Model;

use Orchid\Filters\Filterable;

/**
 * App\Model\Counselor
 *
 * @mixin \Eloquent
 */
class Counselor extends Model
{
    use Filterable;

    /* Constants */
    public const GENDER_MAN = 'men';
    public const GENDER_WOMAN = 'women';
    public const GENDER_ANOTHER = 'another';
    public const PERMISSION_VIEW = 'platform.counselors.view';
    public const PERMISSION_TOUCH = 'platform.counselors.touch';
    public const PERMISSION_DELETE = 'platform.counselors.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'counselors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'company_name',
        'first_name',
        'last_name',
        'display_name',
        'gender',
        'year_of_birth',
        'phone',
        'job_title',
        'rate_value',
        'avatar',
        'province_id',
        'district_id',
    ];

    /**
     * //
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'uid',
        'display_name',
        'created_at',
        'updated_at',
        'year_of_birth',
    ];

    /**
     * //
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'uid',
        'display_name',
        'gender',
        'province_id',
        'district_id',
    ];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            if (!$model->display_name) {
                $model->display_name = $this->getdisplayNameAttribute('');
            }
        });
    }

    public function personality()
    {
        return $this->belongsToMany(Personality::class);
    }

    public function getAvatarAttribute($value)
    {
        return $value ?: '/img/default-avatar.jpg';
    }

    public function getDisplayNameAttribute($value)
    {
        return ucwords($value ?: sprintf('%s %s', $this->last_name, $this->first_name));
    }

    public function getYearOfBirthAttribute($value)
    {
        return !$value || $value == '0' ? null : $value;
    }

    /**
     * //
     *
     * @return array
     */
    public static function getGender(): array
    {
        return [
            self::GENDER_MAN     => __('Nam'),
            self::GENDER_WOMAN   => __('Nữ'),
            self::GENDER_ANOTHER => __('Giới tính khác'),
        ];
    }
}
