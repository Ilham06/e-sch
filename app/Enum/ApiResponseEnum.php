<?php

namespace App\Enum;

enum ApiResponseEnum: int
{
    case Success = 200;
    case Created = 201;
    case BadRequest = 400;
    case Unauthorized = 401;
    case InternalError = 500;
    case Custom = 0;

    public function description(): object
    {
        return match ($this) {
            self::Success => (object)['status' => 'OK', 'response_code' => $this->value, 'message' => 'Operation Successful'],
            self::Created => (object)['status' => 'OK', 'response_code' => $this->value, 'message' => 'Resource Created Successfully'],
            self::BadRequest => (object)['status' => 'ERROR', 'response_code' => $this->value, 'message' => 'Bad Request'],
            self::Unauthorized => (object)['status' => 'ERROR', 'response_code' => $this->value, 'message' => 'Unautorize Access'],
            self::InternalError => (object)['status' => 'ERROR', 'response_code' => $this->value, 'message' => 'Internal Server Error'],
        };
    }

    public function customDescription($status, $message, $response_code): object
    {
       
        return (object)['status' => $status, 'response_code' => $response_code, 'message' => $message];
    }
}
