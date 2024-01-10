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
        $dive = AcnDives::find($diveId);
        $members = AcnDives::getMembersNotInDive($diveId);
        $directorRegistered = true;
        foreach($members as $member) {
            if ($member->MEM_NUM_MEMBER == $dive['DIV_NUM_MEMBER_LEAD']) $directorRegistered=false;
        }

        $tempMembers = AcnDives::find($diveId)->divers;
        $regMembers = array();
        foreach($tempMembers as $member) {
            if ($member->MEM_NUM_MEMBER != $dive['DIV_NUM_MEMBER_LEAD']) array_push($regMembers, $member);
        }
        
        $maxReached = count($regMembers)==$dive['DIV_MAX_REGISTERED'];
        foreach($members as $member) {
            if ($member->MEM_NUM_MEMBER == $dive['DIV_NUM_MEMBER_LEAD']) $directorRegistered=false;
        }
        return view('director/addDiveMember', ["members" => $members, "dive" => $dive, "directorRegistered" => $directorRegistered, "maxReached" => $maxReached]);
    }

    public static function diveInformation($diveId) {
        $dive = AcnDives::find($diveId);
        $allMembers = AcnDives::find($diveId)->divers;
        $members = array();
        foreach($allMembers as $member) {
            if (!($member->MEM_NUM_MEMBER == $dive['DIV_NUM_MEMBER_LEAD'])) array_push($members, $member); 
        }
        $nbMembers = count($members);
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
            $selectedSecurity = AcnMember::find($dive['DIV_NUM_MEMBER_SECURED']);
            $selectedSecurity = $selectedSecurity->MEM_NAME." ".$selectedSecurity->MEM_SURNAME;
        }
        if (is_null($dive['DIV_NUM_MEMBER_LEAD'])) {
            $selectedLead = "non définit";
        } else {
            $selectedLead = AcnMember::find($dive['DIV_NUM_MEMBER_LEAD']);
            $selectedLead = $selectedLead->MEM_NAME." ".$selectedLead->MEM_SURNAME;
        }
        if (is_null($dive['DIV_NUM_MEMBER_PILOT'])) {
            $selectedPilot = "non définit";
        } else {
            $selectedPilot = AcnMember::find($dive['DIV_NUM_MEMBER_PILOT']);
            $selectedPilot = $selectedPilot->MEM_NAME." ".$selectedPilot->MEM_SURNAME;
        }

        $min_divers = $dive['DIV_MIN_REGISTERED'];
        $max_divers = $dive['DIV_MAX_REGISTERED'];
        
        return view('director/diveInformation', ['members' => $members, 'dive' => $dive, 'site' => $site, 'period' => $period, 
        'security' => $selectedSecurity, 'lead' => $selectedLead, 'pilot' => $selectedPilot, 'min_divers' => $min_divers, 
        'max_divers' => $max_divers, 'nbMembers' => $nbMembers]);
    }


}