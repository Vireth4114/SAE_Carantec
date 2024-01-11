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

    /**
     * return the specified Boat
     * @param int $num_boat
     * 
     * @return [data_Boat]
     */
    public static function getBoat($num_boat){
        $boat = DB::table('ACN_BOAT')
        -> select('BOA_NAME')
        -> where('BOA_NUM_BOAT','=',$num_boat)
        ->get();
        return $boat;
    }

    /**
     * return all the boats that aren't deleted
     * 
     * @return [list[data_Boat]] -> a list of boat
     */
    static public function getAllBoats() {
        return AcnBoat::where("BOA_DELETED", "=", 0)->get();
    }
}
