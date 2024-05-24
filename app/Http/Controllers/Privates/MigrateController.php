<?php

namespace App\Http\Controllers\Privates;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\StreamOutput;

class MigrateController
{
    public function __invoke(Request $request)
    {
        if ($request->get('force') !== 'yes') {
            abort(500);
        }

        $output = new StreamOutput(fopen('php://output', 'wb'));

        echo '<pre>Run migration:</pre>';

        try {
            echo '<pre>';
            Artisan::call('migrate', ['--force' => true], $output);
            echo '</pre>';

            exit(0);
        } catch (\Exception $e) {
            return response($e, 500);
        }
    }
}
