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

            $priorities = DB::table('ACN_MEMBER')
            -> select(DB::raw('min(PRE_PRIORITY) as MIN_PRIORITY'), DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
            -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
            -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
            -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('NUM_DIVE','=',$dive)
            -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
            -> get();
            $minnum = $priorities[0]->MIN_PRIORITY;
            $maxnum = $priorities[0]->MAX_PRIORITY;

            $members[$group->NUM_GROUPS] = [$member, $minnum, $maxnum];
        }
        return view ('groupsMaking', ["members" => $members, "dive" => $dive]);
    }

    static public function add(Request $request) {
        $testIn = DB::table('ACN_REGISTERED')
        -> select(DB::raw('count(*) as NUM_ADD'))
        -> where('NUM_DIVE', '=', $request->dive)
        -> where('NUM_MEMBER', '=', $request->member)
        -> get();
        if ($testIn[0]->NUM_ADD == 0) {
            DB::table('ACN_REGISTERED')->insert([
                'NUM_MEMBER' => $request -> member,
                'NUM_DIVE'=> $request -> dive,
            ]);
        }

        DB::update('update ACN_REGISTERED set NUM_GROUPS='.$request->group.' where NUM_DIVE='.$request->dive.' and NUM_MEMBER='.$request->member);

        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $priorities = DB::table('ACN_MEMBER')
        -> select(DB::raw('min(PRE_PRIORITY) as MIN_PRIORITY'), DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
        -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
        -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> where('NUM_DIVE','=',$request->dive)
        -> where('NUM_GROUPS','=', $request->group)
        -> get();
        $minnum = $priorities[0]->MIN_PRIORITY;
        $maxnum = $priorities[0]->MAX_PRIORITY;

        $members = DB::table('ACN_MEMBER')
        -> select('ACN_MEMBER.MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME', 'PRE_LEVEL')
        -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> where('PRIORITY_TABLE.MAX_PRIORITY','>=', 13)
        -> get();

        if ($minnum <= 4 && $maxnum < 13) {
            return view ('addSupervisor', ["members" => $members, "dive" => $request->dive, "group" => $request->group]);
        }

        return AcnGroupsMakingController::getAll();
    }

    static public function addGroup(Request $request) {
        DB::insert('insert into ACN_GROUPS values ()');
        $max = DB::table('ACN_GROUPS')
            -> selectRaw('max(GRP_NUM_GROUPS) as maxi')
            -> get();

        $max = ((array) $max[0])['maxi'];

        $request["group"] = $max;
        return AcnGroupsMakingController::add($request);
    }

    static public function removeMember(Request $request) {
        DB::update('update ACN_REGISTERED set NUM_GROUPS=null where NUM_DIVE='.$request->dive.' and NUM_MEMBER='.$request->member);
        return AcnGroupsMakingController::getAll();
    }
}
