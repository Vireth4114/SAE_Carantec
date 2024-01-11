<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use App\Models\web\AcnSite;
use App\Models\web\AcnPeriod;
use App\Models\web\AcnMember;
use App\Models\web\AcnFunction;
use App\Models\web\AcnPrerogative;
use App\Models\web\AcnDives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AcnDivesController;
use App\Http\Controllers\AcnBoatController;
use Carbon\Carbon;
use League\CommonMark\Extension\Attributes\Node\Attributes;

class AcnDiveModifyController extends Controller
{
    /**
     * Get all the dive's informations
     *
     * @param $diveId the identification of the dive
     * @return mixed the datas of a dive to modify
     */
    static public function getAll($diveId) {

        $dive = AcnDives::getDive($diveId);
        $period = AcnPeriod::getPeriod($dive[0]->DIV_NUM_PERIOD);
        $site = AcnSite::getSite($dive[0]->DIV_NUM_SITE);
        if($site->isEmpty()){
            $site = "";
        }else{
            $site = $site[0]->SIT_NAME;
        }
        $boat = AcnBoat::getBoat($dive[0]->DIV_NUM_BOAT);
        if($boat->isEmpty()){
            $boat = "";
        }else{
            $boat = $boat[0]->BOA_NAME;
        }
        $prerogative = AcnPrerogative::getPrerogative($dive[0]->DIV_NUM_PREROG);
        if($prerogative->isEmpty()){
            $prerogative = "";
        }else{
            $prerogative = $prerogative[0]->PRE_LEVEL;
        }
        $lead = AcnMember::getMember($dive[0]->DIV_NUM_MEMBER_LEAD);
        if($lead->isEmpty()){
            $lead = "";
        }else{
            $lead = $lead[0]->MEM_NAME.$lead[0]->MEM_SURNAME;
        }
        $pilot = AcnMember::getMember($dive[0]->DIV_NUM_MEMBER_PILOTING);
        if($pilot->isEmpty()){
            $pilot = "";
        }else{
            $pilot = $pilot[0]->MEM_NAME.$pilot[0]->MEM_SURNAME;
        }
        $security = AcnMember::getMember($dive[0]->DIV_NUM_MEMBER_SECURED);
        if($security->isEmpty()){
            $security = "";
        }else{
            $security = $security[0]->MEM_NAME.$security[0]->MEM_SURNAME;
        }


        $isDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);
        $boats = AcnBoat::all();
        $sites = AcnSite::all();
        $prerogatives = DB::table('ACN_PREROGATIVE') -> where('PRE_LEVEL', 'not like', 'E%') -> get();
        $leads = AcnMember::getAllLeader();
        $pilots = AcnMember::getAllPilots();
        $securitys = AcnMember::getAllSecurity();
        return view ('diveModify', ["boats" => $boats, "sites" => $sites, "prerogatives" => $prerogatives,
         "leads" => $leads,"period"=> $period[0],
         "pilots" => $pilots, "securitys" => $securitys,
         "dive" => $dive[0], "site" => $site,
         "boat" => $boat, "prerogative" => $prerogative,
        "lead" => $lead, "pilot" => $pilot,
        "security" => $security,
        'isDirector' => $isDirector]);
    }

    /**
     * Modifying dive's informations
     * @param Request $request the request for modifying a boat
     *
     */
    static public function modify(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";


        //check if the level has been changed from an adult dive to a child dive, if there are members that can't participate, the modifications are cancelled
        $dive = AcnDives::find($request -> numDive);
        if (AcnPrerogative::find($dive -> DIV_NUM_PREROG)->PRE_PRIORITY < 5 && $request -> lvl_required >=5) {
            foreach($dive->divers as $member) {
                if (AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBE) < 5) {
                    $err=true;
                }
            }
            if ($err) {
                $strErr .= "- Vous avez passé le niveau de la plongée de niveau enfant à adulte mais des enfants en font partie.<br>";
            }
        }
        //check if the level has been changed from a child dive to an adult dive, if there are members that can't participate, the modifications are cancelled
        else if (AcnPrerogative::find($dive -> DIV_NUM_PREROG)->PRE_PRIORITY > 4 && $request -> lvl_required <= 4 ) {
            foreach($dive->divers as $member) {
                $memPri = AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBE);
                if ($request -> lvl_required <= 2) {
                    if ($memPri > 5 && $memPri < 13 ) {
                        $err=true;
                    }
                }
                else {
                    if ($memPri > 5 && $memPri < 14 ) {
                        $err=true;
                    }
                }
            }
            if ($err) {
                $strErr .= "- Vous avez passé le niveau de la plongée de niveau adulte à enfant mais des adultes non formateurs en font partie.<br>";
            }
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

        $dive=AcnDives::getDive($request -> numDive);

        if ($dive[0] -> DIV_MIN_REGISTERED < $request -> min_divers) {
            $err = true;
            $strErr .= "-L'effectif minimum ne peut qu'être diminué<br>";
        }

        if ($dive[0] -> DIV_MAX_REGISTERED > $request -> max_divers) {
            $err = true;
            $strErr .= "-L'effectif maximum ne peut qu'être augmenté<br>";
        }

        $divers_lvl = AcnDives::find($request -> numDive)->divers;
        $minAllDivers = AcnPrerogative::all()->max('PRE_PRIORITY');
        foreach($divers_lvl as $diver) {
            $maxLocalDiver = $diver->prerogatives->max("PRE_PRIORITY");
            if ($maxLocalDiver < $minAllDivers) {
                $minAllDivers = $maxLocalDiver;
            }
        }
        if($minAllDivers < AcnPrerogative::find($request -> lvl_required)->PRE_PRIORITY) {
            $err = true;
            $strErr .= "-Le niveau saisi est trop élevé <br>";
        }

        if ($err) {
            echo $strErr;
        }
        else {

            AcnDives::updateData($request -> numDive, $request -> site, $request -> boat, $request -> lvl_required, $request -> lead, $request -> pilot, $request -> security, $request -> min_divers, $request -> max_divers);

            if(AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER)){
                return redirect(route('dives'));
            }else if(AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER)){
                return redirect(route('diveInformation',['diveId'=>$request -> numDive]));
            }
        }

    }
}
