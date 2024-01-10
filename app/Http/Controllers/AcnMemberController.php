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

    /**
     * Return a view to modify the profile of a member
     *
     * @param [int] $mem_num_member 
     * @return void
     */
    public static function modifyForm($mem_num_member){

        $member = AcnMember::getMemberInfo($mem_num_member);
        $pricing = AcnMember::getPrincing();
        $prerog = AcnMember::getPrerog();
        $tutorlvl = AcnMember::getTutorLevels();
        $prerogMemLvl = AcnMember::getMemberPrerog($mem_num_member);
        $tutorMemLvl = AcnMember::getMemberTutorLevels($mem_num_member);

        return view('members_modification',["member" => $member[0],"pricing" => $pricing,"prerogation"=>$prerog,"tutorlvl"=>$tutorlvl,"prerogation_member_level" =>$prerogMemLvl,"tutor_member_level"=>$tutorMemLvl]);
    }

    /**
     * Check if the modifications are legal and update the data
     *
     * @param Request $request
     * @return void
     */
    static public function modify(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        // //Checks if the leader, the pilot and the surface security are different person.
        // if ($request -> member_prerog < $request -> member_tutor_lvl) {
        //     $member = AcnMemberController::getMember($request -> lead);
        //     $err = true;
        //     $strErr .= "- Le directeur de plongée et le pilote ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        // }
        // if (!is_null($request -> lead) && !is_null($request -> security) && ($request -> lead == $request -> security) ) {
        //     $member = AcnMemberController::getMember($request -> lead);
        //     $err = true;
        //     $strErr .= "- Le directeur de plongée et la sécurié de surface ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        // }
        // if (!is_null($request -> pilot) && !is_null($request -> security) && ($request -> pilot == $request -> security) ) {
        //     $member = AcnMemberController::getMember($request -> pilot);
        //     $err = true;
        //     $strErr .= "- La sécurié de surface et le pilote ne peuvent être la même personne (".$member['MEM_NAME']." ".$member['MEM_SURNAME'].").<br>";
        // }

        // $dive=AcnDives::getDive($request -> numDive);

        // if ($dive[0] -> DIV_MIN_REGISTERED < $request -> min_divers) {
        //     $err = true;
        //     $strErr .= "-L'effectif minimum ne peut qu'être diminué<br>";
        // }

        // if ($dive[0] -> DIV_MAX_REGISTERED > $request -> max_divers) {
        //     $err = true;
        //     $strErr .= "-L'effectif maximum ne peut qu'être augmenté<br>";
        // }

        // $divers_lvl = AcnDives::find($request -> numDive)->divers;
        // $minAllDivers = AcnPrerogative::all()->max('PRE_PRIORITY');
        // foreach($divers_lvl as $diver) {
        //     $maxLocalDiver = $diver->prerogatives->max("PRE_PRIORITY");
        //     if ($maxLocalDiver < $minAllDivers) {
        //         $minAllDivers = $maxLocalDiver;
        //     }
        // }
        // if($minAllDivers < AcnPrerogative::find($request -> lvl_required)->PRE_PRIORITY) {
        //     $err = true;
        //     $strErr .= "-Le niveau saisi est trop élevé <br>";
        // }

        // $max = AcnDivesController::getNumMax();

        if ($err) {
            echo $strErr;
        }
        else {
            DB::table('ACN_MEMBER')->where('MEM_NUM_MEMBER', '=', $request -> member_num)
            ->update([
                'MEM_NAME' => $request -> member_name,
                'MEM_SURNAME' => $request -> member_surname,
                'MEM_DATE_CERTIF' => $request -> certif_date,
                'MEM_PRICING' => $request -> pricing_type,
                'MEM_REMAINING_DIVES' => $request -> remaining_dive,
            ])
            ;

            //search for every prerogation a member don't have and are below the prerogation selected, meant to add them later
            $pre = DB::table('ACN_RANKED')
            ->select('NUM_PREROG')->distinct()
            ->where('NUM_PREROG', '<=' , 15)
            ->where('NUM_PREROG', '>' , 4)
            ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
            ->select('NUM_PREROG')
            ->where('NUM_MEMBER', '=', $request -> member_num))
            ->get();





            // DB::table('ACN_RANKED')
            // ->select('NUM_PREROG')
            // ->where('NUM_MEMBER', '=', $request -> member_num);

            // DB::table('ACN_RANKED')->where('NUM_MEMBER', '=', $request -> member_num)
            // ->insert([
            //     'MEM_NAME' => $request -> member_name,
            //     'MEM_SURNAME' => $request -> member_surname,
            //     'MEM_DATE_CERTIF' => $request -> certif_date,
            //     'MEM_PRICING' => $request -> pricing_type,
            //     'MEM_REMAINING_DIVES' => $request -> remaining_dive,
            // ])
            // ;

            return redirect('members');
        }

    }
}
