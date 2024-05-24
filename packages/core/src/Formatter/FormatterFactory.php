<?php

namespace Core\Formatter;

use Illuminate\Contracts\Foundation\Application;

class FormatterFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(Application $app)
    {
        return new Formatter(
            $app->make('cache.store'),
            $app->make('events'),
            storage_path('formatter')
        );
    }
}
