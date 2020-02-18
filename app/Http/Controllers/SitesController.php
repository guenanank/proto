<?php

namespace App\Http\Controllers;

use App\Models\Sites;
use App\Models\Networks;
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
        $networks= Networks::pluck('name','id');
        return view('sites.create', compact('networks'));
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
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
         $filename = Str::slug($request->name) . '.' . $request->logo->extension();
            $request->logo->storeAs('images', $filename);
            $data['meta']['logo'] = $filename;
        }
        if ($request->hasFile('shortcut_icon') && $request->file('shortcut_icon')->isValid()) {
         $filename = Str::slug($request->name) . '.' . $request->shortcut_icon->extension();
            $request->shortcut_icon->storeAs('images', $filename);
            $data['meta']['shortcut_icon'] = $filename;
        }
        if ($request->hasFile('css')) {
            $cssName='';
            foreach ($request->file('css') as $css) {
                $css_filename = $css->getClientOriginalName();
                $css->storeAs('css', $css_filename);
                $cssName .= $css_filename.','; 
                $css_filenames = explode(',',$cssName);
            }
            $data['meta']['css']    = $css_filenames;
        }

        if ($request->hasFile('js')) {
            $jsName='';
                foreach ($request->file('js') as $js) {
                    $js_filename = $js->getClientOriginalName();
                    $js->storeAs('js', $js_filename);
                    $jsName .= $js_filename.','; 
                    $js_filenames = explode(',',$jsName);
            }
            $data['meta']['js']     = $js_filenames;
        }            
       
        $data['meta']['keywords'] = explode(',',$request->meta['keywords']);
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
        $networks= Networks::pluck('name','id');
        return view('sites.edit', compact('site','networks'));
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

        if ($request->hasFile('logo')) {
            $filename = Str::slug($request->name) . '.' . $request->logo->extension();
            // dd(Storage::exists('images' . $site->meta->cover));
            if (Storage::exists('images' . $site->meta->logo)) {
                Storage::delete('images' . $site->meta->logo);
            }
            $request->logo->storeAs('images', $filename);
            $data['meta']['logo'] = $filename;
        }

        if ($request->hasFile('shortcut_icon')) {
            $filename = Str::slug($request->name) . '.' . $request->shortcut_icon->extension();
            // dd(Storage::exists('images' . $site->meta->cover));
            if (Storage::exists('images' . $site->meta->shortcut_icon)) {
                Storage::delete('images' . $site->meta->shortcut_icon);
            }
            $request->shortcut_icon->storeAs('images', $filename);
            $data['meta']['shortcut_icon'] = $filename;
        }

        if ($request->hasFile('css')) {
            $cssName='';
            foreach ($request->file('css') as $css) {

                if (Storage::exists('css' . $site->meta->css)) {
                    Storage::delete('css' . $site->meta->css);
                }
                $css_filename = $css->getClientOriginalName();
                $css->storeAs('css', $css_filename);
                $cssName .= $css_filename.','; 
                $css_filenames = explode(',',$cssName);
            }
            $data['meta']['css']    = $css_filenames;
        }else{
            $data['meta']['css'] = explode(',',$request->meta['css']);
        }

        if ($request->hasFile('js')) {
            $jsName='';
                foreach ($request->file('js') as $js) {
                    if (Storage::exists('JS' . $site->meta->js)) {
                        Storage::delete('js' . $site->meta->js);
                    }

                    $js_filename = $js->getClientOriginalName();
                    $js->storeAs('js', $js_filename);
                    $jsName .= $js_filename.','; 
                    $js_filenames = explode(',',$jsName);
            }
            $data['meta']['js']     = $js_filenames;
        }else{
            $data['meta']['js'] = explode(',',$request->meta['js']);
        }     

        $data['meta']['keywords'] = explode(',',$request->meta['keywords']);
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
