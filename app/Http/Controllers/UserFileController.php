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
        UserFile::insert(["path"=>$path,"type"=>UserFile::AUDIO,"user_id"=>auth()->user()->id]);
        return response()->success("File Saved");

    }
    public function audioFiles()
    {
        return response()->json(auth()->user()->audioFiles);
    }
    public function videoStore()
    {
        request()->validate(["file"=>"required|mime:mp4"]);
        //Code for google drive file upload
        $path=null;
        UserFile::insert(["path"=>$path,"type"=>UserFile::VIDEO,"user_id"=>auth()->user()->id]);
        return response()->json("Video Saved!");
    }

}
