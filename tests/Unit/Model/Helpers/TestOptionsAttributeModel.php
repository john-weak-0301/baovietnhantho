<?php

namespace Tests\Unit\Model\Helpers;

use Illuminate\Database\Eloquent\Model;
use App\Model\Helpers\HasOptionsAttribute;

class TestOptionsAttributeModel extends Model
{
    use HasOptionsAttribute;

    protected $table = 'test_options_models';

    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'options' => 'array',
    ];
}
