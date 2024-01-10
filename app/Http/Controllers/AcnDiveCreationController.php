<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use App\Models\web\AcnSite;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnMember;
use App\Models\web\AcnFunction;
use App\Models\web\AcnPrerogative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnBoatController;
use Carbon\Carbon;

class AcnDiveCreationController extends Controller
{
    static public function getAll() {
        $boats = AcnBoat::all();
        $sites = AcnSite::all();
        $periods = AcnPeriod::all();
        $prerogatives = DB::table('ACN_PREROGATIVE') -> where('PRE_LEVEL', 'not like', 'E%') -> get();
        
        $leads = AcnMember::getAllLeader();

        $pilots = AcnMember::getAllPilots();

        $securitys = AcnMember::getAllSecurity();
        return view ('diveCreation', ["boats" => $boats, "sites" => $sites, "periods" => $periods, "prerogatives" => $prerogatives, "leads" => $leads,"pilots" => $pilots, "securitys" => $securitys]);
    }

    static public function create(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        //check if a dive already exists at the date and period
        $exist = AcnDivesController::existDive($request -> date, $request -> period);
        if ($exist) {
            //if it exists sets the error variable to true and add a message to the error message
            $err = true;
            $periodName = AcnPeriodController::getPeriodName($request -> period);
            $strErr .= "- Une plongée existe déjà le ".Carbon::parse($request -> date)->locale('fr_FR')->translatedFormat('l j F Y')." à ce moment "."(".$periodName.").<br>";
        }

        //if the boat exists, check if the max_divers is lower than the capacity -3 (-3 for the pilot, the surface security and the dive's diector)
        if (!is_null($request->boat)) {
            $capacity = AcnBoatController::getBoatCapacity($request->boat)-3;
            //if the max_divers is not specified, it is set at the maximum of divers the boat can carry
            if ($request -> max_divers == 0) {
                $request -> max_divers = AcnBoatController::getBoatCapacity($request -> boat)-3;
            }
            else if ($capacity < ($request -> max_divers) ) {
                //if the max_divers is superior to the capacity, sets the erro variable to true and add a message to the error message
                $boatName = AcnBoatController::getBoatName($request -> boat);
                $err = true;
                $strErr .= "- Le nombre de nageurs maximal renseigné est supérieur à la capacité <strong>en plongeur</strong> du bateau ".$boatName." (".$capacity." plongeur max).<br>";
            }
        }
        else {
            //If the boat isn't specified, the capacity of divers of the biggest boat is retrieved
            $capacity = AcnBoatController::getMaxCapacity()-3;
            //if the max_divers is not specified, it is set at the maximum of divers the biggest boat can carry
            if ($request -> max_divers == 0) {
                $request -> max_divers = $capacity;
            }
            //If it is specified, check if the max_divers is lower than the capacity of the biggest boat
            else if ($capacity < $request -> max_divers) {
                $err = true;
                $strErr .= "- Le nombre de nageurs maximal renseigné est supérieur à la capacité <strong>en plongeur</strong> du bateau le plus gros (".$capacity.").<br>";
            }
        }

        //Check if the min_divers is lower than the max_divers
        if ($request -> min_divers > $request -> max_divers) {
            $err = true;
            $strErr .="- Le nombe minimum de nageurs ne peut être supérieur au nombre maximum.<br>";
        }

        //Checks if the leader, the pilot and the surface security are different person.
        if (!is_null($request -> lead) && !is_null($request -> pilot) && ($request -> lead == $request -> pilot) ) {
            $member = AcnMemberController::getMember($request -> lead);
            $err = true;
            $strErr .= "- Le directeur de plongée et le pilote ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        }
        if (!is_null($request -> lead) && !is_null($request -> security) && ($request -> lead == $request -> security) ) {
            $member = AcnMemberController::getMember($request -> lead);
            $err = true;
            $strErr .= "- Le directeur de plongée et la sécurié de surface ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        }
        if (!is_null($request -> pilot) && !is_null($request -> security) && ($request -> pilot == $request -> security) ) {
            $member = AcnMemberController::getMember($request -> pilot);
            $err = true;
            $strErr .= "- La sécurié de surface et le pilote ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        }

        $max = AcnDivesController::getNumMax();

        if ($err) {
            echo $strErr;
        }
        else {
            DB::table('ACN_DIVES')->insert([
                'DIV_DATE' => DB::raw("str_to_date('".$request -> date."','%Y-%m-%d')"),
                'DIV_NUM_PERIOD' => $request -> period,
                'DIV_NUM_SITE' => $request -> site,
                'DIV_NUM_BOAT' => $request -> boat,
                'DIV_NUM_PREROG' => $request -> lvl_required,
                'DIV_NUM_MEMBER_LEAD' => $request -> lead,
                'DIV_NUM_MEMBER_PILOTING' => $request -> pilot,
                'DIV_NUM_MEMBER_SECURED'=> $request -> security,
                'DIV_MIN_REGISTERED' => $request -> min_divers,
                'DIV_MAX_REGISTERED'=> $request -> max_divers,
            ]);
        }

    }
}
