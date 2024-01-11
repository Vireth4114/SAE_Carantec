<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AcnDives extends Model
{
    use HasFactory;

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
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_SECURED');
    }

    /**
     * Get the leader for the dive.
     */
    public function leader(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_LEAD');
    }

    /**
     * Get the pilot for the dive.
     */
    public function pilot(): HasOne
    {
        return $this->hasOne(AcnMember::class, 'MEM_NUM_MEMBER', 'DIV_NUM_MEMBER_PILOTING');
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

}
