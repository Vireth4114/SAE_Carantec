<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcnPeriod extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ACN_PERIOD';

   /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'PER_START_TIME' => 'datetime',
        'PER_END_TIME' => 'datetime',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'PER_NUM_PERIOD';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
