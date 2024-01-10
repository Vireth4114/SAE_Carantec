<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnBoat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_BOAT';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'BOA_NUM_BOAT';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getBoat($num_boat){
        $boat = DB::table('ACN_BOAT')
        -> select('BOA_NAME')
        -> where('BOA_NUM_BOAT','=',$num_boat)
        ->get();
        return $boat;
    }
}
