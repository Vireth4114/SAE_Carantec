<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnGroupsMakingController extends Controller
{
    static public function automatic() {
        $dive = 1;
        DB::update('update ACN_REGISTERED set NUM_GROUPS=null where NUM_DIVE='.$dive);

        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $members = DB::table('ACN_REGISTERED')
        -> select('NUM_MEMBER')
        -> where('NUM_DIVE', '=', $dive);

        $supervisors = DB::table('ACN_MEMBER')
        -> select('ACN_MEMBER.MEM_NUM_MEMBER')
        -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> where('PRIORITY_TABLE.MAX_PRIORITY','>=', 13)
        -> whereNotIn('ACN_MEMBER.MEM_NUM_MEMBER', $members)
        -> get()
        -> toArray();

        $superIn = DB::table('ACN_MEMBER')
        -> select('ACN_MEMBER.MEM_NUM_MEMBER as MEM_NUM_MEMBER')
        -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> where('PRIORITY_TABLE.MAX_PRIORITY','>=', 13)
        -> whereIn('ACN_MEMBER.MEM_NUM_MEMBER', $members);


        $superIn2 = $superIn -> get() -> toArray();

        DB::table('ACN_REGISTERED')
        -> joinSub($superIn, 'SUPER_IN', 'SUPER_IN.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
        -> where('NUM_DIVE', '=', $dive)
        -> delete();


        $supervisors = array_merge($supervisors, $superIn2);

        $members = $members->get()->toArray();
        shuffle($members);

        while ($members) {
            $member = array_shift($members)->NUM_MEMBER;
            $rank = DB::table('ACN_MEMBER')
            -> select('MAX_PRIORITY')
            -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
            -> where ('ACN_MEMBER.MEM_NUM_MEMBER', '=', $member)
            -> get()[0]->MAX_PRIORITY;

            $groups = DB::table('ACN_REGISTERED')
            -> select('NUM_GROUPS')
            -> distinct()
            -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
            -> where('NUM_DIVE','=',$dive)
            -> orderBy('NUM_GROUPS')
            -> get();

            $added = false;
            foreach ($groups as $group) {
                if ($group->NUM_GROUPS != null) {
                    $count_member = DB::table('ACN_REGISTERED')
                    -> select(DB::raw('count(*) as COUNT_MEMBER'))
                    -> where('NUM_DIVE','=',$dive)
                    -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
                    -> get()[0]->COUNT_MEMBER;

                    $count_superv = DB::table('ACN_REGISTERED')
                    -> select(DB::raw('count(*) as COUNT_SUPERV'))
                    -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
                    -> joinSub($priorityTable, 'PRIORITY_TABLE', 'NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
                    -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
                    -> where('NUM_DIVE','=',$dive)
                    -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
                    -> where('MAX_PRIORITY','>=',13)
                    -> get()[0]->COUNT_SUPERV;

                    $minnum = DB::table('ACN_MEMBER')
                    -> select(DB::raw('min(PRE_PRIORITY) as MIN_PRIORITY'))
                    -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
                    -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
                    -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
                    -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
                    -> where('NUM_DIVE','=',$dive)
                    -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
                    -> get()[0]->MIN_PRIORITY;

                    $maxcount = $minnum == 1 ? 2 : 3;


                    if ($maxcount > $count_member) {
                        if ($rank <= 4 || $rank == 5 || $rank == 7 || $rank == 10) {
                            if ($count_superv != 0 && ($rank != 1 || $count_member == 1)) {
                                DB::update('update ACN_REGISTERED set NUM_GROUPS='.$group->NUM_GROUPS.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$member);
                                $added = true;
                                break;
                            } elseif ($maxcount-1 > $count_member && $rank != 1) {
                                DB::update('update ACN_REGISTERED set NUM_GROUPS='.$group->NUM_GROUPS.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$member);
                                if (sizeof($supervisors) == 0) {
                                    return AcnGroupsMakingController::getAll("Il y a eu des problèmes");
                                }
                                $supervisor = array_pop($supervisors)->MEM_NUM_MEMBER;
                                DB::table('ACN_REGISTERED')->insert([
                                    'NUM_MEMBER' => $supervisor,
                                    'NUM_DIVE'=> $dive
                                ]);
                                DB::update('update ACN_REGISTERED set NUM_GROUPS='.$group->NUM_GROUPS.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$supervisor);
                                $added = true;
                                break;
                            }
                        } else {
                            DB::update('update ACN_REGISTERED set NUM_GROUPS='.$group->NUM_GROUPS.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$member);
                            $added = true;
                            break;
                        }
                    }
                }
            }

            if (!$added) {
                DB::insert('insert into ACN_GROUPS values ()');
                $max = DB::table('ACN_GROUPS')
                -> selectRaw('max(GRP_NUM_GROUPS) as maxi')
                -> get();

                $max = ((array) $max[0])['maxi'];
                DB::update('update ACN_REGISTERED set NUM_GROUPS='.$max.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$member);
                if ($rank <= 4 || $rank == 5 || $rank == 7 || $rank == 10) {
                    if (sizeof($supervisors) == 0) {
                        return AcnGroupsMakingController::getAll("Il y a eu des problèmes");
                    }
                    $supervisor = array_pop($supervisors)->MEM_NUM_MEMBER;
                    DB::table('ACN_REGISTERED')->insert([
                        'NUM_MEMBER' => $supervisor,
                        'NUM_DIVE'=> $dive
                    ]);
                    DB::update('update ACN_REGISTERED set NUM_GROUPS='.$max.' where NUM_DIVE='.$dive.' and NUM_MEMBER='.$supervisor);
                }
            }
        }
        return AcnGroupsMakingController::getAllAutomatics();
    }

    static public function getAllAutomatics() {
        $dive = 1;

        $groups = DB::table('ACN_REGISTERED')
        -> select('NUM_GROUPS')
        -> distinct()
        -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
        -> where('NUM_DIVE','=',$dive)
        -> orderBy('NUM_GROUPS')
        -> get();

        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $members = array();

        foreach ($groups as $group) {
            $member = DB::table('ACN_MEMBER')
            -> select('ACN_MEMBER.MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME', 'PRE_LABEL')
            -> join('ACN_REGISTERED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_REGISTERED.NUM_MEMBER')
            -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
            -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('NUM_DIVE','=',$dive)
            -> where('NUM_GROUPS','=', $group->NUM_GROUPS)
            -> get();

            $members[$group->NUM_GROUPS] = $member;
        }
        return view ('automaticGroups', ["members" => $members]);
    }

    static public function getAll($message) {
        $dive = 1;

        $groups = DB::table('ACN_REGISTERED')
        -> select('NUM_GROUPS')
        -> distinct()
        -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
        -> where('NUM_DIVE','=',$dive)
        -> orderBy('NUM_GROUPS')
        -> get();

        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $superInDive = DB::table('ACN_REGISTERED')
        -> select('NUM_MEMBER')
        -> where('NUM_DIVE', '=', $dive);

        $supervisors = DB::table('ACN_MEMBER')
        -> select('ACN_MEMBER.MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME', 'PRE_LABEL')
        -> joinSub($priorityTable, 'PRIORITY_TABLE', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'PRIORITY_TABLE.MEM_NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'PRIORITY_TABLE.MAX_PRIORITY', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> where('PRIORITY_TABLE.MAX_PRIORITY','>=', 13)
        -> whereNotIn('ACN_MEMBER.MEM_NUM_MEMBER', $superInDive)
        -> get();

        $members = array();

        foreach ($groups as $group) {
            $member = DB::table('ACN_MEMBER')
            -> select('ACN_MEMBER.MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME', 'PRE_LABEL')
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
        return view ('groupsMaking', ["members" => $members, "dive" => $dive, "supervisors" => $supervisors, "message" => $message]);
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

        return AcnGroupsMakingController::getAll("");
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
        return AcnGroupsMakingController::getAll("");
    }

    static public function validateButton() {
        $dive = 1;

        $groups = DB::table('ACN_REGISTERED')
        -> select('NUM_GROUPS')
        -> distinct()
        -> join('ACN_DIVES', 'ACN_REGISTERED.NUM_DIVE', '=', 'ACN_DIVES.DIV_NUM_DIVE')
        -> where('NUM_DIVE','=',$dive)
        -> orderBy('NUM_GROUPS')
        -> get();

        $priorityTable = DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', DB::raw('max(PRE_PRIORITY) as MAX_PRIORITY'))
        -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
        -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
        -> groupBy('MEM_NUM_MEMBER');

        $test = array();

        $numGroup = 1;
        foreach ($groups as $group) {
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
            $message = 'Palanquées validées';
            if (($minnum == 5 || $minnum == 7 || $minnum == 10) && $maxnum < 14) {
                $message = 'Il manque un encadrant (minimum E2) dans le groupe '.$numGroup;
            }
            if ($minnum <= 4 && $maxnum < 13) {
                $message = 'Il manque un encadrant dans le groupe '.$numGroup;
            }
            $numGroup++;
        }
        return AcnGroupsMakingController::getAll($message);
    }
}
