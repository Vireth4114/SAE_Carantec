<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnPrerogative extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_PREROGATIVE';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'PRE_NUM_PREROG';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The members that belong to the prerogative.
     */
    public function members()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_RANKED", "NUM_PREROG", "NUM_MEMBER");
    }

    /**
     * return the prerogative
     * @param int $num_pre -> the id of the specified prerogative
     * 
     * @return [data_prerogative] -> the prerogative
     */
    public static function getPrerogative($num_pre){
        $prerogative = DB::table('ACN_PREROGATIVE')
        ->select('PRE_LEVEL')
        ->where('PRE_NUM_PREROG' ,'=', $num_pre)
        -> get();
        return $prerogative;
    }

    /**
     * return all the prerogatives
     * 
     * @return [list[data_Prerogative]] -> all prerogatives
     */
    public static function getPrerog(){
        return DB::table('ACN_PREROGATIVE')->select('ACN_PREROGATIVE.*')->get();
    }

    /**
     * return the label of a prerogative
     * @param int $num_pre -> the id of the specified prerogative
     */
    public static function getPrerogLabel($num_pre){
        return DB::table('ACN_PREROGATIVE')->select('PRE_LEVEL')->where('PRE_PRIORITY','=',$num_pre)->get();
    }

    /**
     * return the highest prerogative of the member
     * @param int $member_num
     * 
     * @return int -> the highest priority
     */
    public static function getMemberPrerog($member_num){
        return DB::table('ACN_PREROGATIVE')
        ->select('ACN_PREROGATIVE.*')
        ->join('ACN_RANKED', 'ACN_PREROGATIVE.PRE_NUM_PREROG','=','ACN_RANKED.NUM_PREROG')
        ->where('NUM_MEMBER','=',$member_num)
        ->max('ACN_RANKED.NUM_PREROG');
    }
}
