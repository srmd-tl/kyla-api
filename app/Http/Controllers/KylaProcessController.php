<?php

namespace App\Http\Controllers;

use App\Mail\SendReport;
use App\Models\KylaProcess;
use App\Utils\Helper;
use Exception;
use Illuminate\Support\Facades\Mail;
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
            "audioFile" => "required|mimes:3gp",
            "videoFile" => "mimes:mp4,",
            "officerName" => "required",
            "officerNumber" => "required",
            "location" => "required",
            "law" => "required",
        ]);
        //upload to google drive and audio file id
        if (request()->audioFile) {
//            $audioPath = Helper::storeOnGdrive(request()->audioFile, request()
//                ->file("audioFile")->getClientOriginalName());
            $audioPath = request()->file("audioFile")->store("files");
        }
        if (request()->videoFile) {

            //upload to google drive and video file id
//            $videoPath = Helper::storeOnGdrive(request()->audioFile, request()
//                ->file("videoFile")->getClientOriginalName());
            $audioPath = request()->file("videoFile")->store("files");

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
        if (request()->viaSms > 0) {
            try {
                self::sendReport($kylaProcess);
            } catch (Exception $e) {
                return response()->error($e->getMessage());
            }
        }

        return response()->success("Info saved");
    }

    private function sendReport(KylaProcess $kylaProcess)
    {
        if ($kylaProcess->alert_via_sms) {
            try {
                $url = sprintf("%s/kylaProcess/%s", env('APP_URL'), $kylaProcess->id);
                Helper::sendMessage($url, env("SEND_SMS_TO"));
            } catch (ConfigurationException $e) {
                throw new Exception($e->getMessage());

            } catch (TwilioException $e) {
                throw new Exception($e->getMessage());
            }

        }
        if ($kylaProcess->alert_via_email) {
            try {
                Mail::to(auth()->user()->email)->send(new SendReport($kylaProcess));
            } catch (\Exception $exception) {
                return response()->error($exception->getMessage());
            }
        }
//        $kylaProcess->alert_via_email ? Helper::sendMessage() : false;

    }

    public function show(KylaProcess $kylaProcess)
    {
        if (Request::wantsJson()) {
            return response()->success($kylaProcess);
        } else {
            return view('kylaProcess.report', ["kylaProcess" => $kylaProcess]);
        }
    }

    public function index()
    {
        return response()->success(auth()->user()->kylaProcesses);
    }
}
