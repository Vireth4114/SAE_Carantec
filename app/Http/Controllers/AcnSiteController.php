<?php

namespace App\Http\Controllers;

use App\Models\web\AcnSite;

class AcnSiteController extends Controller
{
    static public function delete($siteId) {
        $site = AcnSite::find($siteId);
        $site->SIT_DELETED = 1;
        $site->save();
    }

    static public function update($siteId) {
        $site = AcnSite::find($siteId);
        $site->SIT_DELETED = 1;
        $site->save();
    }

    static public function getSiteUpdateView($siteId) {
        $site = AcnSite::find($siteId);
        return view("manager/updateBoat");
    }
}
