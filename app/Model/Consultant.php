<?php

namespace App\Model;

use Orchid\Filters\Filterable;

/**
 * App\Model\Consultant
 *
 * @mixin \Eloquent
 */
class Consultant extends Model
{
    use Filterable;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.consultants.view';
    public const PERMISSION_TOUCH = 'platform.consultants.touch';
    public const PERMISSION_DELETE = 'platform.consultants.delete';

    public const MONDAY = 'monday';
    public const TUESDAY = 'tuesday';
    public const WEDNESDAY = 'wednesday';
    public const THURSDAY = 'thursday';
    public const FRIDAY = 'friday';
    public const SATURDAY = 'saturday';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consultants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'counselor_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'private_note',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
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
}
