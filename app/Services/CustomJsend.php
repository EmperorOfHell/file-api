<?php

namespace App\Services;

use App\Contracts\JSONFormatter;

class CustomJsend implements JSONFormatter
{

    public function success($data = null, $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $statusCode);
    }

    public function fail($data = null, $statusCode = 400)
    {
        return response()->json([
            'status' => 'fail',
            'data' => $data
        ], $statusCode);
    }

    public function error($msg, $statusCode = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $msg
        ], $statusCode);
    }

}
