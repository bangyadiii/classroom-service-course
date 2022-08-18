<?php

use Illuminate\Support\Facades\Http;


function getUser($userId = ''){
    $url = env("URL_SERVICE_USER") . "/api/v1/auth/" . $userId ;

    try {
        $response = Http::timeout(5)->get($url);
        $data = $response->json();
        $data["http_code"] = $response->getStatusCode();
        return $data;

    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "http_code" => 500,
            "message" => "service user unavailable.",
        ];
    }

}


function getUserById($userId = []){
    $url = env("URL_SERVICE_USER") . "/api/v1/auth" ;

    try {
        if(count($userId) === 0) {
            return [
                "status" => "error",
                "http_code" => 200,
                "data" => []
            ];
        }

        $response = Http::timeout(5)->get($url, ["user_ids[]" => $userId]);
        $data = $response->json();
        $data["http_code"] = $response->getStatusCode();
        return $data;

    } catch (\Throwable $th) {
        return [
            "status" => "error",
            "http_code" => 500,
            "message" => "service user unavailable.",
        ];
    }

}

