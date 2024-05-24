<?php

namespace App\Repositories;

use App\Model\Contact;
use Core\Database\Repository;

class ContactRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Contact::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'email',
        'phone_number',
        'address',
    ];
}
