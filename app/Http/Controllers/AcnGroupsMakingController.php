<?php

namespace App\Http\Controllers;

use App\Models\web\AcnGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnGroupsMakingController extends Controller
{
    static public function getAll() {

        $groups = DB::table('ACN_REGISTERED')
        -> select('NUM_GROUPS')
        -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
        -> where('NUM_DIVE','=','1')
        -> orderBy('NUM_GROUPS')
        -> get();


        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $members = array();

        $dive = 1;

        foreach ($groups as $group) {
            $member = DB::table('ACN_MEMBER')
            -> select('ACN_MEMBER.MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME', 'PRE_LEVEL')
            -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
            -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
            -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('NUM_DIVE','=',$dive)
            -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
            -> get();

            $maxnum = DB::table('ACN_MEMBER')
            -> select(DB::raw('max(PRE_PRIORITY) as MIN_PRIORITY'))
            -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
            -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
            -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('NUM_DIVE','=',$dive)
            -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
            -> get();
            $maxnum = $maxnum[0]->MIN_PRIORITY == 1 ? 2 : 3;
            $members[$group->NUM_GROUPS] = [$member, $maxnum];
        }
        return view ('groupsMaking', ["members" => $members, "dive" => $dive]);
    }

    static public function add(Request $request) {
        DB::update('update ACN_REGISTERED set NUM_GROUPS='.$request->group.' where NUM_DIVE='.$request->dive.' and NUM_MEMBER='.$request->member);
        return AcnGroupsMakingController::getAll();
    }

    static public function add_group() {
        return AcnGroupsMakingController::getAll();
    }

    static public function remove_member(Request $request) {
        DB::update('update ACN_REGISTERED set NUM_GROUPS=null where NUM_DIVE='.$request->dive.' and NUM_MEMBER='.$request->member);
        return AcnGroupsMakingController::getAll();
    }
}
