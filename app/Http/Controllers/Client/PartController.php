<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Part;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::all();

        return $this->success($parts);
    }
}
