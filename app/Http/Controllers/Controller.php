<?php

namespace App\Http\Controllers;

use App\Exceptions\Code;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     * @param array $data
     * @param string $code
     * @return JsonResponse
     */
    public function sendResponse(
        string $message = '',
        array $data = [],
        $code = Code::SUCCESS,
    ): JsonResponse {
        $message = !empty($message) ? $message : trans('message.' . Code::SUCCESS);
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @param string $code
     * @return JsonResponse
     */
    public function sendError(
        string $message = '',
        array $data = [],
        $code = Code::FAILED,
    ): JsonResponse {
        $message = !empty($message) ? $message : trans('message.' . Code::FAILED);
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}
