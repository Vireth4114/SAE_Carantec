<?php
namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnGroups;
use Illuminate\Support\Facades\DB;

class AcnSafetyDataSheetController extends Controller {
    static public function getAll() {
        $dives = $dive = AcnDives::all();
        $palanquing = $palanq = AcnGroups::all();
        $register = DB::table('ACN_REGISTERED')->get();

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
        ->where('NUM_GROUPS', '=', 2)
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
        ->select('PER_START_TIME', 'PER_END_TIME')
        ->where('PER_NUM_PERIOD', '=', $dives[0]->DIV_NUM_PERIOD)
        ->get();

        $members = DB::table('ACN_REGISTERED')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->join('ACN_MEMBER', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
        ->join('ACN_GROUPS', 'ACN_GROUPS.GRP_NUM_GROUPS', '=', 'ACN_REGISTERED.NUM_GROUPS')
        ->where('ACN_REGISTERED.NUM_GROUPS', '=', 2)
        ->get();

        return view('safetyDataSheet', ["dive"=> $dive, "dives" => $dives[0], "pilote" => $pilote[0],
        "secure" => $secure[0], "lead" => $lead[0], "registered" => $registered[0], "register" => $register,
        "boat" => $boat[0], "site" => $site[0], "period" => $period[0], "palanquing" => $palanquing[0], "palanq" => $palanq,
        "members" => $members]);
    }

    static public function getSafetySheetGroups($groupNum) {

        $numDiveGroup = DB::table('ACN_REGISTERED')->select('NUM_DIVE')->distinct()->where('NUM_GROUPS', '=', $groupNum)->get();
        $dives = AcnDives::all()->whereIn('DIV_NUM_DIVE', $numDiveGroup);
        $palanquing = AcnGroups::all()->where('GRP_NUM_GROUPS', '=', $groupNum);   

        /*$pilote = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives->DIV_NUM_MEMBER_PILOTING)
        ->get();

        $secure = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives->DIV_NUM_MEMBER_SECURED)
        ->get();

        $lead = DB::table('ACN_MEMBER')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dives->DIV_NUM_MEMBER_LEAD)
        ->get();

        $registered = DB::table('ACN_REGISTERED')
        ->selectRaw("NUM_GROUPS, count(*) as REGISTERED")
        ->where('NUM_GROUPS', '=', $groupNum)
        ->groupBy('NUM_GROUPS')
        ->get();

        $boat = DB::table('ACN_BOAT')
        ->select('BOA_NAME')
        ->where('BOA_NUM_BOAT', '=', $dives->DIV_NUM_BOAT)
        ->get();

        $site = DB::table('ACN_SITE')
        ->select('SIT_NAME')
        ->where('SIT_NUM_SITE', '=', $dives->DIV_NUM_SITE)
        ->get();

        $period = DB::table('ACN_PERIOD')
        ->select('PER_START_TIME', 'PER_END_TIME')
        ->where('PER_NUM_PERIOD', '=', $dives->DIV_NUM_PERIOD)
        ->get();
*/
        $members = DB::table('ACN_REGISTERED')
        ->select('MEM_NAME', 'MEM_SURNAME')
        ->join('ACN_MEMBER', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
        ->join('ACN_GROUPS', 'ACN_GROUPS.GRP_NUM_GROUPS', '=', 'ACN_REGISTERED.NUM_GROUPS')
        ->where('ACN_REGISTERED.NUM_GROUPS', '=', $groupNum)
        ->get();

        return view('test', ["dives" => $dives, /*"pilote" => $pilote,
        "secure" => $secure, "lead" => $lead, "registered" => $registered,
        "boat" => $boat, "site" => $site, "period" => $period,*/ "palanquing" => $palanquing,
        "members" => $members, "numDiveGroup" => $numDiveGroup]);
    }
}

?>
