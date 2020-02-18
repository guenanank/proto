<?php

namespace App\Http\Controllers;

use App\Models\Visualinteractives;
use Illuminate\Http\Request;
use ZipArchive;
use File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VisualInteractivesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visual_interactive = Visualinteractives::latest()->simplePaginate(7);
        return view('visualinteractive.index', compact('visual_interactive'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('visualinteractive.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file') || $request->hasFile('image') ) {
        $data = $request->all();
        $file_zip = $request->file('file');
        $image = $request->file('image');
        $original_name = $file_zip->getClientOriginalName();
        $file_zip->storeAs('folder/zip_folder', $original_name);
        $zip = new ZipArchive();
        $target_path = public_path('storage/folder/zip_folder/').$original_name;
        $extract = $zip->open($target_path);
		        if ($extract) {
		        	$zip->extractTo(public_path('storage/folder/'));
                    $zip->close();
                }
        $folder_name = Str::replaceLast('.'.$file_zip->extension(),'',$original_name);
        $data['cover']['file']['foldername'] = $folder_name;
        $data['cover']['file']['filename'] = $original_name;
        $data['cover']['file']['size'] = $file_zip->getSize().'kb';       
        $data['cover']['file']['path_extract_folder'] = asset('storage/folder/'.$folder_name);
        $data['cover']['file']['path_file_zip'] = $target_path;
        list($width, $height) = getimagesize($image);
        $filename = sprintf('%s.%s', $request->name, $image->extension());
        $image->storeAs('folder/'. $folder_name, $filename);
        $data['cover']['image']['extension'] = $image->extension();
        $data['cover']['image']['filename'] = $filename;
        $data['cover']['image']['path_image'] = asset('storage/folder/'. $folder_name . '/' . $filename);
        $data['cover']['image']['size'] = $image->getSize();
        $data['cover']['image']['dimension']['width'] = $width;
        $data['cover']['image']['dimension']['height'] = $height;
        $create = Visualinteractives::create($data);
        return response()->json($create);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VisualInteractive  $VisualInteractive
     * @return \Illuminate\Http\Response
     */
    public function show(Visualinteractives $visualinteractive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VisualInteractive  $visualInteraktif
     * @return \Illuminate\Http\Response
     */
    public function edit(Visualinteractives $visualinteractive)
    {
        return view('visualinteractive.edit', compact('visualinteractive'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VisualInteraktif  $visualInteraktif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visualinteractives $visualinteractive)
    {
        if ($request->hasFile('file') || $request->hasFile('image') ) {
            $data = $request->all();
            $file_zip = $request->file('file');
            $image = $request->file('image');
            $original_name = $file_zip->getClientOriginalName();
            $file_zip->storeAs('folder/zip_folder', $original_name);
            $zip = new ZipArchive();
            $target_path = public_path('storage/folder/zip_folder/').$original_name;
            $extract = $zip->open($target_path);
                    if ($extract) {
                        $zip->extractTo(public_path('storage/folder/'));
                        $zip->close();
                    }
            $folder_name = Str::replaceLast('.'.$file_zip->extension(),'',$original_name);
            $data['cover']['file']['foldername'] = $folder_name;
            $data['cover']['file']['filename'] = $original_name;
            $data['cover']['file']['size'] = $file_zip->getSize().'kb';       
            $data['cover']['file']['path_extract_folder'] = asset('storage/folder/'.$folder_name);
            $data['cover']['file']['path_file_zip'] = asset('storage/folder/'.$original_name);
            list($width, $height) = getimagesize($image);
            $filename = sprintf('%s.%s', $request->name, $image->extension());
            $image->storeAs('folder/'. $folder_name, $filename);
            $data['cover']['image']['extension'] = $image->extension();
            $data['cover']['image']['filename'] = $filename;
            $data['cover']['image']['path_image'] = asset('storage/folder/'. $folder_name . '/' . $filename);
            $data['cover']['image']['size'] = $image->getSize();
            $data['cover']['image']['dimension']['width'] = $width;
            $data['cover']['image']['dimension']['height'] = $height;
            $visualinteractive->update($data);
        } else {
            $data = collect($visualinteractive->cover)->merge($request->all())->toArray();
            $visualinteractive->update($data);
            
        }

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VisualInteractive  $visualInteraktif
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visualinteractives $visualinteractive)
    {
        $delete = $visualinteractive->delete();
        return response()->json($delete);
    }
}
