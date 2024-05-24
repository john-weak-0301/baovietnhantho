<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Link;
use Orchid\Screen\Layout;
use Orchid\Platform\Dashboard;

class PlatformScreen extends AbstractScreen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Welcome';

    /**
     * Query data.
     *
     *
     * @return array
     */
    public function query(): array
    {
        return [
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [

        ];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            // Layout::view('platform::partials.update'),
            // Layout::view('platform::partials.welcome'),
        ];
    }
}
