<?php

namespace App\Model\UrlPresenters;

use App\Model\News;

class NewsUrlPresenter extends UrlPresenter
{
    /**
     * @var News
     */
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function link(): string
    {
        if (!$this->model->slug) {
            return '';
        }

        return route('news', $this->model->slug);
    }

    public function edit(): string
    {
        return route('platform.news.edit', $this->model);
    }

    public function editor(): string
    {
        return route('platform.news.editor', $this->model);
    }
}
