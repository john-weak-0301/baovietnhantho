<?php

namespace App\Model;

use Orchid\Filters\Filterable;

/**
 * App\Model\Branch
 *
 * @mixin \Eloquent
 */
class Branch extends Model
{
    use Filterable;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.branchs.view';
    public const PERMISSION_TOUCH = 'platform.branchs.touch';
    public const PERMISSION_DELETE = 'platform.branchs.delete';

    public const MONDAY = 'monday';
    public const TUESDAY = 'tuesday';
    public const WEDNESDAY = 'wednesday';
    public const THURSDAY = 'thursday';
    public const FRIDAY = 'friday';
    public const SATURDAY = 'saturday';

    public const HEADQUARTERS = 'headquarters';
    public const TRANSACTION_POINT = 'transaction_point';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branchs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'name',
        'province_code', 'ward_code', 'district_code',
        'latitude', 'longitude', 'phone_number', 'fax_number',
        'email', 'type', 'working_days',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'working_days' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (self $branch) {
            $branch->services()->detach();
        });
    }

    public function services()
    {
        return $this->belongsToMany(BranchService::class, 'branchs_serviceables');
    }

    public static function getDate(): array
    {
        return [
            self::MONDAY    => __('Thứ 2'),
            self::TUESDAY   => __('Thứ 3'),
            self::WEDNESDAY => __('Thứ 4'),
            self::THURSDAY  => __('Thứ 5'),
            self::FRIDAY    => __('Thứ 6'),
            self::SATURDAY  => __('Thứ 7'),
        ];
    }

    public static function getMain()
    {
        return [
            self::HEADQUARTERS      => __('Trụ sở chính'),
            self::TRANSACTION_POINT => __('Điểm giao dịch'),
        ];
    }
}
