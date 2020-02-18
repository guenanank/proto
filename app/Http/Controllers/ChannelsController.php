<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

<<<<<<< HEAD
use App\Models\MongoDB\Groups;
use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
=======
use App\Models\Channels;
use App\Models\Sites;
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
use Recursive;
use ZipArchive;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = Cache::rememberForever('channels:all', function () {
            return Channels::latest('lastUpdate')->get();
        });

        return view('channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
<<<<<<< HEAD
        $groups = Cache::get('groups:all');
        $recursive = Recursive::make(Cache::get('channels:all'), 'id', 'sub');
        $channels = Recursive::data($recursive, 'name');
        // $channels = Cache::get('channels:all');
        return view('channels.create', compact('groups', 'channels'));
=======
        $channels = Channels::all();
        $sites = Sites::all();
        return view('channels.create', compact('channels','sites'));
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $data = $request->all();
<<<<<<< HEAD
        $media = Cache::get('media:' . $request->mediaId);
        $path = sprintf('%s/%s/channels/', Str::slug($media->group->name), Str::slug($media->name));
=======
        $value = $request->VisualInteractiveFile;

        foreach($value as $value_visual){
        $filename = $value_visual->getClientOriginalName();
        $data['collaboration']['VisualInteractiveFIle'] .= $filename.','; 
        $value_visual->storeAs('folder', $filename);
        $zip = new ZipArchive();
        $target_path = public_path('storage/folder/').$filename;
        $x = $zip->open($target_path);
		        if ($x) {
		        	$zip->extractTo(public_path('storage/folder/'));
                    $zip->close();
                    storage::delete($target_path);
                }

        }
        $covername = ''; 
        $data['VisualInteractiveCover']->storeAs('folder/'.$filename, $covername);
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $data['meta']['cover'] = Str::slug($request->name) . '.' . $request->cover->extension();
            $request->cover->storeAs($path, $data['meta']['cover']);
        }
        $data['collaboration']['VisualInteractiveName'] = $data['VisualInteractiveName'];

        $create = Channels::create($data);
<<<<<<< HEAD
        Cache::forget('channels:all');
        Cache::forever('channels:' . $create->_id, $create);
=======
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
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
<<<<<<< HEAD
        $groups = Cache::get('groups:all');
        // $recursive = Recursive::make(Channels::all(), '_id', 'sub');
        // $channels = Recursive::data($recursive, 'name');
        $channels = Cache::get('channels:all');
        // $channels = Channels::all();
        return view('channels.edit', compact('groups', 'channels', 'channel'));
=======
        $channels = Channels::all();
        return view('channels.edit', compact('channels', 'channel'));
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
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
              Rule::unique($channel->getTable())->ignore($channel->_id),
          ]
        ])->toArray());

        $data = $request->all();
        $media = Cache::get('media:' . $request->mediaId);
        $path = sprintf('%s/%s/channels/', Str::slug($media->group->name), Str::slug($media->name));
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $data['meta']['cover'] = Str::slug($request->name) . '.' . $request->cover->extension();
            if ($channel->meta->has('cover') && Storage::exists($path . $channel->meta['cover'])) {
                Storage::delete($path . $channel->meta['cover']);
            }
            $request->cover->storeAs($path, $data['meta']['cover']);
        } else {
            $data['meta']['cover'] = $channel->meta['cover'];
        }

        $update = $channel->update($data);
        Cache::forget('channels:' . $channel->_id);
        Cache::forget('channels:all');
        Cache::forever('channels:' . $channel->_id, $channel);
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
<<<<<<< HEAD
        $channel->media->load('group');
        $path = sprintf('%s/%s/channel/', Str::slug($channel->media->group->name), Str::slug($channel->media->name));
        if ($channel->meta->has('cover') && Storage::exists($path . $channel->meta['cover'])) {
            Storage::delete($path . $channel->meta['cover']);
        }

        Cache::forget('channels:' . $channel->_id);
        Cache::forget('channels:all');
        $delete = $channel->delete();
=======
        $users = Channels::where('id',$id)->first();
        $delete = $users->delete($users);
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
        return response()->json($delete);
    }
}
