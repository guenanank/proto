<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Cache::rememberForever('media:all', function () {
            return Media::with('group')->latest('lastUpdate')->get();
        });

        return view('media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Cache::get('groups:all')->pluck('name', 'id');
        return view('media.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Media::rules()->toArray());
        $data = $request->all();
        $group = Cache::get('groups:' . $request->groupId);
        $path = sprintf('%s/%s/media/', Str::slug($group->name), Str::slug($request->name));

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $data['assets']['logo'] = Str::slug($request->name) . '-logo.' . $request->logo->extension();
            $request->logo->storeAs($path, $data['assets']['logo']);
        }

        if ($request->hasFile('logoAlt') && $request->file('logoAlt')->isValid()) {
            $data['assets']['logoAlt'] = Str::slug($request->name) . '-logo-alt.' . $request->logoAlt->extension();
            $request->logoAlt->storeAs($path, $data['assets']['logoAlt']);
        }

        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $data['assets']['icon'] = Str::slug($request->name) . '-icon.' . $request->icon->extension();
            $request->icon->storeAs($path, $data['assets']['icon']);
        }

        if ($request->hasFile('css') && $request->file('css')->isValid()) {
            $data['assets']['css'] = Str::slug($request->name) . '.' . $request->css->extension();
            $request->css->storeAs($path, $data['assets']['css']);
        }

        if ($request->hasFile('js') && $request->file('js')->isValid()) {
            $data['assets']['js'] = Str::slug($request->name) . '.' . $request->js->extension();
            $request->js->storeAs($path, $data['assets']['js']);
        }

        $create = Media::create($data);
        Cache::forget('media:all');
        Cache::forever('media:' . $create->_id, $create);
        return response()->json($create);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $medium)
    {
        $groups = Cache::get('groups:all')->pluck('name', 'id');
        return view('media.edit', compact('medium', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $medium)
    {
        Validator::make($request->all(), $medium->rules([
          'name' => [
              'required', 'string', 'max:63',
              Rule::unique($medium->getTable())->ignore($medium->_id),
          ]
        ])->toArray());

        $data = $request->all();
        $group = Cache::get('groups:' . $request->groupId);
        $path = sprintf('%s/%s/media/', Str::slug($group->name), Str::slug($request->name));

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $data['assets']['logo'] = Str::slug($request->name) . '-logo.' . $request->logo->extension();
            if ($medium->assets->has('logo') && Storage::exists($path . $medium->assets['logo'])) {
                Storage::delete($path . $medium->assets['logo']);
            }
            $request->logo->storeAs($path, $data['assets']['logo']);
        } else {
            $data['assets']['logo'] = $medium->assets['logo'];
        }

        if ($request->hasFile('logoAlt') && $request->file('logoAlt')->isValid()) {
            $data['assets']['logoAlt'] = Str::slug($request->name) . '-logo-alt.' . $request->logoAlt->extension();
            if ($medium->assets->has('logoAlt') && Storage::exists($path . $medium->assets['logoAlt'])) {
                Storage::delete($path . $medium->assets['logoAlt']);
            }
            $request->logoAlt->storeAs($path, $data['assets']['logoAlt']);
        } else {
            $data['assets']['logoAlt'] = $medium->assets['logoAlt'];
        }

        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $data['assets']['icon'] = Str::slug($request->name) . '-icon.' . $request->icon->extension();
            if ($medium->assets->has('icon') && Storage::exists($path . $medium->assets['icon'])) {
                Storage::delete($path . $medium->assets['icon']);
            }
            $request->icon->storeAs($path, $data['assets']['icon']);
        } else {
            $data['assets']['icon'] = $medium->assets['icon'];
        }

        if ($request->hasFile('css') && $request->file('css')->isValid()) {
            $data['assets']['css'] = Str::slug($request->name) . '.' . $request->css->extension();
            if ($medium->assets->has('css') && Storage::exists($path . $medium->assets['css'])) {
                Storage::delete($path . $medium->assets['css']);
            }
            $request->css->storeAs($path, $data['assets']['css']);
        } else {
            $data['assets']['css'] = $medium->assets['css'];
        }

        if ($request->hasFile('js') && $request->file('js')->isValid()) {
            $data['assets']['js'] = Str::slug($request->name) . '.' . $request->js->extension();
            if ($medium->assets->has('js') && Storage::exists($path . $medium->assets['js'])) {
                Storage::delete($path . $medium->assets['js']);
            }
            $request->js->storeAs($path, $data['assets']['js']);
        } else {
            $data['assets']['js'] = $medium->assets['js'];
        }

        $update = $medium->update($data);
        Cache::forget('media:' . $medium->_id);
        Cache::forget('media:all');
        Cache::forever('media:' . $medium->_id, $medium);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $medium)
    {
        $medium->load('group');
        if ($medium->has('group') || $medium->group->isNotEmpty()) {
            $path = sprintf('%s/%s/media/', Str::slug($group->name), Str::slug($medium->name));

            if ($medium->assets->has('logo') && Storage::exists($path . $medium->assets['logo'])) {
                Storage::delete($path . $medium->assets['logo']);
            }

            if ($medium->assets->has('logoAlt') && Storage::exists($path . $medium->assets['logoAlt'])) {
                Storage::delete($path . $medium->assets['logoAlt']);
            }

            if ($medium->assets->has('icon') && Storage::exists($path . $medium->assets['icon'])) {
                Storage::delete($path . $medium->assets['icon']);
            }

            if ($medium->assets->has('css') && Storage::exists($path . $medium->assets['css'])) {
                Storage::delete($path . $medium->assets['css']);
            }

            if ($medium->assets->has('js') && Storage::exists($path . $medium->assets['js'])) {
                Storage::delete($path . $medium->assets['js']);
            }
        }
        Cache::forget('media:' . $medium->_id);
        Cache::forget('media:all');
        $delete = $medium->delete();
        return response()->json($delete);
    }
}
