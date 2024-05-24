<?php

namespace App\Model;

use LogicException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionProduct extends Product
{
    /**
     * @return string|null
     */
    public function getProductType()
    {
        return 'addition';
    }

    public function additions(): BelongsToMany
    {
        throw new LogicException('Cannot add additions by self.');
    }
}
