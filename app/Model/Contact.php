<?php

namespace App\Model;

/**
 * App\Model\Contact
 *
 * @mixin \Eloquent
 */
class Contact extends Model
{
    /* Constants */
    public const PERMISSION_VIEW = 'platform.contacts.view';
    public const PERMISSION_DELETE = 'platform.contacts.delete';

    /**
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'province_code',
        'address',
        'message',
    ];
}
