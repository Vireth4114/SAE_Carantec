<?php

namespace App\Http\Controllers;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnBoatController extends Controller
{
    static public function getAllBoat() {
        return view ('propose_slot', ["boats" => AcnBoat::all() ]);
    }

    static public function getBoatCapacity($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_CAPACITY')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['BOA_CAPACITY'];
    }

    static public function getMaxCapacity() {
        $capacity = DB::table('ACN_BOAT')
            -> selectRaw('max(BOA_CAPACITY) as max')
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['max'];
    }

    static public function getBoatName($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_NAME')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();
        
        $capacity = (array) $capacity[0];
        return $capacity['BOA_NAME'];
    }

    static public function create(Request $request) {
        //return AcnBoatController;
    }

    static public function delete($boatId) {
        $boat = AcnBoat::find($boatId);
        $boat->BOA_DELETED = 1;
        $boat->save();
    }

    static public function update(Request $request, $boatId) {
        $boat = AcnBoat::find($boatId);
        $errors = array();
        $nameAlreadyExist = AcnBoat::where("BOA_NAME", "=", strtoupper($request->boa_name))->where("BOA_NUM_BOAT", "!=", $boatId)->exists();
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if ($request->boa_capacity < 4) {
            $errors["number"] = "La capacité doit être supérieure ou égale à 4.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $boat->BOA_NAME = $request->boa_name;
        $boat->BOA_CAPACITY = $request->boa_capacity;
        $boat->save();
        return redirect(route("managerPanel"));
    }

    static public function getBoatUpdateView($boatId) {
        $boat = AcnBoat::find($boatId);
        return view("manager/updateBoat", ["boat" => $boat]);
    }
}
