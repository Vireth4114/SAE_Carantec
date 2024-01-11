<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MemberRequest;
use App\Http\Resources\Api\MemberResource;
use App\Models\web\AcnFunction;
use App\Models\web\AcnMember;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcnMemberController extends Controller
{
    /**
     * Display a listing of the members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MemberResource::collection(AcnMember::all());
    }

    /**
     * Store a newly created member in storage.
     *
     * @param  \Illuminate\Http\Api\MemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        $member = new AcnMember;
        $member->MEM_NUM_LICENCE = strtoupper($request->licence);
        $member->MEM_NAME = $request->name;
        $member->MEM_SURNAME = $request->surname;
        $member->MEM_DATE_CERTIF = Carbon::parse($request->date_certification);
        $member->MEM_PRICING = strtolower($request->pricing);
        $member->MEM_STATUS = 1;
        $member->MEM_REMAINING_DIVES = 99;
        $member->MEM_PASSWORD = Hash::make($request->password);
        $member->save();
        $functionId = AcnFunction::where("FUN_LABEL", "Adhérent")->first()->FUN_NUM_FUNCTION;
        DB::insert("INSERT INTO ACN_WORKING (NUM_MEMBER, NUM_FUNCTION) values (?, ?)", [$member->MEM_NUM_MEMBER, $functionId]);
        return new MemberResource($member);
    }

    /**
     * Display the specified member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $member = AcnMember::findOrFail($id);
            return new MemberResource($member);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "string|required|max:64",
            "surname" => "string|required|max:64",
            "date_certification" => "date|required",
            "pricing" => "string|required|in:adulte,enfant",
            "remaining_dives" => "integer|numeric",
            "password" => "string|sometimes",
        ]);
        try {
            $member = AcnMember::findOrFail($id);
            if ($member->MEM_STATUS === 0) return response(["message" => "Resource requested does not exist."], 404);
            $member->MEM_NAME = $request->name;
            $member->MEM_SURNAME = $request->surname;
            $member->MEM_DATE_CERTIF = Carbon::parse($request->date_certification);
            $member->MEM_PRICING = strtolower($request->pricing);
            if (isset($request->remaining_dives)) $member->MEM_REMAINING_DIVES = $request->remaining_dives;
            if (isset($request->password)) $member->MEM_PASSWORD = Hash::make($request->password);
            $member->save();
            return new MemberResource($member);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist.".$id], 404);
        }
    }

    /**
     * Remove the specified member from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $member = AcnMember::findOrFail($id);
            $member->MEM_STATUS = 0;
            $member->save();
            return response(null, 204);
        } catch (Exception $e) {
            return response("Resource requested to delete does not exist.", 404);
        }
    }
}
