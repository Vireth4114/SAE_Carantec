<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use App\Models\web\AcnDives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnDirectorController extends Controller
{

    public static function addDiveMember($diveId) {
        $members = AcnMember::all();
        $dive = AcnDives::find($diveId);
        return view('director/addDiveMember', ["members" => $members, "dive" => $dive]);
    }

}