<?php

namespace App\Http\Controllers;

use App\Models\web\AcnSite;
use Illuminate\Http\Request;

class AcnSiteController extends Controller
{
    static public function delete($siteId) {
        $site = AcnSite::find($siteId);
        $site->SIT_DELETED = 1;
        $site->save();
    }

    static public function update(Request $request, $siteId) {
        $site = AcnSite::find($siteId);
        $errors = array();
        $nameAlreadyExist = AcnSite::where("SIT_NAME", "=", strtoupper($request->sit_name))->where("SIT_NUM_SITE", "!=", $siteId)->exists();
        if ($nameAlreadyExist) {
            $errors["name"] = "Le nom donné est déjà existant.";
        }
        if (strlen($request->sit_coord) > 127) {
            $errors["coord_len"] = "Les coordonnées ne doivent pas faire plus de 127 caractères.";
        }
        if (strlen($request->sit_description) > 255) {
            $errors["coord_len"] = "La description ne doit pas faire plus de 255 caractères.";
        }
        if (count($errors) != 0) return back()->withErrors($errors);
        $site->SIT_NAME = strtoupper($request->sit_name);
        $site->SIT_COORD = $request->sit_coord;
        $site->SIT_DEPTH  = $request->sit_depth;
        $site->SIT_DESCRIPTION  = $request->sit_description;
        $site->save();
        return redirect(route("managerPanel"));
    }

    static public function getSiteUpdateView($siteId) {
        $site = AcnSite::find($siteId);
        return view("manager/updateSite", ["site" => $site, 'errors' => array()]);
    }
}
