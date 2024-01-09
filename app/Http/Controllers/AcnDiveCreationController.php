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

        $leads = DB::table('ACN_MEMBER')
            -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
            -> distinct()
            -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('PRE_PRIORITY', '>', '12')
            -> where('MEM_STATUS','=','1')
            -> get();

        $pilots = DB::table('ACN_MEMBER')
            -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
            -> distinct()
            -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
            -> where ('NUM_FUNCTION','=','3')
            -> where('MEM_STATUS','=','1')
            -> get();

        $securitys = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        -> distinct()
        -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
        -> where ('NUM_FUNCTION','=','2')
        -> where('MEM_STATUS','=','1')
        -> get();

        return view ('suggestSlot', ["boats" => $boats, "sites" => $sites, "periods" => $periods, "prerogatives" => $prerogatives, "leads" => $leads,"pilots" => $pilots, "securitys" => $securitys]);
    }

    static public function create(Request $request) {

        
        $err = false;
        $strErr = "";

        $exist = AcnDivesController::existDive($request -> date, $request -> period);
        
        if ($exist) {
            $err = true;
            $periodName = AcnPeriodController::getPeriodName($request -> period);
            $strErr .= "- Une plongée existe déjà le ".Carbon::parse($request -> date)->locale('fr_FR')->translatedFormat('l j F Y')." à ce moment "."(".$periodName.")<br>";
            echo $strErr;
        }

        if (!is_null($request->boat)) {
            $capacity = AcnBoatController::getBoatCapacity($request->boat);
            if ($capacity < ($request -> max_divers) ) {
                $err = true;
            }
        }
        else {
            //TO DO : Retrieve the max capacity of the 
            //if ($request -> max_divers == 0) $request -> max_divers = $capacity;
        }
        if ($request -> min_divers > $request -> max_divers) $err = true;

        if (!is_null($request -> lead) && !is_null($request -> pilot) && ($request -> lead == $request -> pilot) ) {
            echo "egal"; 
            $err = true;          
        }
        if (!is_null($request -> lead) && !is_null($request -> security) && ($request -> lead == $request -> security) ) {
            $err = true;             
        }
        if (!is_null($request -> pilot) && !is_null($request -> security) && ($request -> pilot == $request -> security) ) {
            $err = true;   
        }
        


        echo $err;

        $max = AcnDivesController::getNumMax();

        /*
        DB::table('ACN_DIVES')->insert([
            'DIV_NUM_DIVE' => $max,
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
        */
    }
}
