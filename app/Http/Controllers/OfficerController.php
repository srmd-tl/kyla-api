<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    //
    public function officer()
    {
        request()->validate(["name" => "required", "number" => "required"]);
        Officer::insert(["name" => request()->name, "number" => request()->number]);
        return response()->success('Officer Saved!');
    }
    public function getOfficer()
    {
        return response()->success(auth()->user()->officer);

    }

}
