<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Intent;

class ChatController extends Controller
{

    public function index(Request $request) {
        return view('pages.chat');
    }

    public function query(Request $request) {
        $query = $request->content;
        $url = "https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/c352ce32-48a9-453a-9be0-6035f0d180d2?spellCheck=true&bing-spell-check-subscription-key=%7BYOUR_BING_KEY_HERE%7D&verbose=true&timezoneOffset=-360&subscription-key=ba7fe1dee51d4b48a73f83a96a6ce708&q=".urlencode($query);
        $response = file_get_contents($url);
        return response()->json([
            'luis' => json_decode($response),
            'message' => 'OK',
            'result' => Intent::proccess(json_decode($response))
        ]);
    }

    public function token(Request $request) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['SERVER_NAME']);

        $subscriptionKey = env('SPEECH_SDK_AUTH_KEY', '10e4ac7594994881bc939740fa43fc52');
        $region = 'westus';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://' . $region . '.api.cognitive.microsoft.com/sts/v1.0/issueToken');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Ocp-Apim-Subscription-Key: ' . $subscriptionKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }

}
