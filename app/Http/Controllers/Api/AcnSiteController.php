<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SiteRequest;
use App\Http\Resources\Api\SiteResource;
use App\Models\web\AcnSite;
use Exception;

class AcnSiteController extends Controller
{
    /**
     * Display a listing of the sites.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SiteResource::collection(AcnSite::all()->where("SIT_DELETED", 0));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteRequest $request)
    {
        $site = new AcnSite;
        $site->SIT_NAME = strtoupper($request->name);
        $site->SIT_COORD = $request->coord;
        $site->SIT_DEPTH = $request->depth;
        $site->SIT_DESCRIPTION = $request->description;
        $site->save();
        return new SiteResource($site);
    }

    /**
     * Display the specified site.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $site = AcnSite::findOrFail($id);
            if ($site->SIT_DELETED === 1) return response("Resource requested does not exist.", 404);
            return new SiteResource($site);
        } catch (Exception $e) {
            return response("Resource requested does not exist.", 404);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SiteRequest $request, $id)
    {
        try {
            $site = AcnSite::findOrFail($id);
            if ($site->SIT_DELETED === 1) return response("Resource requested does not exist.", 404);
            $site->SIT_NAME = strtoupper($request->name);
            $site->SIT_COORD = $request->coord;
            $site->SIT_DEPTH = $request->depth;
            $site->SIT_DESCRIPTION = $request->description;
            $site->save();
            return new SiteResource($site);
        } catch (Exception $e) {
            return response("Resource requested does not exist.", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $site = AcnSite::findOrFail($id);
            $site->SIT_DELETED = 1;
            $site->save();
            return response(null, 204);
        } catch (Exception $e) {
            return response("Resource requested to delete does not exist.", 404);
        }
    }
}
