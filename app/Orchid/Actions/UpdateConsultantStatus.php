<?php

namespace App\Orchid\Actions;

use App\Model\Consultant;
use Carbon\Carbon;
use Core\Actions\Action;
use Core\Actions\ActionFields;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class UpdateConsultantStatus extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Constructor.
     *
     * @param string $status
     */
    public function __construct(string $status = 'pending')
    {
        $this->name = $status === 'pending'
            ? __('Cập nhật đã tư vấn')
            : __('Cập nhật chưa tư vấn');
    }

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function (Consultant $consultant) {
            $consultant->reserved_at = !$consultant->reserved_at
                ? Carbon::now()
                : null;

            $consultant->save();
        });
    }

    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return 'UpdateConsultantStatus';
    }
}
