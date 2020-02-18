<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Groups;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Cache::rememberForever('groups:all', function () {
            return Groups::with('media')->latest('lastUpdate')->get();
        });

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Groups::rules()->toArray());
        $create = Groups::create($request->all());
        Cache::forget('groups:all');
        Cache::forever('groups:' . $create->_id, $create);
        return response()->json($create);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function edit(Groups $group)
    {
        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Groups $group)
    {
        Validator::make($request->all(), $group->rules([
          'code' => [
              'present', 'alpha_num', 'max:15',
              Rule::unique($group->getTable())->ignore($group->_id),
          ]
        ])->toArray());

        $update = $group->update($request->all());
        Cache::forget('groups:' . $group->_id);
        Cache::forget('groups:all');
        Cache::forever('groups:' . $group->_id, $group);
        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function destroy(Groups $group)
    {
        Cache::forget('groups:all');
        Cache::forget('groups:' . $group->_id);
        $delete = $group->delete();
        return response()->json($delete);
    }
}
