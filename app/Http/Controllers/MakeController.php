<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteMakeRequest;
use App\Http\Requests\StoreMakeRequest;
use App\Http\Requests\UpdateMakeRequest;
use App\Http\Resources\MakeCollection;
use App\Http\Resources\MakeResource;
use App\Models\Make;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $make = Make::where('user_id', $request->user()->id)->orderBy('created_at', 'asc')
            ->get();
        return  new MakeCollection($make);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMakeRequest $request)
    {
        $validated = $request->safe()->only(['name']);
        $make = new Make($validated);
        $request->user()->makes()->save($make);
        return (new MakeResource(Make::with('user')->where('id', $make->id)->first()))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * 
     * Display the specified resource.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Models\Make $make
     * 
     * @return App\Http\Resources\MakeResource
     * 
     */
    public function show(Request $request, Make $make)
    {
        $make = Make::where('id', $make->id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        return  new MakeResource($make);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMakeRequest $request, Make $make)
    {
        $validated = $request->safe()->only(['name']);
        $make->update($validated);
        return new MakeResource(Make::with('user')->where('id', $make->id)->first());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteMakeRequest $request, Make $make)
    {
        if ($make->delete()) {
            return response('', Response::HTTP_NO_CONTENT);
        }

        return response('', Response::HTTP_CONFLICT);
    }
}
