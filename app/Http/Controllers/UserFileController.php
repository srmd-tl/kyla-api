<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Google\Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class UserFileController extends Controller
{
    public function audioStore()
    {
//        request()->validate(["file" => "required|mimes:mpga,wav,mp3"]);
        //upload to google drive code

        // Get the API client and construct the service object.
        $client = self::getClient();
        $service = new Google_Service_Drive($client);
        // Now lets try and send the metadata as well using multipart!
        $file = new Google_Service_Drive_DriveFile();
        $file->setName(request()->file('file')->getClientOriginalName());
        $result2 = $service->files->create(
            $file,
            array(
                'data' => file_get_contents(request()->file),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );
        //
        $path = $result2->getId();
        UserFile::insert(["path" => $path, "type" => UserFile::AUDIO, "user_id" => auth()->user()->id]);
        return response()->success("File Saved");

    }

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Google_Service_Drive::DRIVE);
        try {
            $client->setAuthConfig(public_path() . '/credentials.json');
        } catch (Exception $e) {
            print_r($e);
        }
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = public_path() . '/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));
                print_r($authCode);
                echo "eh";

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function audioFiles()
    {
        return response()->success(auth()->user()->audioFiles);
    }

    public function videoStore()
    {
        request()->validate(["file" => "required|mime:mp4"]);
        //Code for google drive file upload
        $path = null;
        UserFile::insert(["path" => $path, "type" => UserFile::VIDEO, "user_id" => auth()->user()->id]);
        return response()->json("Video Saved!");
    }

    public function videoFiles()
    {
        return response()->success(auth()->user()->videoFiles);

    }


}
