<?php

namespace Core\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * An array of callbacks to be run once after the model is saved.
     *
     * @var callable[]
     */
    protected $savedCallbacks = [];

    /**
     * An array of callbacks to be run once after the model is deleted.
     *
     * @var callable[]
     */
    protected $deletedCallbacks = [];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function (Model $model) {
            foreach ($model->releaseSavedCallbacks() as $callback) {
                $callback($model);
            }
        });

        static::deleted(function (Model $model) {
            foreach ($model->releaseDeletedCallbacks() as $callback) {
                $callback($model);
            }
        });
    }

    /**
     * Register a callback to be run once after the model is saved.
     *
     * @param  callable  $callback
     * @return void
     */
    public function afterSave($callback): void
    {
        $this->savedCallbacks[] = $callback;
    }

    /**
     * Register a callback to be run once after the model is deleted.
     *
     * @param  callable  $callback
     * @return void
     */
    public function afterDelete($callback): void
    {
        $this->deletedCallbacks[] = $callback;
    }

    /**
     * @return callable[]
     */
    public function releaseSavedCallbacks(): array
    {
        $callbacks = $this->savedCallbacks;

        $this->savedCallbacks = [];

        return $callbacks;
    }

    /**
     * @return callable[]
     */
    public function releaseDeletedCallbacks(): array
    {
        $callbacks = $this->deletedCallbacks;

        $this->deletedCallbacks = [];

        return $callbacks;
    }
}
