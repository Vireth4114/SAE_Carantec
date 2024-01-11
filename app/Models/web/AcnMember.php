<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function getAuthPassword()
    {
        return $this->MEM_PASSWORD;

    }

    public static function getMemberInfo($mem_num){
        return DB::table("ACN_MEMBER")->selectRaw('*')->where('MEM_NUM_MEMBER','=',$mem_num)->get();
    }

    public static function getPrincing(){
        return DB::table("ACN_MEMBER")->select('MEM_PRICING')->distinct()->get();
    }

    public static function getPrerog(){
        return DB::table('ACN_PREROGATIVE')->select('ACN_PREROGATIVE.*')->get();
    }

    public static function getPrerogLabel($prerogative){
        return DB::table('ACN_PREROGATIVE')->select('PRE_LEVEL')->where('PRE_PRIORITY','=',$prerogative)->get();
    }

    public static function getMemberPrerog($member_num){
        return DB::table('ACN_PREROGATIVE')
        ->select('ACN_PREROGATIVE.*')
        ->join('ACN_RANKED', 'ACN_PREROGATIVE.PRE_NUM_PREROG','=','ACN_RANKED.NUM_PREROG')
        ->where('NUM_MEMBER','=',$member_num)
        ->max('ACN_RANKED.NUM_PREROG');
    }

    static public function isUserSecretary($memberNum) {
        $isSecretary = DB::table("ACN_MEMBER")->join("ACN_WORKING", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_WORKING.NUM_MEMBER")
        ->join("ACN_FUNCTION", "ACN_FUNCTION.FUN_NUM_FUNCTION", "=", "ACN_WORKING.NUM_FUNCTION")
        ->where("ACN_FUNCTION.FUN_LABEL", "=", "Secrétaire")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isSecretary;
    }

    static public function isUserManager($memberNum) {
        $isUserManager = DB::table("ACN_MEMBER")->join("ACN_WORKING", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_WORKING.NUM_MEMBER")
        ->join("ACN_FUNCTION", "ACN_FUNCTION.FUN_NUM_FUNCTION", "=", "ACN_WORKING.NUM_FUNCTION")
        ->where("ACN_FUNCTION.FUN_LABEL", "=", "Responsable")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isUserManager;
    }

    static public function isUserDirector($memberNum) {
        $isUserDirector = DB::table("ACN_MEMBER")->join("ACN_RANKED", "ACN_MEMBER.MEM_NUM_MEMBER", "=", "ACN_RANKED.NUM_MEMBER")
        ->join("ACN_PREROGATIVE", "ACN_PREROGATIVE.PRE_NUM_PREROG", "=", "ACN_RANKED.NUM_PREROG")
        ->where("ACN_PREROGATIVE.PRE_PRIORITY", ">", "13")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isUserDirector;
    }

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

    static public function getAllPilots(){
        return DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        -> distinct()
        -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
        -> where ('NUM_FUNCTION','=','3')
        -> where('MEM_STATUS','=','1')
        -> get();
    }

    static public function getMember($num_member){
        return DB::table('ACN_MEMBER')
             -> select('MEM_NAME', 'MEM_SURNAME')
             -> where('MEM_NUM_MEMBER', '=' , $num_member)
             -> get();

    }

    static public function getAllSecurity() {
        return DB::table('ACN_MEMBER')
        -> select('MEM_NUM_MEMBER', 'MEM_NAME', 'MEM_SURNAME')
        -> distinct()
        -> join('ACN_WORKING', 'ACN_MEMBER.MEM_NUM_MEMBER','=', 'ACN_WORKING.NUM_MEMBER')
        -> where ('NUM_FUNCTION','=','2')
        -> where('MEM_STATUS','=','1')
        -> get();
    }

    static public function getMemberMaxPriority($numMember) {
        return DB::table('ACN_MEMBER')
            -> select('PRE_PRIORITY')
            -> join('ACN_RANKED', 'ACN_MEMBER.MEM_NUM_MEMBER', '=', 'ACN_RANKED.NUM_MEMBER')
            -> join('ACN_PREROGATIVE', 'ACN_RANKED.NUM_PREROG', '=', 'ACN_PREROGATIVE.PRE_NUM_PREROG')
            -> where('MEM_NUM_MEMBER', $numMember)
            -> max('PRE_PRIORITY');
    }

    static public function getAllPRevPrerogativeNotE1($member_num,$member_prerogative){
        return DB::table('ACN_RANKED')
                ->select('NUM_PREROG')->distinct()
                ->where('NUM_PREROG', '<=' , $member_prerogative)
                ->where('NUM_PREROG', '>' , 4)
                ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
                ->select('NUM_PREROG')
                ->where('NUM_MEMBER', '=', $member_num))
                ->get();
    }

    static public function getAllPRevPrerogativeButE1($member_num,$member_prerogative){
        return DB::table('ACN_RANKED')
        ->select('NUM_PREROG')->distinct()
        ->where('NUM_PREROG', '<=' , 8)
        ->where('NUM_PREROG', '>' , 4)
        ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
        ->select('NUM_PREROG')
        ->where('NUM_MEMBER', '=', $member_num))
        ->get();
    }

    static public function getAllPRevPrerogativeChildren($member_num,$member_prerogative){
        return DB::table('ACN_RANKED')
        ->select('NUM_PREROG')->distinct()
        ->where('NUM_PREROG', '<=' , $member_prerogative)
        ->where('NUM_PREROG', '<' , 4)
        ->whereNotIn('NUM_PREROG',DB::table('ACN_RANKED')
        ->select('NUM_PREROG')
        ->where('NUM_MEMBER', '=', $member_num))
        ->get();
    }

    static public function insertAllPrerogative($pre,$member_num){
        foreach($pre as $prerogative){
            DB::table('ACN_RANKED')->where('NUM_MEMBER','=',$member_num)->insert(['NUM_MEMBER'=>$member_num,'NUM_PREROG'=>$prerogative->NUM_PREROG]);
        }
    }

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

    static public function getNewNumMember(){
        return DB::table('ACN_MEMBER')->max('MEM_NUM_MEMBER')+1;
    }

    static public function insertNewMember($request,$mem_num_member){
        DB::table('ACN_MEMBER')
            ->insert([
                'MEM_NUM_MEMBER' => $mem_num_member,
                'MEM_NUM_LICENCE' => $request -> member_licence,
                'MEM_NAME' => $request -> member_name,
                'MEM_SURNAME' => $request -> member_surname,
                'MEM_DATE_CERTIF' => $request -> certif_date,
                'MEM_PRICING' => $request -> pricing_type,
                'MEM_STATUS' => 1,
                'MEM_REMAINING_DIVES' => 80,
                'MEM_PASSWORD' => Hash::make($request->member_password),
            ]);
    }

    static public function deleteUserRole($memberNum, $roleName) {
        $roleId = AcnFunction::where("FUN_LABEL", $roleName)->first()->FUN_NUM_FUNCTION;
        DB::table("ACN_WORKING")->where("NUM_FUNCTION", $roleId)->where("NUM_MEMBER", $memberNum)->delete();
    }

    static public function createUserRole($memberNum, $roleName) {
        $roleId = AcnFunction::where("FUN_LABEL", $roleName)->first()->FUN_NUM_FUNCTION;
        DB::table("ACN_WORKING")->insert(["NUM_FUNCTIOn" =>$roleId, "NUM_MEMBER" => $memberNum]);
    }
}
