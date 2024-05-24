<?php

namespace App\Model\UrlPresenters;

use App\Model\Experience;

class ExperienceUrlPresenter extends UrlPresenter
{
    /**
     * @var Experience
     */
    protected $model;

    public function __construct(Experience $model)
    {
        $this->model = $model;
    }

    public function link(): string
    {
        if (!$this->model->slug) {
            return '';
        }

        return route('exp', $this->model->slug);
    }

    public function edit(): string
    {
        return route('platform.exps.edit', $this->model);
    }

    public function editor(): string
    {
        return route('platform.exps.editor', $this->model);
    }
}
