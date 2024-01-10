<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use App\Models\web\AcnMember;
use App\Models\web\AcnSite;

class ManagerPanelController extends Controller
{
    static public function displayManagerPanel() {
        return view("manager/panel", ["boats" => AcnBoat::getAllBoats(), "sites" => AcnSite::getAllSites(), "members" => AcnMember::all()]);
    }
}
