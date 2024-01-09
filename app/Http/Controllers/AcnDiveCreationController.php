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
            ->select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
            ->distinct()
            ->join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_FUNCTION')
            ->where ('NUM_FUNCTION','=','3')
            -> where('MEM_STATUS','=','1')
            ->get();

        $securitys = DB::table('ACN_MEMBER')
        ->select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        ->distinct()
        ->join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_FUNCTION')
        ->where ('NUM_FUNCTION','=','2')
        -> where('MEM_STATUS','=','1')
        ->get();

        return view ('propose_slot', ["boats" => $boats, "sites" => $sites, "periods" => $periods, "prerogatives" => $prerogatives, "leads" => $leads,"pilots" => $pilots, "securitys" => $securitys]);
    }
}
