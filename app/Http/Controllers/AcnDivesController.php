<?php

namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LDAP\Result;

class AcnDivesController extends Controller
{
    public static function getDivesValues() {
        $months = DB::table('ACN_DIVES')
        ->selectRaw("DISTINCT date_format(DIV_DATE, '%m') as mois_nb, date_format(div_date,'%M') as mois_mot")
        ->orderBy('mois_nb')
        ->get();

        $dives = array();
        foreach ($months as $month) {
            $dive = DB::table("ACN_DIVES")
            ->join("ACN_PERIOD","PER_NUM_PERIOD","DIV_NUM_PERIOD")
            ->join("ACN_SITE","SIT_NUM_SITE","DIV_NUM_SITE")
            ->join("ACN_PREROGATIVE","PRE_NUM_PREROG","DIV_NUM_PREROG")
            ->whereRaw("date_format(DIV_DATE, '%m') = ?", $month->mois_nb)
            ->get();
            $dives[$month->mois_mot] = $dive;
        }
        return view("displayDives",["dives" => $dives, "months" => $months]);
    }

    static public function getNumMax() {
        return DB::table('ACN_DIVES')
            -> selectRaw('max(DIV_NUM_DIVE)+1 as maxi')
            -> get();
    }

    public static function getAllDiveInformation($id){
        $dives = DB::table("ACN_DIVES")
        ->join("ACN_PERIOD","PER_NUM_PERIOD","=","DIV_NUM_PERIOD")
        ->join("ACN_SITE","SIT_NUM_SITE","=","DIV_NUM_SITE")
        ->join("ACN_PREROGATIVE","PRE_NUM_PREROG","=","DIV_NUM_PREROG")
        ->join("ACN_BOAT","BOA_NUM_BOAT","=","DIV_NUM_BOAT")
        ->join("ACN_MEMBER","MEM_NUM_MEMBER","=","DIV_NUM_MEMBER_LEAD")
        ->whereRaw("DIV_NUM_DIVE = ?",$id)
        ->get();

        $dives_secur =  DB::table("ACN_DIVES")
        ->join("ACN_MEMBER","MEM_NUM_MEMBER","=","DIV_NUM_MEMBER_SECURED")
        ->whereRaw("DIV_NUM_DIVE = ?",$id)
        ->get();

        $dives_pilot =  DB::table("ACN_DIVES")
        ->join("ACN_MEMBER","MEM_NUM_MEMBER","=","DIV_NUM_MEMBER_PILOTING")
        ->whereRaw("DIV_NUM_DIVE = ?",$id)
        ->get();

        $dives_register =  DB::table("ACN_MEMBER")
        ->join("ACN_REGISTERED","MEM_NUM_MEMBER","=","NUM_MEMBER")
        ->join("ACN_DIVES","DIV_NUM_DIVE","=","NUM_DIVE")
        ->whereRaw("DIV_NUM_DIVE = ?",$id)
        ->get();

        return view("divesInformation",["dives" => $dives, "dives_secur" => $dives_secur, "dives_pilot" => $dives_pilot, "dives_register"=> $dives_register]);
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
        DB::table('ACN_REGISTERED')
        ->insert([
            'NUM_DIVE' => $request->dive,
            'NUM_MEMBER' => $userId,
        ]);
        $user = AcnMember::find($userId);
        $user->MEM_REMAINING_DIVES = $user->MEM_REMAINING_DIVES - 1;
        $user->save();
        return redirect(route("dives"));
    }

    static public function unregister(Request $request){
        DB::table('ACN_REGISTERED')
        ->where("NUM_DIVE", "=", $request->dive)
        ->where("NUM_MEMBER", "=", auth()->user()->MEM_NUM_MEMBER)
        ->delete();
        return redirect(route("dives"));
    }

}
