<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcnBoatController extends Controller
{
    static public function getAllBoats() {
        return view("home", ["boats" => AcnBoat::all()]);
    }
}
