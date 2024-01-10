<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnMemberController extends Controller
{
    public static function getMember($numMember) {
        return AcnMember::find($numMember);
    }

    public static function updateRolesMember(Request $request, $numMember) {
        $member = AcnMember::find($numMember);
        
    }
}
