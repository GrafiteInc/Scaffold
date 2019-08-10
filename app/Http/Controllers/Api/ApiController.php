<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }
}