<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class AcnMember extends Authenticatable
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_MEMBER';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'MEM_NUM_MEMBER';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ["MEM_NUM_LICENCE", "MEM_PASSWORD"];

    /**
     * The functions that belong to the member.
     */
    public function functions()
    {
        return $this->belongsToMany(AcnFunction::class, "ACN_WORKING", "NUM_MEMBER", "NUM_FUNCTION");
    }

    /**
     * The prerogatives that belong to the member.
     */
    public function prerogatives()
    {
        return $this->belongsToMany(AcnPrerogative::class, "ACN_RANKED", "NUM_MEMBER", "NUM_PREROG");
    }

    /**
     * Get Groups of the user.
     */
    public function groups()
    {
        return $this->belongsToMany(AcnGroups::class, "ACN_REGISTERED", "NUM_MEMBER", "NUM_GROUPS");
    }

    /**
     * Get Dives of the user.
     */
    public function dives()
    {
        return $this->belongsToMany(AcnDives::class, "ACN_REGISTERED", "NUM_MEMBER", "NUM_DIVE");
    }

    /**
     * Return the hashed password of the member
     * 
     * @return [String] -> hashed Password
     */
    public function getAuthPassword()
    {
        return $this->MEM_PASSWORD;

    }

    /**
     * return the member
     * @param int $mem_num -> the specified member's id
     * 
     * @return [data_Member] -> the member
     */
    public static function getMemberInfo($mem_num){
        return DB::table("ACN_MEMBER")->selectRaw('*')->where('MEM_NUM_MEMBER','=',$mem_num)->get();
    }

    /**
     * returns all the pricings
     * 
     * @return [list[String]] -> the pricings
     */
    public static function getPrincing(){
        return DB::table("ACN_MEMBER")->select('MEM_PRICING')->distinct()->get();
    }

    /**
     * check is the user is a Secretary
     * @param int $memberNum -> the specified user
     * 
     * @return boolean -> True if the user is a Secretary, false otherwise
     */
    static public function isUserSecretary($memberNum) {
        $isSecretary = DB::table("ACN_MEMBER")->join("ACN_WORKING", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_WORKING.NUM_MEMBER")
        ->join("ACN_FUNCTION", "ACN_FUNCTION.FUN_NUM_FUNCTION", "=", "ACN_WORKING.NUM_FUNCTION")
        ->where("ACN_FUNCTION.FUN_LABEL", "=", "Secrétaire")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isSecretary;
    }

    /**
     * check is the user is a Manager
     * @param int $memberNum -> the specified user
     * 
     * @return boolean -> True if the user is a Manager, false otherwise
     */
    static public function isUserManager($memberNum) {
        $isUserManager = DB::table("ACN_MEMBER")->join("ACN_WORKING", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_WORKING.NUM_MEMBER")
        ->join("ACN_FUNCTION", "ACN_FUNCTION.FUN_NUM_FUNCTION", "=", "ACN_WORKING.NUM_FUNCTION")
        ->where("ACN_FUNCTION.FUN_LABEL", "=", "Responsable")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isUserManager;
    }

    /**
     * check is the user is a Director
     * @param int $memberNum -> the specified user
     * 
     * @return boolean -> True if the user is a Director, false otherwise
     */
    static public function isUserDirector($memberNum) {
        $isUserDirector = DB::table("ACN_MEMBER")->join("ACN_RANKED", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_RANKED.NUM_MEMBER")
        ->join("ACN_PREROGATIVE", "ACN_PREROGATIVE.PRE_NUM_PREROG", "=", "ACN_RANKED.NUM_PREROG")
        ->where("ACN_PREROGATIVE.PRE_PRIORITY", ">", "13")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isUserDirector;
    }

    /**
     * return all the users that are eligible to be director
     * 
     * @return [list[data_Member]] -> all the members that can be director
     */
    static public function getAllLeader(){
       return DB::table('ACN_MEMBER')
            -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
            -> distinct()
            -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('PRE_PRIORITY', '>', '12')
            -> where('MEM_STATUS','=','1')
            -> get();
    }

    /**
     * return all the users that are pilots
     * 
     * @return [list[data_Member]] -> all the members that are pilots
     */
    static public function getAllPilots(){
        return DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        -> distinct()
        -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
        -> where ('NUM_FUNCTION','=','3')
        -> where('MEM_STATUS','=','1')
        -> get();
    }

    /**
     * return the member
     * @param int $memberNum -> the id of the specified member
     * 
     * @return [data_Member] -> the specified members
     */
    static public function getMember($memberNum){
        return DB::table('ACN_MEMBER')
             -> select('MEM_NAME', 'MEM_SURNAME')
             -> where('MEM_NUM_MEMBER', '=' , $memberNum)
             -> get();

    }

    /**
     * return all the users that are security
     * 
     * @return [list[data_Member]] -> all the members that are surface security
     */
    static public function getAllSecurity() {
        return DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        -> distinct()
        -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
        -> where ('NUM_FUNCTION','=','2')
        -> where('MEM_STATUS','=','1')
        -> get();
    }

    /**
     * return the max priority of the member's prerogatives
     * @param int $memberNum -> the id of the specified member
     * 
     * @return int -> the maximum pre_priority of the member
     */
    static public function getMemberMaxPriority($memberNum) {
        return DB::table('ACN_MEMBER')
            -> select('PRE_PRIORITY')
            -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('MEM_NUM_MEMBER', $memberNum)
            -> max('PRE_PRIORITY');
    }

    /**
     * update the data of a user
     * @param mixed $request -> the request with all the data to update a member
     */
    static public function updateMemberInfos($request){
        DB::table('ACN_MEMBER')->where('MEM_NUM_MEMBER', '=', $request -> member_num)
            ->update([
                'MEM_NAME' => $request -> member_name,
                'MEM_SURNAME' => $request -> member_surname,
                'MEM_DATE_CERTIF' => $request -> certif_date,
                'MEM_PRICING' => $request -> pricing_type,
                'MEM_REMAINING_DIVES' => $request -> remaining_dive,
            ]);
    }
}
