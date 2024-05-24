<?php

namespace Core\Dashboard\Actions;

use Core\Actions\Action;
use Core\Actions\ActionFields;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class DeleteMediaAction extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = __('Xóa');
    }

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
}
