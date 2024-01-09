<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnDivesController extends Controller
{

    static public function getNumMax() {
        return DB::table('ACN_DIVES')
            -> selectRaw('max(DIV_NUM_DIVE)+1 as maxi')
            -> get();
    }
}
