<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Topics;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('topics.index');
    }

    /**
     * Resource JSON data.
     *
     * @return \DataTables
     */
    public function dataTable(Request $request)
    {
        $topics = Cache::rememberForever('topics:all', function () {
            return Topics::latest('lastUpdate')->get();
        });

        return datatables()->of($topics)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Cache::get('groups:all');
        return view('topics.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Topics::rules()->toArray());
        $data = $request->all();
        $media = Cache::get('media:' . $request->mediaId);
        $path = sprintf('%s/%s/topic/', strtolower($media->group->code), Str::slug($media->name));
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $data['meta']['cover'] = Str::slug($request->title) . '.' . $request->cover->extension();
            $request->cover->storeAs($path, $data['meta']['cover']);
        }

        $create = Topics::create($data);
        Cache::forget('topics:all');
        Cache::forever('topics:' . $create->_id, $create);
        return response()->json($create);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topics  $topics
     * @return \Illuminate\Http\Response
     */
    public function edit(Topics $topic)
    {
        $groups = Cache::get('groups:all');
        return view('topics.edit', compact('groups', 'topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topics  $topics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topics $topic)
    {
        Validator::make($request->all(), $topic->rules([
          'name' => [
              'required', 'string', 'max:63',
              Rule::unique($topic->getTable())->ignore($topic->id),
          ]
        ])->toArray());

        $topic->media->load('group');
        $data = $request->all();
        $path = sprintf('%s/%s/topic/', strtolower($topic->media->group->code), Str::slug($topic->media->name));

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $data['meta']['cover'] = Str::slug($request->title) . '.' . $request->cover->extension();
            if ($topic->meta->has('cover') && Storage::exists($path . $topic->meta['cover'])) {
                Storage::delete($path . $topic->meta['cover']);
            }
            $request->cover->storeAs($path, $data['meta']['cover']);
        } else {
            $data['meta']['cover'] = $topic->meta['cover'];
        }

        $update = $topic->update($data);
        Cache::forget('topics:' . $topic->_id);
        Cache::forget('topics:all');
        Cache::forever('topics:' . $topic->_id, $topic);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topics  $topics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topics $topic)
    {
        $topic->media->load('group');
        $path = sprintf('%s/%s/topic/', strtolower($topic->media->group->code), Str::slug($topic->media->name));
        if ($topic->meta->has('cover') && Storage::exists($path . $topic->meta['cover'])) {
            Storage::delete($path . $topic->meta['cover']);
        }

        Cache::forget('topics:' . $topic->_id);
        Cache::forget('topics:all');
        $delete = $topic->delete();
        return response()->json($delete);
    }
}
