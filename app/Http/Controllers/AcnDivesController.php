<?php

namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnMember;
use App\Models\web\AcnBoat;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnPrerogative;
use App\Models\web\AcnRegistered;
use App\Models\web\AcnSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LDAP\Result;

class AcnDivesController extends Controller
{
    public static function getAllDivesValues() {
        $months = AcnDives::getMonthWithDive();

        $dives = array();
        foreach ($months as $month) {
            $dive = AcnDives::getDivesOfAMonth($month->mois_nb);
            $dives[$month->mois_mot] = $dive;
        }
        return view("displayDives",["dives" => $dives, "months" => $months]);
    }

    static public function getNumMax() {
        $max = DB::table('ACN_DIVES')
            -> selectRaw('max(DIV_NUM_DIVE)+1 as maxi')
            -> get();

        $max = (array) $max[0];
        return $max['maxi'];
    }



    static public function existDive($date, $numPeriod) {
        return DB::table('ACN_DIVES')
            -> select(DB::raw(1))
            -> where('DIV_NUM_PERIOD', $numPeriod)
            -> where('DIV_DATE', '=', date($date))
            -> exists();

    }

    public static function getAllDiveInformation($id){
        $dives = AcnDives::find($id);
        $dives_lead = AcnMember::find($dives -> DIV_NUM_MEMBER_LEAD);
        if (is_null($dives_lead)) {
            $dives_lead = "non définit";
        } else {
            $dives_lead = $dives_lead->MEM_NAME." ".$dives_lead->MEM_SURNAME;
        }
        
        $dives_secur = AcnMember::find($dives -> DIV_NUM_MEMBER_SECURED);
        if (is_null($dives_secur)) {
            $dives_secur = "non définit";
        } else {
            $dives_secur = $dives_secur->MEM_NAME." ".$dives_secur->MEM_SURNAME;
        } 

        $dives_pilot = AcnMember::find($dives -> DIV_NUM_MEMBER_PILOTING);
        if (is_null($dives_pilot)) {
            $dives_pilot = "non définit";
        } else {
            $dives_pilot = $dives_pilot->MEM_NAME." ".$dives_pilot->MEM_SURNAME;
        }

        $site = AcnSite::find($dives->DIV_NUM_SITE);
        if (is_null($site)) {
            $site = "non définit";
        } else {
            $site = $site->SIT_NAME." (".$site->SIT_DESCRIPTION.")";
        } 

        $boat = AcnBoat::find($dives->DIV_NUM_BOAT);
        if (is_null($boat)) {
            $site = "non définit";
        } else {
            $boat = $boat->BOA_NAME;
        }

        $prerogative = AcnPrerogative::find($dives->DIV_NUM_PREROG);
        if (is_null($prerogative)) {
            $prerogative = "non définit";
        } else {
            $prerogative = $prerogative->PRE_LABEL;
        }

        $period = AcnPeriod::find($dives->DIV_NUM_PERIOD);
        $dives_register = AcnDives::find($id)->divers;

        return view("divesInformation",["dives" => $dives, "dives_lead" => $dives_lead, "dives_secur" => $dives_secur, "dives_pilot" => $dives_pilot, "dives_register"=> $dives_register, 
        "prerogative" => $prerogative, "period" => $period, "site" => $site, "boat" => $boat]);
    }


    static public function register(Request $request){
        $userPriority = auth()->user()->prerogatives->max("PRE_PRIORITY");

        $dive = AcnDives::find($request->dive);
        $divePriority = $dive->prerogative->PRE_PRIORITY;

        $errors = array();

        if ($userPriority < $divePriority) {
            $errors["insufficientLevel"] = "Vous ne pouvez pas vous inscrire. Votre niveau est insuffisant.";
        }
        if ($dive->divers->count() === $dive->DIV_MAX_REGISTERED) {
            $errors["max_member_reach"] = "Il n'y a plus de place dans la plongée.";
        }

        if (!empty($errors)) return back()->withErrors($errors);

        $userId = auth()->user()->MEM_NUM_MEMBER;
        AcnRegistered::insert($userId, $request->dive);

        $user = AcnMember::find($userId);
        $user->MEM_REMAINING_DIVES = $user->MEM_REMAINING_DIVES - 1;
        $user->save();

        return redirect(route("dives"));
    }

    static public function unregister(Request $request){
        AcnRegistered::deleteData(auth()->user()->MEM_NUM_MEMBER, $request->dive);
        return redirect(route("dives"));
    }

}
