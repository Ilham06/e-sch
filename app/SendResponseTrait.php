<?php

namespace App;

trait SendResponseTrait
{
    public function sendResponse($data, $message, $statusCode)
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
