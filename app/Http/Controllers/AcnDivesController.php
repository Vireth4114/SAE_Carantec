<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnDivesController extends Controller
{
    public static function getAllDivesValues() {
        $months = DB::table('ACN_DIVES')
        ->selectRaw("DISTINCT date_format(DIV_DATE, '%m') as mois_nb, date_format(div_date,'%M') as mois_mot")
        ->orderBy('mois_nb')
        ->get();

        $dives = array();
        foreach ($months as $month) {
            $dive = DB::table("ACN_DIVES")
            ->join("ACN_PERIOD","PER_NUM_PERIOD","DIV_NUM_PERIOD")
            ->join("ACN_SITE","SIT_NUM_SITE","DIV_NUM_SITE")
            ->whereRaw("date_format(DIV_DATE, '%m') = ?", $month->mois_nb)
            ->get();
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

}
