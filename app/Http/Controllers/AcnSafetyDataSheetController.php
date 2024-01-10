<?php
namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnBoat;
use Illuminate\Support\Facades\DB;

class AcnSafetyDataSheetController extends Controller {
    static public function getAll() {
        $dives = AcnDives::all();

        $pilote = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives[0]->DIV_NUM_MEMBER_PILOTING)
        ->get();

        $secure = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives[0]->DIV_NUM_MEMBER_SECURED)
        ->get();

        $lead = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives[0]->DIV_NUM_MEMBER_LEAD)
        ->get();

        $registered = DB::table('ACN_REGISTERED')
        ->selectRaw("NUM_GROUPS, count(*) as REGISTERED")
        ->groupBy('NUM_GROUPS')
        ->get();

        $boat = DB::table('ACN_BOAT')
        ->select('BOA_NAME')
        ->where('BOA_NUM_BOAT', '=', $dives[0]->DIV_NUM_BOAT)
        ->get();

        $site = DB::table('ACN_SITE')
        ->select('SIT_NAME')
        ->where('SIT_NUM_SITE', '=', $dives[0]->DIV_NUM_SITE)
        ->get();

        $period = DB::table('ACN_PERIOD')
        ->select('PER_LABEL')
        ->where('PER_NUM_PERIOD', '=', $dives[0]->DIV_NUM_PERIOD)
        ->get();

        return view('safetyDataSheet', ["dives" => $dives[0], "pilote" => $pilote[0],
        "secure" => $secure[0], "lead" => $lead[0], "registered" => $registered[0],
        "boat" => $boat[0], "site" => $site[0], "period" => $period[0]]);
    }
}

?>
