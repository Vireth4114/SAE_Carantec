<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use App\Models\web\AcnMember;
use App\Models\web\AcnSite;

class DirectorPanelController extends Controller
{
    static public function displayDirectorPanel() {
        return view("manager/panel", ["boats" => AcnBoat::getAllBoats(), "sites" => AcnSite::getAllSites(), "members" => AcnMember::all()]);
    }
}
