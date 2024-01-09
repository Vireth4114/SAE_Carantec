<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;

class AcnBoatController extends Controller
{
    static public function getAllBoat() {
        return view ('propose_slot', ["boats" => AcnBoat::all() ]);
    }
}
