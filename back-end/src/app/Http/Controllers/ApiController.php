<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends BaseController
{
    public function index()
    {
        return $this->sendResponse('api is running...', []);
    }
}
