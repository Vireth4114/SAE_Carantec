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

    public static function getPrerogative($num_pre){
        $prerogative = DB::table('ACN_PREROGATIVE')
        ->select('PRE_LEVEL')
        ->where('PRE_NUM_PREROG' ,'=', $num_pre)
        -> get();
        return $prerogative;
    }
}
