<?php

namespace App\Exports;

use App\Model\District;
use App\Model\Province;
use App\Model\Consultant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ConsultantsExporter implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithColumnFormatting,
    ShouldAutoSize
{
    use Exportable;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var bool
     */
    protected $onlyConsulted;

    /**
     * @var bool
     */
    protected $withoutConsulted;

    /**
     * @return $this
     */
    public function forYear(int $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyConsulted()
    {
        $this->onlyConsulted = true;

        $this->withoutConsulted = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutConsulted()
    {
        $this->withoutConsulted = true;

        $this->onlyConsulted = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withConsulted()
    {
        $this->onlyConsulted = false;

        $this->withoutConsulted = false;

        return $this;
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Consultant::query()
            ->with('counselor')
            ->when($this->year, function (Builder $query) {
                $query->whereYear('created_at', $this->year);
            })
            ->tap(function (Builder $query) {
                if ($this->onlyConsulted) {
                    $query->whereNotNull('reserved_at');
                } elseif ($this->withoutConsulted) {
                    $query->whereNull('reserved_at');
                }
            });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tên KH',
            'SĐT',
            'Email',
            'Tỉnh/Thành Phố',
            'Quận/Huyện',
            'Ghi chú',
            'NV tư vấn',
            'ID NV tư vấn',
            'Ngày tạo',
        ];
    }

    /**
     * @param object $row
     * @return array
     */
    public function map($row): array
    {
        [$province, $district] = $this->parseAddress($row->customer_address);

        return [
            $row->id,
            $row->customer_name,
            $row->customer_phone,
            $row->customer_email,
            $province,
            $district,
            $row->private_note,
            $row->counselor->display_name ?? '',
            $row->counselor->id ?? '',
            Date::dateTimeToExcel($row->created_at),
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    protected function parseAddress($customerAddress): array
    {
        if (empty($customerAddress)) {
            return ['', ''];
        }

        $address = explode(',', $customerAddress, 2);
        $provinces = Province::all();

        /* @var Province|null $province */
        $province = $provinces->first(function ($province) use ($address) {
            $name = trim(Str::lower($address[0]));
            $name2 = trim(Str::lower($address[1] ?? ''));

            if ($name === 'hn') {
                $name = 'hà nội';
            }

            if ($name2 === 'hn') {
                $name2 = 'hà nội';
            }

            return Str::lower($province['name_with_type']) === $name
                   || Str::lower($province['name']) === $name
                   || Str::contains($name, Str::lower($province['name']))
                   || Str::contains(Str::lower($province['name_with_type']), $name)
                   || ($name2 && Str::lower($province['name_with_type']) === $name2)
                   || ($name2 && Str::lower($province['name']) === $name2)
                   || ($name2 && Str::contains($name2, Str::lower($province['name'])))
                   || ($name2 && Str::contains(Str::lower($province['name_with_type']), $name2));
        });

        if (!$province) {
            return ['', $customerAddress];
        }

        /* @var District $district */
        $district = $province->getDistricts()->first(function ($province) use ($address) {
            $name = trim(Str::lower($address[0]));
            $name2 = trim(Str::lower($address[1] ?? ''));

            return ($name2 && Str::lower($province['name_with_type']) === $name2)
                   || ($name2 && Str::lower($province['name']) === $name2)
                   || ($name2 && Str::contains($name2, Str::lower($province['name'])))
                   || ($name2 && Str::contains(Str::lower($province['name_with_type']), $name2))
                   || Str::lower($province['name_with_type']) === $name
                   || Str::lower($province['name']) === $name
                   || Str::contains($name, Str::lower($province['name']))
                   || Str::contains(Str::lower($province['name_with_type']), $name);
        });

        return [
            $province->getName(),
            $district ? $district->getNameWithType() : '',
        ];
    }
}
