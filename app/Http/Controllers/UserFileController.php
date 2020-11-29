<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Illuminate\Http\Request;

class UserFileController extends Controller
{
    public function audioStore()
    {
        request()->validate(["file"=>"required|mimes:mpga,wav,mp3"]);
        //upload to google drive code
        $path=null;
        UserFile::insert(["path"=>$path,"type"=>UserFile::AUDIO]);
        return response()->success("File Saved");

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\UserFile  $userFile
     * @return \Illuminate\Http\Response
     */
    public function show(UserFile $userFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserFile  $userFile
     * @return \Illuminate\Http\Response
     */
    public function edit(UserFile $userFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserFile  $userFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFile $userFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFile  $userFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFile $userFile)
    {
        //
    }
}
