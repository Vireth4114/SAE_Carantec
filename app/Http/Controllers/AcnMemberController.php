<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AcnMemberController extends Controller
{
    static public function passMembers() {
        $members = AcnMember::orderby("MEM_NUM_MEMBER")->get();
        $passwords = array();
        foreach ($members as $member) {
            array_push($passwords, Hash::make($member->MEM_PASSWORD));
        }
        return view("vite", ["passwords" => $passwords]);
    }
}
