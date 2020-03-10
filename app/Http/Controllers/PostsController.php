<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Posts;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        return view('posts.' . $type . '.index');
    }

    /**
     * Resource JSON data.
     *
     * @return \DataTables
     */
    public function dataTable(Request $request)
    {
        $posts = Cache::rememberForever('posts:' . strtolower($request->type) . ':all', function () use($request) {
            return Posts::where('type', $request->type)->latest('lastUpdate')->get();
        });

        return datatables($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MongoDB\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Posts $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MongoDB\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Posts $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MongoDB\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posts $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MongoDB\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $post)
    {
        //
    }
}
