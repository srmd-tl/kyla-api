<?php


namespace App\Utils;


use Google\Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class Helper
{

    /**
     * @param string $message
     * @param $recipients
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public static function sendMessage(string $message, $recipients)
    {
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER");
        $client = new Client(
            $account_sid, $auth_token);
        try {
             $client->messages->create($recipients,
                ['from' => $twilio_number, 'body' => $message]);
        } catch (TwilioException $e) {
            throw new TwilioException($e->getMessage()
            );
        }
    }

    public static function storeOnGdrive($file, string $fileName): string
    {
        // Get the API client and construct the service object.
        $client = self::getClient();
        $service = new Google_Service_Drive($client);
        // Now lets try and send the metadata as well using multipart!
        $gFile = new Google_Service_Drive_DriveFile();
        $gFile->setName($fileName);
        $result2 = $service->files->create(
            $gFile,
            array(
                'data' => file_get_contents($file),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );
        //
        $publicPermission = new \Google_Service_Drive_Permission();
        $publicPermission->setType('anyone');
        $publicPermission->setRole('reader');
        $path = $result2->getId();
        $service->permissions->update($path,$publicPermission);

        return $path;
    }

    private static function getClient(): Google_Client
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

}

