<?php

namespace App\Orchid\Filters;

use App\Model\District;
use App\Model\Province;
use Core\Elements\Filter;
use App\Orchid\Fields\SelectArea;
use Illuminate\Database\Eloquent\Builder;

class AreaFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'area.province',
        'area.district',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Khu vực');
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $builder->where(function (Builder $query) {
            $query->where('province_id', (int) $this->request->input('area.province'));

            if ($district = $this->request->input('area.district')) {
                $query->where('district_id', (int) $district);
            }
        });

        return $builder;
    }

    /**
     * @return array|\Orchid\Screen\Field[]
     */
    public function display(): array
    {
        return [
            SelectArea::make('area')
                ->value(map_deep($this->request->input('area'), 'clean')),
        ];
    }

    /**
     * Resolve parameters values.
     *
     * @param  array  $parameters
     * @return array
     */
    public function resolveValues(array $parameters)
    {
        return [
            'area.province' => rescue(function () use ($parameters) {
                return Province::getByCode($parameters['area']['province'])->getName();
            }),

            'area.district' => rescue(function () use ($parameters) {
                if (empty($parameters['area']['district'])) {
                    return null;
                }

                return District::getByCode($parameters['area']['district'])->getNameWithType();
            }),
        ];
    }
}
