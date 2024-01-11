<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FunctionRequest;
use App\Http\Resources\Api\FunctionResource;
use App\Models\web\AcnFunction;
use Exception;
use Illuminate\Http\Request;

class AcnFunctionController extends Controller
{
    /**
     * Display a listing of functions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FunctionResource::collection(AcnFunction::all());
    }

    /**
     * Store a newly created function in storage.
     *
     * @param  \Illuminate\Http\Api\FunctionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FunctionRequest $request)
    {
        //
    }

    /**
     * Display the specified function.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $function = AcnFunction::findOrFail($id);
            return new FunctionResource($function);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified function in storage.
     *
     * @param  \Illuminate\Http\Api\FunctionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified function from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(["message" => "This action is unauthorized."], 403);
    }
}
