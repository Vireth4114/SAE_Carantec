<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnBoat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnBoatController extends Controller
{
    /**
     *
     * Get all the boats existing
     * @return \view with all the boats in parameter of the view
     */
    static public function getAllBoat() {
        return view ('propose_slot', ["boats" => AcnBoat::all() ]);
    }

    /**
     *
     * Get the capacity of a boat
     * @param $BoatNum the number from the specific boat
     * @return array of the capacity of the boat
     */
    static public function getBoatCapacity($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_CAPACITY')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();

        $capacity = (array) $capacity[0];
        return $capacity['BOA_CAPACITY'];
    }

    /**
     * Get the max capacity of all the boat
     * @return array the max capacity of all the boat
     */
    static public function getMaxCapacity() {
        $capacity = DB::table('ACN_BOAT')
            -> selectRaw('max(BOA_CAPACITY) as max')
            -> get();

        $capacity = (array) $capacity[0];
        return $capacity['max'];
    }

    /**
     * Get the boat's name
     * @param $BoatNum num from the specific boat
     * @return array the name of the boat
     */
    static public function getBoatName($BoatNum) {
        $capacity = DB::table('ACN_BOAT')
            -> select('BOA_NAME')
            -> where('BOA_NUM_BOAT', '=', $BoatNum)
            -> get();

        $capacity = (array) $capacity[0];
        return $capacity['BOA_NAME'];
    }

    /**
     * Create a boat
     *
     * @param Request $request the request of a boat creation
     * @return mixed redirection to the route of the panel manager
     */
    static public function create(Request $request) {
        $errors = array();
        $nameAlreadyExist = AcnBoat::where("BOA_NAME", "=", strtoupper($request->boa_name))->exists();
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if ($request->boa_capacity < 4) {
            $errors["number"] = "La capacité doit être supérieure ou égale à 4.";
        }
        if (empty($request->boa_name) || empty($request->boa_capacity)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $boat = new AcnBoat;
        $boat->BOA_NAME = strtoupper($request->boa_name);
        $boat->BOA_CAPACITY = $request->boa_capacity;
        $boat->save();
        return redirect(route("managerPanel"));
    }

    /**
     * Delete a boat
     *
     * @param  $boatId the identification of the boat
     * @return void
     */
    static public function delete($boatId) {
        $boat = AcnBoat::find($boatId);
        $boat->BOA_DELETED = 1;
        $boat->save();
    }

    /**
     * Update boat's informations
     *
     * @param Request $request the request of a boat's update
     * @param  $boatId the identification of the boat
     * @return mixed
     */
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
        if (empty($request->boa_name) || empty($request->boa_capacity)) {
            $errors["empty_entry"] = "Tous les champs doivent êtres remplis.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $boat->BOA_NAME = strtoupper($request->boa_name);
        $boat->BOA_CAPACITY = $request->boa_capacity;
        $boat->save();
        return redirect(route("managerPanel"));
    }

    /**
     * Get the view of the updating boat
     *
     * @param $boatId the identification of the boat
     * @return \view with the new boats inserted
     */
    static public function getBoatUpdateView($boatId) {
        $boat = AcnBoat::find($boatId);
        return view("manager/updateBoat", ["boat" => $boat]);
    }
}
