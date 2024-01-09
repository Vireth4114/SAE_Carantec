<?php

namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcnDivesController extends Controller {
    static public function getAllDives() {
        return view("home", ["dives" => AcnDives::all()]);
    }

    static public function getOneDive($dive_num){
       return view("home" , ["dive" => AcnDives::all()->find($dive_num)]);
    }
}
