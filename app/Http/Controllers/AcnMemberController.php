<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnMemberController extends Controller
{
    public static function getMember($memberNum) {
        return AcnMember::find($memberNum);
    }

    public static function updateRolesMember(Request $request, $memberNum) {
        $checkboxFields = array("security", "secretary", "pilot");
        $fieldsMappingToNameInDatabase = array("security" => "Sécurité de surface",
                                                "secretary" => "Secrétaire",
                                                "pilot" => "Pilote");
        foreach($checkboxFields as $field) {
            $nameInDatabase = $fieldsMappingToNameInDatabase[$field];
            if ($request->exists($field)) {
                AcnMember::createUserRole($memberNum, $nameInDatabase);
            } else {
                AcnMember::deleteUserRole($memberNum, $nameInDatabase);
            }
        }
        return redirect(route("managerPanel"));
    }
}
