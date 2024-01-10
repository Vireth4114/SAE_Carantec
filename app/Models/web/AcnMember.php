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

    public function getAuthPassword()
    {
        return $this->MEM_PASSWORD;

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
        ->where("ACN_PREROGATIVE.PRE_PRIORITY", ">", "12")
        ->where("ACN_MEMBER.MEM_NUM_MEMBER","=",$memberNum)
        ->select("*")->exists();
        return $isUserDirector;
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
