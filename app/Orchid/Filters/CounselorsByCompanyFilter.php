<?php

namespace App\Orchid\Filters;

use App\Model\Counselor;
use Core\Elements\Filter;
use Orchid\Screen\Fields\Select;
use Illuminate\Database\Eloquent\Builder;

class CounselorsByCompanyFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'company'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Công Ty');
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $builder->where('company_name', 'like', '%'.clean($this->request->company).'%');

        return $builder;
    }

    /**
     * @return array|\Orchid\Screen\Field[]
     */
    public function display(): array
    {
        return [
            Select::make('company')
                ->empty()
                ->fromQuery(
                    Counselor::query()->select('company_name')->distinct(),
                    'company_name',
                    'company_name'
                ),
        ];
    }
}
