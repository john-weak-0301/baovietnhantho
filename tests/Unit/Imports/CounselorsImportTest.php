<?php

namespace Tests\Unit\Imports;

use Tests\TestCase;
use Maatwebsite\Excel\Excel;
use App\Imports\CounselorsImport;

class CounselorsImportTest extends TestCase
{
    public function testImport()
    {
        $imported = (new CounselorsImport)->import(
            __DIR__.'/data/ha-noi.xlsx',
            null,
            Excel::XLSX
        );
    }
}
