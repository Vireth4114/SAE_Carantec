<?php

namespace App\Models\web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcnRegistered extends Model
{
    static public function insert($numMember, $numDive) {
        DB::table('ACN_REGISTERED')->insert([
            'NUM_DIVE' => $numDive,
            'NUM_MEMBER' => $numMember,
        ]);
    }

    static public function deleteData($numMember, $numDive) {
        DB::table('ACN_REGISTERED')
            ->where ('NUM_MEMBER', $numMember)
            ->where ('NUM_DIVE', $numDive)
            ->delete();
    }
}
                                    