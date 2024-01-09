<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnBoatController extends Controller
{
    static public function getAllBoat() {
        return view ('propose_slot', ["boats" => AcnBoat::all() ]);
    }

    static public function getBoatCapacity($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_CAPACITY')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['BOA_CAPACITY'];
    }

    static public function getMaxCapacity() {
        $capacity = DB::table('ACN_BOAT')
            -> selectRaw('max(BOA_CAPACITY) as max')
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['max'];
    }

    static public function getBoatName($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_NAME')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['BOA_NAME'];
    }
}
