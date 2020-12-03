<?php

namespace App\Http\Controllers;

use App\Models\KylaProcess;
use App\Utils\Helper;

class KylaProcessController extends Controller
{
    public function store()
    {
        $audioPath = null;
        $videoPath = null;
        request()->validate([
            "audioFile" => "required|mimes:mpga,wav,mp3",
//            "videoFile" => "required|mimes:mp4",
            "officerName" => "required",
            "officerNumber" => "required",
            "location" => "required",
            "law" => "required",
        ]);
        //upload to google drive and audio file id
        if (request()->audioFile) {
            $audioPath = Helper::storeOnGdrive(request()->audioFile, request()
                ->file("audioFile")->getClientOriginalName());
        }
        if (request()->videoFile) {

            //upload to google drive and video file id
            $videoPath = Helper::storeOnGdrive(request()->audioFile, request()
                ->file("videoFile")->getClientOriginalName());
        }


        KylaProcess::create([
            "audio_path" => $audioPath,
            "video_path" => $videoPath,
            "officer_name" => request()->officerName,
            "officer_number" => request()->officerNumber,
            "location" => request()->location,
            "law" => request()->law,
            "alert_via_sms" => request()->viaSms ?? 0,
            "alert_via_email" => request()->viaEmail ?? 0,
            "user_id" => auth()->user()->id
        ]);
        return response()->success("Info saved");
    }

    public function show(KylaProcess $kylaProcess)
    {
        return response()->success($kylaProcess);
    }
}
