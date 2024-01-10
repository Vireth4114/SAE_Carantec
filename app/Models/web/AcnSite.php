<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    static public function getAllSites() {
        return AcnSite::where("SIT_DELETED", "=", 0)->get();
    }
}
