<?php

namespace App\Imports;

use App\Model\Counselor;
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

class CounselorsImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithMultipleSheets,
    SkipsOnError
{
    use Importable, SkipsErrors;

    /**
     * //
     *
     * @var int
     */
    protected $headingRow = 9;

    /**
     * //
     *
     * @var int
     */
    protected $activeSheet = 0;

    protected $imported = 0;

    /**
     * Constructor.
     *
     * @param  int  $activeSheet
     * @param  int  $headingRow
     */
    public function __construct($activeSheet = 0, $headingRow = 9)
    {
        $this->activeSheet = $activeSheet;
        $this->headingRow  = $headingRow;
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
        $attributes = $this->parseAttributes($row);

        if (empty($attributes['uid']) || empty($attributes['display_name'])) {
            return;
        }

        try {
            Validator::make($attributes, [
                'uid' => 'required|unique:counselors',
            ])->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->onError(new \RuntimeException(
                sprintf('Tư vấn viên mới mã "%s" đã tồn tại trong hệ thống', $attributes['uid'])
            ));

            return;
        }

        ++$this->imported;

        return new Counselor($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function headingRow(): int
    {
        return $this->headingRow;
    }

    /**
     * {@inheritdoc}
     */
    public function sheets(): array
    {
        return [
            $this->activeSheet => $this,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    protected function parseAttributes(array $row)
    {
        $name = $row['ho_va_ten_tu_van_vien'] ?? $row['ho_va_ten'] ?? $row['ho_ten'] ?? '';

        [$firstName, $lastName] = $this->parseNames($name);

        [$province, $district] = $this->parseAreaIds($row);

        $gender = $row['gioi_tinh'] ?? '';
        $gender = (mb_strtolower($gender) === 'nữ') ? 'women' : 'men';

        return [
            'uid'           => clean($row['ma_tvv'] ?? $row['ma_tu_van_vien'] ?? $row['ma_nhan_vien'] ?? ''),
            'company_name'  => clean($row['cong_ty'] ?? $row['cty'] ?? $row['comany'] ?? ''),
            'phone'         => clean($row['sdt'] ?? $row['so_dien_thoai'] ?? $row['dien_thoai'] ?? $row['phone'] ?? ''),
            'display_name'  => clean($name),
            'first_name'    => clean($firstName ?: ''),
            'last_name'     => clean($lastName ?? ''),
            'year_of_birth' => clean($row['nam_sinh'] ?? 0),
            'gender'        => $gender,
            'province_id'   => $province,
            'district_id'   => $district,
        ];
    }

    protected function parseNames(string $name)
    {
        $names = explode(' ', $name);

        if (count($names) <= 1) {
            return ['', $name];
        }

        $lastName = array_pop($names);

        return [ucwords($lastName), trim(ucwords(implode(' ', $names)))];
    }

    protected function parseAreaIds(array $row)
    {
        $provinceName = $row['tinhthanh_pho'] ?? $row['tinh_thanh_pho'] ?? $row['tinh_tp'] ?? '';
        if (!$provinceName) {
            return [0, 0];
        }

        $provinceName = trim(
            str_replace(['tp.', 'tp', 'tỉnh', 'thành phố'], '', Str::lower($provinceName))
        );

        /* @var Province $province */
        $province = Province::all()->first(function (Province $p) use ($provinceName) {
            return Str::lower($p->getName()) === $provinceName ||
                   Str::slug($provinceName) === $p->getSlug();
        });

        if (!$province) {
            return [0, 0];
        }

        $districtName = $row['quan_huyen'] ?? $row['quanhuyen'] ?? $row['huyenquan'] ?? $row['huyen_quan'] ?? '';

        if (!$districtName) {
            return [$province->getCode(), 0];
        }

        $districtName = trim(
            str_replace(['q. ', 'h. ', 'quận', 'huyện'], '', Str::lower($districtName))
        );

        $district = $province->getDistricts()->first(function (District $p) use ($districtName) {
            return Str::lower($p->getName()) === $districtName ||
                   Str::slug($districtName) === $p->getSlug();
        });

        return [$province->getCode(), $district->getCode()];
    }
}
