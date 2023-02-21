<?php

use Illuminate\Support\Facades\Http;


function error($code = 500, $message = "")
{
    return [
        "meta" => [
            "success" => false,
            "http_code" => $code,
            "message" => $message ?? "service user unavailable.",
        ],
        "data" => null
    ];
}

function getUser($userId = '')
{
    $url = env("URL_SERVICE_USER") . "/api/v1/auth/" . $userId;

    try {
        $response = Http::timeout(5)->acceptJson()->get($url);
        $data = $response->json();
        $data["meta"]["http_code"] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return error(500);
    }
}


function getUserById($userId = [])
{
    $url = env("URL_SERVICE_USER") . "/api/v1/auth";

    try {
        if (count($userId) === 0) {
            return error(400, "BAD REQUEST");
        }

        $response = Http::timeout(5)->acceptJson()->get($url, ["user_ids[]" => $userId]);
        $data = $response->json();
        $data["meta"]["http_code"] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return error(500);
    }
}

function createOrder($params = [])
{
    $url = env("URL_SERVICE_ORDER_PAYMENT") . "/api/v1/orders/";

    try {
        if (count($params) === 0) {
            return error(400, "Bad Request");
        }
        $response = Http::timeout(5)->acceptJson()->post($url, $params);
        $data = $response->json();
        $data["meta"]["http_code"] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return error();
    }
}

function postCourseImage($files = [])
{
    $url = env("URL_SERVICE_MEDIA") . "/api/v1/media";
    try {
        if (count($files) <= 0) {
            return error(400, "BAD REQUEST");
        }

        $response = Http::timeout(5)->acceptJson()->post($url, $files);
        $data = $response->json();

        return $data;
    } catch (\Throwable $th) {
        return error(400, $th->getMessage());
    }
}
