<?php

namespace App\Model;

use App\Model\UrlPresenters\UrlPresenter;
use App\Model\UrlPresenters\ExperienceUrlPresenter;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Experience extends News
{
    /* Constants */
    public const PERMISSION_VIEW = 'platform.exps.view';
    public const PERMISSION_TOUCH = 'platform.exps.touch';
    public const PERMISSION_DELETE = 'platform.exps.delete';

    /**
     * The post type name.
     *
     * @var string
     */
    protected $postType = 'experience';

    /**
     * @var string
     */
    public $searchableType = 'exps';

    /**
     * {@inheritdoc}
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            ExperienceCategory::class,
            'categoriable',
            'categoriables',
            null,
            'category_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlAttribute(): UrlPresenter
    {
        return new ExperienceUrlPresenter($this);
    }
}
