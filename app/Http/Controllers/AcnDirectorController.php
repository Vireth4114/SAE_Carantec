<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use App\Models\web\AcnDives;
use App\Models\web\AcnPrerogative;
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
        $levels = array();
        foreach($members as $member) {
            $memberPriority = AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER);
            array_push($levels, AcnPrerogative::find($memberPriority)->PRE_LABEL);
        }
        $registeredMembers = $dive->divers;

        $directorRegistered = $registeredMembers->contains("MEM_NUM_MEMBER", $dive['DIV_NUM_MEMBER_LEAD']);
        
        $maxReached = $registeredMembers->count()==$dive['DIV_MAX_REGISTERED'];
        return view('director/addDiveMember', ["members" => $members, "dive" => $dive, "directorRegistered" => $directorRegistered, 
        "maxReached" => $maxReached, 'levels' => $levels]);
    }

    public static function diveInformation($diveId) {
        $dive = AcnDives::find($diveId);
        $allMembers = AcnDives::find($diveId)->divers;
        $members = array();
        $levels = array();
        foreach($allMembers as $member) {
            if (!($member->MEM_NUM_MEMBER == $dive['DIV_NUM_MEMBER_LEAD'])) {
                array_push($members, $member);
                $memberPriority = AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER);
                array_push($levels, AcnPrerogative::find($memberPriority)->PRE_LABEL);
            }
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
        'max_divers' => $max_divers, 'nbMembers' => $nbMembers, 'levels' => $levels]);
    }

    public static function myDirectorDives() {
        $dives = AcnDives::getDirectorDives(auth()->user()->MEM_NUM_MEMBER);
        $sites = array();
        $prerogatives = array();
        $periods = array();
        foreach($dives as $dive) {
            $site = AcnSite::find($dive->DIV_NUM_SITE);
            if (is_null($site)) {
                $site = "non définit";
            } else {
                $site = $site->SIT_NAME." (".$site->SIT_DESCRIPTION.")";
            }
            array_push($sites, $site);

            $prerogative = AcnPrerogative::find($dive->DIV_NUM_PREROG);
            if (is_null($prerogative)) {
                $prerogative = "non définit";
            } else {
                $prerogative = $prerogative->PRE_LABEL;
            }
            array_push($prerogatives, $prerogative);

            $period = AcnPeriod::find($dive->DIV_NUM_PERIOD);
            array_push($periods, $period);
        }

        return view('director/myDirectorDives', ['dives' => $dives, 'sites' => $sites, 'prerogatives' => $prerogatives, 'periods' => $periods]);
    }


}