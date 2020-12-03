<?php

namespace App\Http\Controllers;

use App\Models\KylaProcess;
use App\Utils\Helper;
use Illuminate\Support\Facades\Request;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

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


        $kylaProcess = KylaProcess::create([
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

        //Send Report
        try {
            self::sendReport($kylaProcess);
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
        return response()->success("Info saved");
    }

    private
    function sendReport(KylaProcess $kylaProcess)
    {
        if ($kylaProcess->alert_via_sms) {
            try {
                Helper::sendMessage("127.0.0.1:8000/kylaProcess/1", "+923315743763");
            } catch (ConfigurationException $e) {
                throw new \Exception($e->getMessage());

            } catch (TwilioException $e) {
                throw new \Exception($e->getMessage());
            }

        }
//        $kylaProcess->alert_via_email ? Helper::sendMessage() : false;

    }

    public
    function show(KylaProcess $kylaProcess)
    {
        if (Request::wantsJson()) {
            return response()->success($kylaProcess);
        } else {
            return view('kylaProcess.report', ["kylaProcess" => $kylaProcess]);
        }
    }

}
