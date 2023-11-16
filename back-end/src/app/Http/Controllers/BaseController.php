<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
    /**
    * return a success response json
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function sendResponse($message = 'Ok', $response_data = null, array $additional_values = [], $http_code = 200): JsonResponse
    {
        $response = [
            'success'     => true,
            'status_text' => 'success',
            'message'     => $message
        ];

        if (!is_null($response_data)) {
            $response['data'] = $response_data;
        }

        if (sizeof($additional_values)) {
            foreach ($additional_values as $key => $value) {
                $response[$key] = $value;
            }
        }

        return Response::json($response, $http_code);
    }

    /**
    * return error response.
    *
    * @return \Illuminate\Http\Response
    */
    public function sendError($error, array $data = null, int $code = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'status_text'  => 'error',
            'message' => $error,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
