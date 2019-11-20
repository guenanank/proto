<?php

namespace App\Http\Controllers;

use App\Models\Sites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Cache::rememberForever('sites:all', function () {
            return Sites::latest()->get();
        });
        return view('sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Sites::rules()->toArray());
        $data = $request->all();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $create = Sites::create($data);
        Cache::forget('sites:all');
        Cache::forever('sites:' . $create->id, $create);
        return response()->json($create);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sites  $sites
     * @return \Illuminate\Http\Response
     */
    public function edit(Sites $site)
    {
        return view('sites.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sites  $sites
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sites $site)
    {
        Validator::make($request->all(), $site->rules([
          'name' => [
              'required', 'string', 'max:63',
              Rule::unique($site->getTable())->ignore($site->id),
          ]
        ])->toArray());

        $data = $request->all();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            if (Storage::exists('images' . $site->meta->cover)) {
                Storage::delete('images' . $site->meta->cover);
            }
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $update = $site->update($data);
        Cache::forget('sites:' . $site->id);
        Cache::forget('sites:all');
        Cache::forever('sites:' . $site->id, $site);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sites  $sites
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sites $site)
    {
        Cache::forget('sites:' . $site->id);
        Cache::forget('sites:all');
        if (Storage::exists('images' . $site->meta->cover)) {
            Storage::delete('images' . $site->meta->cover);
        }
        $delete = $site->delete();
        return response()->json($delete);
    }
}
