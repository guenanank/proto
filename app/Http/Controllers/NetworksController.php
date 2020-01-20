<?php

namespace App\Http\Controllers;

use App\Models\Networks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class NetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $networks = Cache::rememberForever('networks:all', function () {
            return Networks::latest()->get();
        });
        return view('networks.index', compact('networks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('networks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Networks::rules()->toArray());
        $data = $request->all();
        $create = Networks::create($data);
        Cache::forget('networks:all');
        Cache::forever('networks:' . $create->id, $create);
        return response()->json($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Networks  $networks
     * @return \Illuminate\Http\Response
     */
    public function edit(Networks $network)
    {
        return view('networks.edit', compact('network'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Networks  $networks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Networks $network)
    {
        Validator::make($request->all(), $network->rules([
          'name' => [
              'required', 'string', 'max:63',
              Rule::unique($network->getTable())->ignore($network->id),
          ]
        ])->toArray());

        $data = $request->all();
        $update = $network->update($data);
        Cache::forget('networks:' . $network->id);
        Cache::forget('networks:all');
        Cache::forever('networks:' . $network->id, $network);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Networks  $networks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Networks $network)
    {
        Cache::forget('networks:' . $network->id);
        Cache::forget('networks:all');
        $delete = $network->delete();
        return response()->json($delete);
    }
}
