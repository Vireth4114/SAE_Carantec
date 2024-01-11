<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnSite extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_SITE';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'SIT_NUM_SITE';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * return the site
     * @param int $num_site -> the id of the specified site
     * 
     * @return [data_Site] -> the site
     */
    public static function getSite($num_site){
        $site = DB::table('ACN_SITE')
        -> select('SIT_NAME')
        -> where('SIT_NUM_SITE','=',$num_site) ->get();
        return $site;
    }
    static public function getAllSites() {
        return AcnSite::where("SIT_DELETED", "=", 0)->get();
    }
}
