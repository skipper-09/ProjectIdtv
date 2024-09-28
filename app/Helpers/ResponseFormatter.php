<?php

namespace App\Helpers;

class ResponseFormatter
{
    /**
     * Handle success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Handle error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $errors = null, $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
