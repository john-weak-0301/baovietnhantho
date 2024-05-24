<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Model\Branch;

class BranchController extends Controller
{
    public function index()
    {
        return view('branch.list');
    }

    public function json()
    {
        $branchs = Branch::with('services')->get();

        return tap(BranchResource::collection($branchs), function () {
            BranchResource::withoutWrapping();
        });
    }
}
