<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use App\Models\web\AcnDives;
use App\Models\web\AcnSite;
use App\Models\web\AcnPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnDirectorController extends Controller
{

    public static function addDiveMember($diveId) {
        $members = AcnDives::getMembersNotInDive($diveId);
        $dive = AcnDives::find($diveId);
        return view('director/addDiveMember', ["members" => $members, "dive" => $dive]);
    }

    public static function diveInformation($diveId) {
        $members = AcnDives::find($diveId)->divers;
        $dive = AcnDives::find($diveId);
        $period = AcnPeriod::find($dive['DIV_NUM_PERIOD']);
        $period = "de ".$period->PER_START_TIME->format('H')."h à ".$period->PER_END_TIME->format('H')."h";

        if (is_null($dive['DIV_NUM_SITE'])) {
            $site = "non définit";
        } else {
            $site = AcnSite::find($dive['DIV_NUM_SITE'])->SIT_NAME;
        }

        if (is_null($dive['DIV_NUM_MEMBER_SECURED'])) {
            $selectedSecurity = "non définit";
        } else {
            $selectedSecurity = AcnMember::find($dive['DIV_NUM_MEMBER_SECURED'])->MEM_NUM_MEMBER;
        }
        if (is_null($dive['DIV_NUM_MEMBER_LEAD'])) {
            $selectedLead = "non définit";
        } else {
            $selectedLead = AcnMember::find($dive['DIV_NUM_MEMBER_LEAD'])->MEM_NUM_MEMBER;
        }
        if (is_null($dive['DIV_NUM_MEMBER_PILOT'])) {
            $selectedPilot = "non définit";
        } else {
            $selectedPilot = AcnMember::find($dive['DIV_NUM_MEMBER_PILOT'])->MEM_NUM_MEMBER;
        }

        $min_divers = $dive['DIV_MIN_REGISTERED'];
        $max_divers = $dive['DIV_MAX_REGISTERED'];
        
        return view('director/diveInformation', ['members' => $members, 'dive' => $dive, 'site' => $site, 'period' => $period, 
        'security' => $selectedSecurity, 'lead' => $selectedLead, 'pilot' => $selectedPilot, 'min_divers' => $min_divers, 
        'max_divers' => $max_divers]);
    }


}