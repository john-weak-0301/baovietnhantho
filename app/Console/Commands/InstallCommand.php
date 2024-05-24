<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the site database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        define('APP_INSTALLING', true);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        if (Schema::hasTable('migrations') && !$this->option('force')) {
            $this->warn('Hệ thống đã được cài đặt!');

            return;
        }

        $this->call('optimize:clear');

        $this->info('Cài đặt CSDL...');
        $this->call('migrate:refresh', ['--force']);

        $this->info('Import CSDL...');
        usleep(500000);

        $this->call('db:seed', ['--force']);
        $this->info('Nhập thành công CSDL!');
    }
}
