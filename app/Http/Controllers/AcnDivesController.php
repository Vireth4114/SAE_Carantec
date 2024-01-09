<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\web\AcnDives;

class AcnDivesController extends Controller
{
    public static function test() {
        return view("test",["dive" => AcnDives::all()]);
    }
}
