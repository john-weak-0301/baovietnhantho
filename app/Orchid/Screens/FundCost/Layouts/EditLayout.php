<?php

namespace App\Orchid\Screens\FundCost\Layouts;

use App\Model\FundImport;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Select::make('fundImport.status')
                ->options(FundImport::STATUSES)
                ->title(__('Trạng thái')),
        ];
    }
}
