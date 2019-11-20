<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Channels;
use Recursive;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('channels.index');
    }

    /**
     * Resource JSON data.
     *
     * @return \DataTables
     */
    public function dataTable(Request $request)
    {
        $columns = ['channels.id', 'channels.site_id', 'channels.name', 'channels.slug', 'channels.sub', 'channels.meta', 'channels.displayed', 'channels.created_at', 'channels.updated_at'];
        $channels = Channels::select($columns);
        return datatables()->eloquent($channels)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recursive = Recursive::make($this->channels, 'id', 'sub');
        $channels = Recursive::data($recursive, 'name');
        return view('channels.create', compact('channels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Channels::rules()->toArray());
        $data = $request->all();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $create = Channels::create($data);
        Cache::forget('channels:all');
        Cache::forever('channels:' . $create->id, $create);
        return response()->json($create);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Elasticsearch\Channels  $channels
     * @return \Illuminate\Http\Response
     */
    public function edit(Channels $channel)
    {
        $recursive = Recursive::make($this->channels, 'id', 'sub');
        $channels = Recursive::data($recursive, 'name');
        return view('channels.edit', compact('channels', 'channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Elasticsearch\Channels  $channels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channels $channel)
    {
        Validator::make($request->all(), $channel->rules([
          'name' => [
              'required', 'string', 'max:127',
              Rule::unique($channel->getTable())->ignore($channel->id),
          ]
        ])->toArray());


        $data = $request->all();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            if (Storage::exists('images' . $channel->meta->cover)) {
                Storage::delete('images' . $channel->meta->cover);
            }
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $update = $channel->update($data);
        Cache::forget('channels:' . $channel->id);
        Cache::forget('channels:all');
        Cache::forever('channels:' . $channel->id, $channel);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Elasticsearch\Channels  $channels
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channels $channel)
    {
        Cache::forget('channels:' . $channel->id);
        Cache::forget('channels:all');
        if (Storage::exists('images' . $channel->meta->cover)) {
            Storage::delete('images' . $channel->meta->cover);
        }
        $delete = $channel->delete();
        return response()->json($delete);
    }
}
