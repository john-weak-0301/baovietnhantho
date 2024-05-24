<?php

namespace App\Model\UrlPresenters;

use App\Model\Page;

class PageUrlPresenter extends UrlPresenter
{
    /**
     * @var Page
     */
    protected $model;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function link(): string
    {
        if (!$this->model->slug) {
            return '';
        }

        if ($this->model->type === 'home') {
            return url('/');
        }

        return route('page', $this->model->slug);
    }

    public function edit(): string
    {
        return route('platform.pages.edit', $this->model);
    }

    public function editor(): string
    {
        return route('platform.pages.editor', $this->model);
    }
}
