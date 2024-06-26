<?php

namespace Core\Actions;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Transaction
{
    /**
     * Perform the given callbacks within a batch transaction.
     *
     * @param  callable  $callback
     * @param  callable|null  $finished
     * @return mixed
     *
     * @throws Throwable
     */
    public static function run($callback, $finished = null)
    {
        try {
            DB::beginTransaction();

            $batchId = (string) Str::orderedUuid();

            return tap($callback($batchId), function ($response) use ($finished, $batchId) {
                if ($finished) {
                    $finished($batchId);
                }

                DB::commit();
            });
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
