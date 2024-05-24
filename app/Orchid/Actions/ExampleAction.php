<?php

namespace App\Orchid\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Core\Actions\Action;
use Core\Actions\ActionFields;

class ExampleAction extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Crawl Chapters';

    /**
     * Perform the action on the given models.
     *
     * @param  ActionFields  $fields
     * @param  Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each->delete();
    }

    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return 'example-action';
    }
}
