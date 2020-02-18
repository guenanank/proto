<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Galleries;

class GalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $galleries = Galleries::where('type', $type)->latest('creationDate')->simplePaginate(7);
        return view('galleries.' . $type . '.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        return view('galleries.'. $type . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('files')) {
            $data = $request->all();
            foreach ($request->file('files') as $file) {
                list($width, $height) = getimagesize($file);
                $filename = sprintf('%s-%s.%s', Str::slug($request->meta['caption']), substr(encrypt($request->meta['caption']), -7), $file->extension());
                $file->storeAs('images', $filename);
                $data['meta']['extension'] = $file->extension();
                $data['meta']['filename'] = $filename;
                $data['meta']['path'] = asset('storage/images/' . $filename);
                $data['meta']['size'] = $file->getSize();
                $data['meta']['dimension']['width'] = $width;
                $data['meta']['dimension']['height'] = $height;
                $create = Galleries::create($data);
                Cache::forever('galleries:' . $request->type . ':' . $create->id, $create);
            }
        }

        Cache::forget('galleries:' . $request->type . ':all');
        return response()->json(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Galleries  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($type, Galleries $gallery)
    {
        return view('galleries.'. $type . '.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Galleries  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update($type, Request $request, Galleries $gallery)
    {
        if ($request->hasFile('files')) {
            $data = $request->all();
            foreach ($request->file('files') as $file) {
                list($width, $height) = getimagesize($file);
                $filename = sprintf('%s-%s.%s', Str::slug($request->meta['caption']), substr(encrypt($request->meta['caption']), -7), $file->extension());
                $file->storeAs('images', $filename);
                $data['meta']['extension'] = $file->extension();
                $data['meta']['filename'] = $filename;
                $data['meta']['path'] = asset('storage/images/' . $filename);
                $data['meta']['size'] = $file->getSize();
                $data['meta']['dimension']['width'] = $width;
                $data['meta']['dimension']['height'] = $height;
                $gallery->update($data);
                Cache::forget('galleries:' . $gallery->type . ':' . $gallery->id);
                Cache::forever('galleries:' . $gallery->type . ':' . $gallery->id, $gallery);
            }
        } else {
            $data = collect($gallery->meta)->merge($request->all())->toArray();
            $gallery->update($data);
            Cache::forget('galleries:' . $gallery->type . ':' . $gallery->id);
            Cache::forever('galleries:' . $gallery->type . ':' . $gallery->id, $gallery);
        }

        Cache::forget('galleries:' . $gallery->type . ':all');
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Galleries  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy($type, Galleries $gallery)
    {
        Cache::forget('galleries:' . $gallery->type . ':' . $gallery->id);
        Cache::forget('galleries:' . $gallery->type . ':all');
        if (Storage::exists('storage/images/' . $gallery->meta->filename)) {
            Storage::delete('storage/images/' . $gallery->meta->filename);
        }
        $delete = $gallery->delete();
        return response()->json($delete);
    }
}
