<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnPeriodController extends Controller
{
    static public function getPeriodName($numPeriod) {
        $name = DB::table('ACN_PERIOD')
            -> select('PER_LABEL')
            -> where('PER_NUM_PERIOD', $numPeriod)
            -> get();
            
        $name = (array) $name[0];
        return $name['PER_LABEL'];
    }
}
