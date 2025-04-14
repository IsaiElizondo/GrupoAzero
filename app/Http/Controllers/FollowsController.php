<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Log;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FollowsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $follows = Follow::where('user_id', auth()->user()->id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('follows.index', compact('follows', 'role', 'department'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all(), $id, 'follow');
        Follow::create([
            'user_id' => auth()->user()->id,
            'order_id' => $id
        ]);

        $follows = Follow::where('user_id', auth()->user()->id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('follows.index', compact('follows', 'role', 'department'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $follow = Follow::find($id);
        // dd($follow);
        $follow->delete();

        $follows = Follow::where('user_id', auth()->user()->id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('follows.index', compact('follows', 'role', 'department'));
    }
}
