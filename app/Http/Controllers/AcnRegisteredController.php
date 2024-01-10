<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\web\AcnRegistered;

class AcnRegisteredController extends Controller
{
    public static function create($numMember, $numDive) {
        AcnRegistered::insert($numMember, $numDive);
    }

    public static function delete($numMember, $numDive) {
        AcnRegistered::deleteData($numMember, $numDive);
    }
}
