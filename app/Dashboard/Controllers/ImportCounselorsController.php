<?php

namespace App\Dashboard\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CounselorsImport;

class ImportCounselorsController
{
    public function __invoke(Request $request)
    {
        return Excel::import(new CounselorsImport, $request->file());
    }
}
