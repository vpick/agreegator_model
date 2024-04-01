<?php

namespace App\Http\Controllers;

use App\Models\SystemMaster;
use Illuminate\Http\Request;

class SystemMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-app.admin-master-settings');
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
     * @param  \App\Models\SystemMaster  $systemMaster
     * @return \Illuminate\Http\Response
     */
    public function show(SystemMaster $systemMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemMaster  $systemMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemMaster $systemMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemMaster  $systemMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemMaster $systemMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemMaster  $systemMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemMaster $systemMaster)
    {
        //
    }
}
