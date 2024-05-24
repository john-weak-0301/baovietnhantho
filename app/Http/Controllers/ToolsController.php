<?php

namespace App\Http\Controllers;

use App\Model\Province;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function __invoke(Request $request)
    {
        $provinces = Province::all()->values();

        return view('consultants', compact('provinces'));
    }
}
