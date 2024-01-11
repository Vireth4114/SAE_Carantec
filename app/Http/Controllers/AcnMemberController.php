<?php

namespace App\Http\Controllers;

use App\Models\web\AcnMember;
use App\Models\web\AcnRanked;
use App\Models\web\AcnWorking;
use App\Models\web\AcnPrerogative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnMemberController extends Controller
{
    /**
     *
     *
     * @param number $memberNum the identification of a member
     * @return list of the all members
     */
    public static function getMember($memberNum) {
        return AcnMember::find($memberNum);
    }

    /**
     * Undocumented function
     *
     * @param Request $request a request to updating the roles of a member
     * @param number $memberNum the identification of a member
     * @return mixed the redirection to the manager's panel page
     */
    public static function updateRolesMember(Request $request, $memberNum) {
        $checkboxFields = array("security", "secretary", "pilot");
        $fieldsMappingToNameInDatabase = array("security" => "Sécurité de surface",
                                                "secretary" => "Secrétaire",
                                                "pilot" => "Pilote");
        foreach($checkboxFields as $field) {
            $nameInDatabase = $fieldsMappingToNameInDatabase[$field];
            if ($request->exists($field)) {
                AcnWorking::createUserRole($memberNum, $nameInDatabase);
            } else {
                AcnWorking::deleteUserRole($memberNum, $nameInDatabase);
            }
        }
        return redirect(route("managerPanel"));
    }

    public static function updateStatus($member_num){
        AcnMember::changeStatus($member_num);
        return redirect('members');
    }

    /**
     * Return a view to modify the profile of a member
     *
     * @param [int] $mem_num_member
     * @return view
     */
    public static function modifyForm($mem_num_member){

        $member = AcnMember::getMemberInfo($mem_num_member);
        $pricing = AcnMember::getPrincing();
        $prerog = AcnPrerogative::getPrerog();
        $prerogMemLvl = AcnPrerogative::getMemberPrerog($mem_num_member);

        return view('members_modification',["member" => $member[0],"pricing" => $pricing,"prerogation"=>$prerog,"prerogation_member_level" =>$prerogMemLvl]);
    }

    public static function registerForm(){

        $pricing = AcnMember::getPrincing();
        $prerog = AcnMember::getPrerog();

        return view('members_registration',["pricing" => $pricing,"prerogation"=>$prerog]);
    }

    /**
     * Check if the modifications are legal and update the data if they are, else go on an Exception page
     *
     * @param Request $request
     * @return mixed
     */
    static public function modify(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        $member = AcnMember::getMemberInfo($request->member_num);
        $prerogMemLvl = AcnPrerogative::getMemberPrerog($request->member_num);
        $preroglabel = AcnPrerogative::getPrerogLabel($request->member_prerog);


        //Checks if the secretary attempt to dicrease the number of remaining dive.
        if ($request -> remaining_dive < $member[0]->MEM_REMAINING_DIVES) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des plongées à un adhérent, ne descendez pas en dessous de ".$member[0]->MEM_REMAINING_DIVES." plongées restante.;";
        }
        if ($request->pricing_type == 'enfant' && $request->member_prerog > 4) {
            $err = true;
            $strErr .= "- Pour un abonnement enfant les prérogatives disponibles sont uniquement : PB, PA, PO-12, PO-20 et vous avez choisis : ".$preroglabel[0]->PRE_LEVEL.";";
        }
        if ($request->pricing_type == 'adulte' && $request->member_prerog <= 4 ) {
            $err = true;
            $strErr .= "- Les prérogatives : PB, PA, PO-12, PO-20 sont disponible uniquement pour les enfants et vous avez choisis : ".$preroglabel[0]->PRE_LEVEL.";";
        }if ($request->member_prerog < $prerogMemLvl && $prerogMemLvl != 13) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des prérogatives à un adhérents, vous avez saisi un niveau de prérogative inférieur au dernier qu'il possède;";
        }if ($request->member_prerog < 8 && $prerogMemLvl == 13) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des prérogatives à un adhérents, vous avez saisi un niveau de prérogative inférieur au dernier qu'il possède;";
        }

        if ($err) {
            $arrayErr = explode(";",$strErr);
            return view('memberException',['member_num'=>$request->member_num,'error_msg'=>$arrayErr,'actionType'=>'Modification']);
        }
        else {
            AcnMember::updateMemberInfos($request);

            //search for every prerogation a member don't have and are below the prerogation selected, meant to add them later (for the 3 request below)
        if($request->pricing_type == 'adulte'){
            if($request->member_prerog  == 13){
                $pre = AcnRanked::getAllPRevPrerogativeButE1($request->member_num,$request->member_prerog);

            }else{
                $pre = AcnRanked::getAllPRevPrerogativeNotE1($request->member_num,$request->member_prerog);
            }
        }else{
            //same but for children
            $pre = AcnRanked::getAllPRevPrerogativeChildren($request -> member_num,$request->member_prerog);
        }

        //insert All the prerogative selected
        AcnRanked::insertAllPrerogative($pre,$request->member_num);

            return redirect('members');
        }

    }

    static public function register(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        $preroglabel = AcnMember::getPrerogLabel($request->member_prerog);
        $newNumMember = AcnMember::getNewNumMember();


        if ($request->pricing_type == 'enfant' && $request->member_prerog > 4) {
            $err = true;
            $strErr .= "- Pour un abonnement enfant les prérogatives disponibles sont uniquement : PB, PA, PO-12, PO-20 et vous avez choisis : ".$preroglabel[0]->PRE_LEVEL.";";
        }
        if ($request->pricing_type == 'adulte' && $request->member_prerog <= 4 ) {
            $err = true;
            $strErr .= "- Les prérogatives : PB, PA, PO-12, PO-20 sont disponible uniquement pour les enfants et vous avez choisis : ".$preroglabel[0]->PRE_LEVEL.";";
        }

        if ($err) {
            $arrayErr = explode(";",$strErr);
            return view('memberException',['member_num'=>$newNumMember,'error_msg'=>$arrayErr,'actionType'=>'Registration']);
        }
        else {
            AcnMember::insertNewMember($request,$newNumMember);

            //search for every prerogative a member need to have before the prerogation selected, meant to add every prerogative needed later (same for the 2 request below)
        if($request->pricing_type == 'adulte'){
            if($request->member_prerog  == 13){
                $pre = AcnMember::getAllPRevPrerogativeButE1($newNumMember,$request->member_prerog);

            }else{
                $pre = AcnMember::getAllPRevPrerogativeNotE1($newNumMember,$request->member_prerog);
            }
        }else{
            //same but for children
            $pre = AcnMember::getAllPRevPrerogativeChildren($newNumMember,$request->member_prerog);
        }

        //insert All the prerogative selected
        AcnMember::insertAllPrerogative($pre,$newNumMember);

            return redirect('members');
        }

    }


}
