<?php

namespace  App\Http\Controllers;

use App\Models\web\AcnMember;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcnMemberController extends Controller
{
    static public function getAllMembers() {
        return view("home", ["members" => AcnMember::all()]);
    }
}
