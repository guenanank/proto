<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $columns = ['topics.id', 'topics.site_id', 'topics.title', 'topics.slug', 'topics.published', 'topics.meta', 'topics.created_at', 'topics.updated_at'];
        $topics = Topics::select($columns);
        return datatables()->eloquent($topics)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('topics.create');
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
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $create = Topics::create($data);

        Cache::tags(['site', 'topics'])->forever($create->id, $create);
        $all = Cache::pull('topics');
        if (is_null($all)) {
            $all = collect()->put($create->id, $create);
        } else {
            $all->put($create->id, $create);
        }
        Cache::forever('topics', $all->sort());

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
        return view('topics.edit', compact('topic'));
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

        $data = $request->all();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $filename = Str::slug($request->name) . '.' . $request->cover->extension();
            if (Storage::exists('images' . $topic->meta->cover)) {
                Storage::delete('images' . $topic->meta->cover);
            }
            $request->cover->storeAs('images', $filename);
            $data['meta']['cover'] = $filename;
        }

        $update = $topic->update($data);

        Cache::tags(['site', 'topics'])->forget($topic->id);
        Cache::tags(['site', 'topics'])->forever($topic->id, $topic);

        $all = Cache::pull('topics');
        if (is_null($all)) {
            $all = collect()->put($topic->id, $topic);
        } else {
            $all->forget($topic->id)->put($topic->id, $topic);
        }
        Cache::set('topics', $all->sort());

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
        Cache::tags(['site', 'topics'])->forget($topic->id);
        $all = Cache::pull('topics');
        $all->forget($topic->id);
        Cache::forever('topics', $all->sort());

        if (Storage::exists('images' . $topic->meta->cover)) {
            Storage::delete('images' . $topic->meta->cover);
        }
        $delete = $topic->delete();
        return response()->json($delete);
    }
}
