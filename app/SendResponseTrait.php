<?php

namespace App;

trait SendResponseTrait
{
    public function sendResponse($data, $responseObject)
    {
        $status = $responseObject->status;
        $status_code = $responseObject->response_code;
        $message = $responseObject->message;

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }
}
