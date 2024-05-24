<?php

namespace App\Console\Commands;

use App\Model\News;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Console\Command;

class UpdatePostCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:update-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update view count of posts';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        News::each(function (Viewable $viewable) {
            retry(3, function () use ($viewable) {
                $this->info('Update posts count for: '.$viewable->title);

                $viewable->unique_views_count = views($viewable)
                    ->unique()
                    ->period(Period::pastYears(1))
                    ->count();

                $viewable->save();
            });
        }, 200);
    }
}
