<?php

namespace Core\Screens;

use Core\Database\Repository;

interface HasRepository
{
    /**
     * Returns the database repository.
     *
     * @return Repository
     */
    public function getRepository(): Repository;
}
