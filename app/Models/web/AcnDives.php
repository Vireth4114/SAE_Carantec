<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class AcnDives extends Model
{
    use HasFactory;

    static public function getMembersNotInDive($diveId) {

        $members = AcnDives::find($diveId)->divers;
        $memNums=array();
        foreach($members as $member){
            array_push($memNums, $member['MEM_NUM_MEMBER']);
        }

        return DB::table('ACN_MEMBER')
        -> where ('MEM_NUM_MEMBER', '>', 0)
        -> whereNotIn('MEM_NUM_MEMBER', $memNums)
        -> get();
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_DIVES';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'DIV_NUM_DIVE';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

        /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'DIV_DATE' => 'datetime',
    ];

    /**
     * Get the boat that is used for the diving.
     */
    public function boat(): HasOne
    {
        return $this->hasOne(AcnBoat::class, 'BOA_NUM_BOAT', 'DIV_NUM_BOAT');
    }

    /**
     * Get the site where the dive take place.
     */
    public function site(): HasOne
    {
        return $this->hasOne(AcnSite::class, 'SIT_NUM_SITE', 'DIV_NUM_SITE');
    }

    /**
     * Get the period where the dive take place.
     */
    public function period(): HasOne
    {
        return $this->hasOne(AcnPeriod::class, 'PER_NUM_PERIOD', 'DIV_NUM_PERIOD');
    }

    /**
     * Get the surface security member for the dive.
     */
    public function surfaceSecurity(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_LICENCE', 'DIV_NUM_LICENCE_SECURED');
    }

    /**
     * Get the leader for the dive.
     */
    public function leader(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_LICENCE', 'DIV_NUM_LICENCE_LEAD');
    }

    /**
     * Get the pilot for the dive.
     */
    public function pilot(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_LICENCE', 'DIV_NUM_LICENCE_PILOTING');
    }

    /**
     * Get the minimum prerogative required for the dive.
     */
    public function prerogative(): HasOne
    {
        return $this->hasOne(AcnPrerogative::class, 'PRE_NUM_PREROG', 'DIV_NUM_PREROG');
    }

    /**
     * Get Divers for this dive.
     */
    public function divers()
    {
        return $this->belongsToMany(AcnMember::class, "ACN_REGISTERED", "NUM_DIVE", "NUM_MEMBER");
    }

    /**
     * Get Groups for this dive.
     */
    public function groups()
    {
        return $this->belongsToMany(AcnGroups::class, "ACN_REGISTERED", "NUM_DIVE", "NUM_GROUPS");
    }

    public static function getDive($num_dive){

        $dive = DB::table('acn_dives')->select('acn_dives.*')->where('div_num_dive','=',$num_dive)->get();
        //array_push($dive, $dive_info[0]);
        /*
        $period = DB::table('ACN_PERIOD')
        -> select('PER_LABEL')
        -> where('PER_NUM_PERIOD','=',$dive_info[0]-> DIV_NUM_PERIOD) ->get();
        array_push($dive,  $period[0]);

        $site = DB::table('acn_site')
        ->select('sit_name')
        ->where('sit_num_site','=',$dive_info[0]->DIV_NUM_SITE)->get();

        if(!$site->isEmpty()){
            array_push($dive,  $site[0]);
        }else{
            $site = collect("sit_name","");
            array_push($dive, $site[0]);
        }



        $boat = DB::table('acn_boat')
        ->select('BOA_NAME')
        ->where('BOA_NUM_BOAT','=',$dive_info[0]->DIV_NUM_BOAT)->get();
        if(!$boat->isEmpty()){
            array_push($dive,  $boat[0]);
        }else{
            array_push($dive,  "");
        }

        $securedMem = DB::table('acn_member')
        ->select('MEM_NAME','MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dive_info[0]->DIV_NUM_MEMBER_SECURED)->get();
        if(!$securedMem->isEmpty()){
            array_push($dive,  $securedMem[0]);
        }else{
            array_push($dive,  "");
        }

        $leadMem = DB::table('acn_member')
        ->select('MEM_NAME','MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dive_info[0]->DIV_NUM_MEMBER_LEAD)->get();
        if(!$leadMem->isEmpty()){
            array_push($dive,  $leadMem[0]);
        }else{
            array_push($dive,  "");
        }

        $piloteMem = DB::table('acn_member')
        ->select('MEM_NAME','MEM_SURNAME')
        ->where('MEM_NUM_MEMBER','=',$dive_info[0]->DIV_NUM_MEMBER_PILOTING)->get();
        if(!$piloteMem->isEmpty()){
            array_push($dive,  $piloteMem[0]);
        }else{
            array_push($dive,  "");
        }


        $prero = DB::table('acn_prerogative')
        ->select('PRE_LABEL')
        ->where('PRE_NUM_PREROG','=',$dive_info[0]->DIV_NUM_PREROG)->get();
        if(!$prero->isEmpty()){
            array_push($dive,  $prero[0]);
        }else{
            array_push($dive,  "");
        }
        dd($dive);
        */
        return $dive;
    }

}
