<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\web\AcnDives;
use App\Models\web\AcnSite;

class AcnDivesController extends Controller
{
    public static function getDivesValues() {
        return view("displayDives",["dives" => AcnDives::all()]);
    }

}
