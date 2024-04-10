<?php

namespace App\Contracts;

interface JSONFormatter
{
    public function success($data, $statusCode = 200);
    public function fail($msg, $statusCode = 400);
    public function error($msg, $statusCode = 400);
}
