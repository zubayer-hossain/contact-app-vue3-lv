<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

if (!function_exists('successResponse')) {
    /**
     * @param null $message
     * @param array|Collection|Model|null $data
     * @return JsonResponse
     */
    function successResponse ($message = null, array|Collection|Model $data = null): Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * @param $message
     * @param int $status
     * @return JsonResponse
     */
    function errorResponse ($message = null, int $status = 422): Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => null
        ], $status);
    }
}