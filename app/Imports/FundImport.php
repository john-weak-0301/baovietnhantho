<?php

namespace App\Imports;

use App\Model\Fund;
use App\Model\FundCost;
use App\Model\District;
use App\Model\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class FundImport implements
    ToModel,
    WithValidation
{
    use Importable, SkipsErrors;

    /**
     * //
     *
     * @var int
     */
    protected $activeSheet = 0;

    protected $imported = 0;

    protected $fundId;

    protected $importedId;

    /**
     * Constructor.
     *
     * @param  int  $activeSheet
     * @param  int  $headingRow
     */
    public function __construct($input = [])
    {
        $this->activeSheet = $input['active_sheet'] ?? 0;
        $this->headingRow  = $input['heading_row'] ?? 0;
        $this->fundId = $input['fund_id'] ?? 0;
        $this->importedId = $input['imported_id'] ?? 0;
    }

    public function getImported(): int
    {
        return $this->imported;
    }

    /**
     * {@inheritdoc}
     */
    public function model(array $row)
    {
        if (empty($this->fundId) || empty($this->fundId)) {
            new \RuntimeException(
                "Some parameters are missing"
            );
        }
        $attributes = $this->parseAttributes($row);

        if (empty($attributes['date']) || empty($attributes['value'])) {
            return;
        }

        try {
            Validator::make($attributes, [
                'date' => 'required|date|date_format:Y-m-d',
                'value' => 'required|numeric',
            ])->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->onError(new \RuntimeException(
                sprintf($e->getMessage())
            ));

            return;
        }

        ++$this->imported;

        return new FundCost($attributes);
    }

    protected function parseAttributes(array $row)
    {
        return [
            "date" => $row[0],
            "value" => $row[1],
            "imported_id" => $this->importedId,
            "quy_lkdv_id" => $this->fundId,
        ];
    }

    public function rules(): array
    {
        return [];
    }
}
