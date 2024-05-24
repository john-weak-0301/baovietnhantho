<?php

namespace App\Model;

class ExperienceCategory extends Category
{
    /* Constants */
    public const PERMISSION_VIEW = 'platform.exps_categories.view';
    public const PERMISSION_TOUCH = 'platform.exps_categories.touch';
    public const PERMISSION_DELETE = 'platform.exps_categories.delete';

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespaceType = 'experience';
}
